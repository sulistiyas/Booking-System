<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\User;
use App\Repositories\BookingItemRepository;
use App\Repositories\BookingRepository;
use App\Repositories\BookingStatusLogRepository;
use App\Repositories\ResourceRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BookingService
{
    public function __construct(
        protected BookingRepository          $bookingRepository,
        protected BookingItemRepository      $bookingItemRepository,
        protected BookingStatusLogRepository $statusLogRepository,
        protected ResourceRepository         $resourceRepository,
        protected BookingNumberGenerator     $numberGenerator,
        protected BookingConflictChecker     $conflictChecker,
    ) {}

    // ── Read ──────────────────────────────────────────────────────────────

    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->bookingRepository->paginate($filters, $perPage);
    }

    public function paginateForUser(User $user, array $filters = []): LengthAwarePaginator
    {
        return $this->bookingRepository->getByUser($user->id, $filters);
    }

    public function findOrFail(int $id): Booking
    {
        $booking = $this->bookingRepository->findById($id, [
            'user',
            'items.resource.resourceType',
            'statusLogs.changedBy',
            'approvedBy',
            'rejectedBy',
        ]);

        abort_if(! $booking, 404, 'Booking tidak ditemukan.');

        return $booking;
    }

    public function getStats(): array
    {
        $byStatus = $this->bookingRepository->countByStatus();

        return [
            'total'     => $byStatus->sum('total'),
            'by_status' => $byStatus->mapWithKeys(fn($r) => [$r->status => $r->total]),
            'upcoming'  => $this->bookingRepository->getUpcoming(5),
        ];
    }

    // ── Write ─────────────────────────────────────────────────────────────

    /**
     * Buat booking baru beserta semua item-nya.
     *
     * $data = [
     *   'title'  => '...',
     *   'notes'  => '...',
     *   'items'  => [
     *     ['resource_id' => 1, 'start_datetime' => '...', 'end_datetime' => '...', 'quantity' => 1, 'notes' => '...'],
     *     ...
     *   ]
     * ]
     */
    public function create(array $data, User $user): Booking
    {
        // 1. Validasi & cek konflik semua item
        $this->conflictChecker->check($data['items']);

        return DB::transaction(function () use ($data, $user) {

            // 2. Hitung aggregate start/end dari semua items
            $starts = array_column($data['items'], 'start_datetime');
            $ends   = array_column($data['items'], 'end_datetime');

            // 3. Hitung total harga dari semua items
            $items       = $this->buildItemsWithPrice($data['items']);
            $totalPrice  = array_sum(array_column($items, 'subtotal'));

            // 4. Buat booking
            $booking = $this->bookingRepository->create([
                'booking_number'   => $this->numberGenerator->generate(),
                'user_id'          => $user->id,
                'title'            => $data['title'],
                'notes'            => $data['notes'] ?? null,
                'status'           => 'pending',
                'payment_status'   => 'unpaid',
                'start_datetime'   => min($starts),
                'end_datetime'     => max($ends),
                'total_price'      => $totalPrice,
            ]);

            // 5. Simpan semua items
            $this->bookingItemRepository->createMany($booking, $items);

            // 6. Log status awal
            $this->statusLogRepository->log(
                booking:    $booking,
                fromStatus: null,
                toStatus:   'pending',
                reason:     'Booking dibuat.',
            );

            return $booking->load('items.resource.resourceType');
        });
    }

    /**
     * Update booking yang masih pending.
     */
    public function update(Booking $booking, array $data): Booking
    {
        $this->ensureEditable($booking);

        // Cek konflik, exclude booking saat ini
        $this->conflictChecker->check($data['items'], $booking->id);

        return DB::transaction(function () use ($booking, $data) {

            $starts     = array_column($data['items'], 'start_datetime');
            $ends       = array_column($data['items'], 'end_datetime');
            $items      = $this->buildItemsWithPrice($data['items']);
            $totalPrice = array_sum(array_column($items, 'subtotal'));

            // Hapus items lama, ganti dengan yang baru
            $this->bookingItemRepository->deleteByBooking($booking);

            $this->bookingRepository->update($booking, [
                'title'          => $data['title'],
                'notes'          => $data['notes'] ?? null,
                'start_datetime' => min($starts),
                'end_datetime'   => max($ends),
                'total_price'    => $totalPrice,
            ]);

            $this->bookingItemRepository->createMany($booking, $items);

            $this->statusLogRepository->log(
                booking:    $booking,
                fromStatus: $booking->status,
                toStatus:   $booking->status,
                reason:     'Data booking diperbarui.',
            );

            return $booking->fresh('items.resource.resourceType');
        });
    }

    // ── Status Transitions ────────────────────────────────────────────────

    public function approve(Booking $booking, ?string $reason = null): Booking
    {
        $this->ensureStatus($booking, ['pending'], 'disetujui');

        DB::transaction(function () use ($booking, $reason) {
            $this->bookingRepository->update($booking, [
                'status'      => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);

            $this->statusLogRepository->log(
                booking:    $booking,
                fromStatus: 'pending',
                toStatus:   'approved',
                reason:     $reason ?? 'Booking disetujui.',
            );
        });

        return $booking->fresh();
    }

    public function reject(Booking $booking, string $reason): Booking
    {
        $this->ensureStatus($booking, ['pending', 'approved'], 'ditolak');

        DB::transaction(function () use ($booking, $reason) {
            $this->bookingRepository->update($booking, [
                'status'            => 'rejected',
                'rejection_reason'  => $reason,
                'rejected_by'       => Auth::id(),
                'rejected_at'       => now(),
            ]);

            $this->statusLogRepository->log(
                booking:    $booking,
                fromStatus: $booking->status,
                toStatus:   'rejected',
                reason:     $reason,
            );
        });

        return $booking->fresh();
    }

    public function cancel(Booking $booking, string $reason): Booking
    {
        // User hanya bisa cancel booking miliknya sendiri yang masih pending
        // Admin/staff bisa cancel apa saja yang belum completed
        $user = Auth::user();

        if ($user->role->name === 'user') {
            $this->ensureOwner($booking, $user);
            $this->ensureStatus($booking, ['pending'], 'dibatalkan');
        } else {
            $this->ensureStatus($booking, ['pending', 'approved'], 'dibatalkan');
        }

        DB::transaction(function () use ($booking, $reason) {
            $this->bookingRepository->update($booking, [
                'status'               => 'cancelled',
                'cancellation_reason'  => $reason,
            ]);

            $this->statusLogRepository->log(
                booking:    $booking,
                fromStatus: $booking->status,
                toStatus:   'cancelled',
                reason:     $reason,
            );
        });

        return $booking->fresh();
    }

    public function complete(Booking $booking): Booking
    {
        $this->ensureStatus($booking, ['approved'], 'diselesaikan');

        DB::transaction(function () use ($booking) {
            $this->bookingRepository->update($booking, ['status' => 'completed']);

            $this->statusLogRepository->log(
                booking:    $booking,
                fromStatus: 'approved',
                toStatus:   'completed',
                reason:     'Booking selesai.',
            );
        });

        return $booking->fresh();
    }

    // ── Availability Check (untuk AJAX) ───────────────────────────────────

    public function checkAvailability(
        int $resourceId,
        string $start,
        string $end,
        ?int $excludeBookingId = null,
    ): array {
        $available = $this->conflictChecker->isAvailable(
            $resourceId, $start, $end, $excludeBookingId
        );

        $resource = $this->resourceRepository->findById($resourceId, ['resourceType']);

        return [
            'available' => $available,
            'resource'  => $resource ? [
                'id'             => $resource->id,
                'name'           => $resource->name,
                'price_per_unit' => $resource->price_per_unit,
                'price_unit'     => $resource->price_unit,
            ] : null,
        ];
    }

    // ── Private Helpers ───────────────────────────────────────────────────

    /**
     * Hitung harga per item berdasarkan resource + durasi.
     */
    private function buildItemsWithPrice(array $items): array
    {
        return array_map(function ($item) {
            $resource = $this->resourceRepository->findById($item['resource_id']);

            $quantity  = $item['quantity'] ?? 1;
            $unitPrice = $resource?->price_per_unit ?? 0;

            // Hitung durasi untuk price_unit = hour / day
            $duration = match ($resource?->price_unit) {
                'hour' => $this->diffInHours($item['start_datetime'], $item['end_datetime']),
                'day'  => $this->diffInDays($item['start_datetime'], $item['end_datetime']),
                default => 1, // session / item → tidak dihitung dari durasi
            };

            $subtotal = $unitPrice * $quantity * $duration;

            return [
                'resource_id'    => $item['resource_id'],
                'start_datetime' => $item['start_datetime'],
                'end_datetime'   => $item['end_datetime'],
                'quantity'       => $quantity,
                'unit'           => $resource?->price_unit ?? 'session',
                'unit_price'     => $unitPrice,
                'subtotal'       => $subtotal,
                'notes'          => $item['notes'] ?? null,
                'meta'           => $item['meta'] ?? null,
            ];
        }, $items);
    }

    private function diffInHours(string $start, string $end): float
    {
        return max(1, round(
            (strtotime($end) - strtotime($start)) / 3600, 2
        ));
    }

    private function diffInDays(string $start, string $end): float
    {
        return max(1, round(
            (strtotime($end) - strtotime($start)) / 86400, 2
        ));
    }

    private function ensureEditable(Booking $booking): void
    {
        if ($booking->status !== 'pending') {
            throw ValidationException::withMessages([
                'booking' => 'Hanya booking berstatus pending yang dapat diubah.',
            ]);
        }
    }

    private function ensureStatus(Booking $booking, array $allowed, string $action): void
    {
        if (! in_array($booking->status, $allowed, true)) {
            $allowedLabel = implode(' atau ', $allowed);
            throw ValidationException::withMessages([
                'booking' => "Booking harus berstatus {$allowedLabel} untuk dapat {$action}.",
            ]);
        }
    }

    private function ensureOwner(Booking $booking, User $user): void
    {
        if ($booking->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke booking ini.');
        }
    }
}