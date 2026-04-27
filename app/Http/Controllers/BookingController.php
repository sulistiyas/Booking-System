<?php

namespace App\Http\Controllers;

use App\Http\Requests\Booking\ApproveBookingRequest;
use App\Http\Requests\Booking\CancelBookingRequest;
use App\Http\Requests\Booking\RejectBookingRequest;
use App\Http\Requests\Booking\StoreBookingRequest;
use App\Http\Requests\Booking\UpdateBookingRequest;
use App\Models\Booking;
use App\Repositories\ResourceRepository;
use App\Services\BookingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function __construct(
        protected BookingService    $bookingService,
        protected ResourceRepository $resourceRepository,
    ) {}

    // ── CRUD ──────────────────────────────────────────────────────────────

    public function index(Request $request): View
    {
        $user = Auth::user();

        // Admin/staff lihat semua, user hanya miliknya
        if (in_array($user->role->name, ['admin', 'staff'])) {
            $bookings = $this->bookingService->paginate(
                filters: $request->only(['search', 'status', 'payment_status', 'resource_type_id', 'date_from', 'date_to']),
            );
        } else {
            $bookings = $this->bookingService->paginateForUser(
                user:    $user,
                filters: $request->only(['status']),
            );
        }

        $stats = $this->bookingService->getStats();

        return view('bookings.index', compact('bookings', 'stats'));
    }

    public function create(): View
    {
        $resources = $this->resourceRepository->allActive();

        return view('bookings.create', compact('resources'));
    }

    public function store(StoreBookingRequest $request): RedirectResponse
    {
        $booking = $this->bookingService->create(
            data: $request->validated(),
            user: Auth::user(),
        );

        return redirect()
            ->route('bookings.show', $booking)
            ->with('success', "Booking {$booking->booking_number} berhasil dibuat dan menunggu persetujuan.");
    }

    public function show(Booking $booking): View
    {
        $this->authorizeView($booking);

        $booking = $this->bookingService->findOrFail($booking->id);

        return view('bookings.show', compact('booking'));
    }

    public function edit(Booking $booking): View
    {
        $this->authorizeEdit($booking);

        $booking->load('items.resource.resourceType');
        $resources = $this->resourceRepository->allActive();

        return view('bookings.edit', compact('booking', 'resources'));
    }

    public function update(UpdateBookingRequest $request, Booking $booking): RedirectResponse
    {
        $this->bookingService->update($booking, $request->validated());

        return redirect()
            ->route('bookings.show', $booking)
            ->with('success', 'Booking berhasil diperbarui.');
    }

    public function destroy(Booking $booking): RedirectResponse
    {
        // Hanya admin yang bisa hard-delete booking cancelled/rejected
        // $this->authorize('delete', $booking); // gunakan policy jika ada

        $this->bookingService->findOrFail($booking->id); // ensure exists
        $booking->delete();

        return redirect()
            ->route('bookings.index')
            ->with('success', 'Booking berhasil dihapus.');
    }

    // ── Status Actions ────────────────────────────────────────────────────

    public function approve(ApproveBookingRequest $request, Booking $booking): RedirectResponse
    {
        $this->bookingService->approve($booking, $request->input('reason'));

        return back()->with('success', "Booking {$booking->booking_number} berhasil disetujui.");
    }

    public function reject(RejectBookingRequest $request, Booking $booking): RedirectResponse
    {
        $this->bookingService->reject($booking, $request->input('reason'));

        return back()->with('success', "Booking {$booking->booking_number} berhasil ditolak.");
    }

    public function cancel(CancelBookingRequest $request, Booking $booking): RedirectResponse
    {
        $this->bookingService->cancel($booking, $request->input('reason'));

        return back()->with('success', "Booking {$booking->booking_number} berhasil dibatalkan.");
    }

    public function complete(Booking $booking): RedirectResponse
    {
        $this->ensureAdminOrStaff();

        $this->bookingService->complete($booking);

        return back()->with('success', "Booking {$booking->booking_number} ditandai selesai.");
    }

    // ── AJAX ──────────────────────────────────────────────────────────────

    /**
     * Check ketersediaan resource (dipanggil dari Alpine.js)
     * GET /bookings/check-availability?resource_id=1&start=...&end=...
     */
    public function checkAvailability(Request $request): JsonResponse
    {
        $request->validate([
            'resource_id' => ['required', 'integer', 'exists:resources,id'],
            'start'       => ['required', 'date'],
            'end'         => ['required', 'date', 'after:start'],
            'exclude_booking_id' => ['nullable', 'integer'],
        ]);

        $result = $this->bookingService->checkAvailability(
            resourceId:       $request->integer('resource_id'),
            start:            $request->input('start'),
            end:              $request->input('end'),
            excludeBookingId: $request->input('exclude_booking_id'),
        );

        return response()->json($result);
    }

    // ── Private Helpers ───────────────────────────────────────────────────

    private function authorizeView(Booking $booking): void
    {
        $user = Auth::user();

        if ($user->role->name === 'user' && $booking->user_id !== $user->id) {
            abort(403);
        }
    }

    private function authorizeEdit(Booking $booking): void
    {
        $user = Auth::user();

        if ($user->role->name === 'user' && $booking->user_id !== $user->id) {
            abort(403);
        }

        if ($booking->status !== 'pending') {
            abort(403, 'Hanya booking berstatus pending yang dapat diubah.');
        }
    }

    private function ensureAdminOrStaff(): void
    {
        if (! in_array(Auth::user()->role->name, ['admin', 'staff'])) {
            abort(403);
        }
    }
}