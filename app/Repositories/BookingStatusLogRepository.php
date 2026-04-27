<?php

namespace App\Repositories;

use App\Models\Booking_Status_Log;
use App\Models\Booking;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class BookingStatusLogRepository
{
    public function __construct(protected Booking_Status_Log $model) {}

    public function log(
        Booking $booking,
        ?string $fromStatus,
        string $toStatus,
        ?string $reason = null,
        ?int $changedBy = null,
    ): Booking_Status_Log {
        return $this->model->create([
            'booking_id'  => $booking->id,
            'changed_by'  => $changedBy ?? Auth::id(),
            'from_status' => $fromStatus,
            'to_status'   => $toStatus,
            'reason'      => $reason,
            'snapshot'    => [
                'booking_number' => $booking->booking_number,
                'title'          => $booking->title,
                'total_price'    => $booking->total_price,
                'start_datetime' => $booking->start_datetime,
                'end_datetime'   => $booking->end_datetime,
            ],
        ]);
    }

    public function getByBooking(int $bookingId): Collection
    {
        return $this->model
            ->with('changedBy:id,name')
            ->where('booking_id', $bookingId)
            ->latest()
            ->get();
    }
}