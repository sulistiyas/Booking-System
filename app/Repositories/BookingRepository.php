<?php

namespace App\Repositories;

use App\Models\Booking;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class BookingRepository
{
    public function __construct(protected Booking $model) {}

    // ── Read ──────────────────────────────────────────────────────────────

    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->model
            ->with(['user', 'items.resource.resourceType'])
            ->when(isset($filters['search']), function ($q) use ($filters) {
                $q->where(function ($q) use ($filters) {
                    $q->where('booking_number', 'like', "%{$filters['search']}%")
                      ->orWhere('title', 'like', "%{$filters['search']}%")
                      ->orWhereHas('user', fn($q) => $q->where('name', 'like', "%{$filters['search']}%"));
                });
            })
            ->when(isset($filters['status']),           fn($q) => $q->where('status', $filters['status']))
            ->when(isset($filters['payment_status']),   fn($q) => $q->where('payment_status', $filters['payment_status']))
            ->when(isset($filters['user_id']),          fn($q) => $q->where('user_id', $filters['user_id']))
            ->when(isset($filters['resource_type_id']), fn($q) =>
                $q->whereHas('items.resource', fn($q) => $q->where('resource_type_id', $filters['resource_type_id']))
            )
            ->when(isset($filters['date_from']), fn($q) => $q->whereDate('start_datetime', '>=', $filters['date_from']))
            ->when(isset($filters['date_to']),   fn($q) => $q->whereDate('end_datetime',   '<=', $filters['date_to']))
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function findById(int $id, array $relations = []): ?Booking
    {
        return $this->model->with($relations)->find($id);
    }

    public function findByNumber(string $number): ?Booking
    {
        return $this->model
            ->with(['user', 'items.resource.resourceType'])
            ->where('booking_number', $number)
            ->first();
    }

    public function getByUser(int $userId, array $filters = []): LengthAwarePaginator
    {
        return $this->model
            ->with(['items.resource.resourceType'])
            ->where('user_id', $userId)
            ->when(isset($filters['status']), fn($q) => $q->where('status', $filters['status']))
            ->latest()
            ->paginate(10)
            ->withQueryString();
    }

    public function getLastBookingNumber(): ?string
    {
        return $this->model
            ->withTrashed()
            ->whereYear('created_at', now()->year)
            ->latest('id')
            ->value('booking_number');
    }

    public function countByStatus(): Collection
    {
        return $this->model
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->get();
    }

    public function getUpcoming(int $limit = 5): Collection
    {
        return $this->model
            ->with(['user', 'items.resource'])
            ->whereIn('status', ['pending', 'approved'])
            ->where('start_datetime', '>=', now())
            ->orderBy('start_datetime')
            ->limit($limit)
            ->get();
    }

    // ── Write ─────────────────────────────────────────────────────────────

    public function create(array $data): Booking
    {
        return $this->model->create($data);
    }

    public function update(Booking $booking, array $data): bool
    {
        return $booking->update($data);
    }

    public function delete(Booking $booking): bool
    {
        return $booking->delete();
    }
}