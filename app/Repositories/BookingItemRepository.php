<?php

namespace App\Repositories;

use App\Models\Booking;
use App\Models\Booking_Item;
use Illuminate\Database\Eloquent\Collection;

class BookingItemRepository
{
    public function __construct(protected Booking_Item $model) {}

    public function createMany(Booking $booking, array $items): void
    {
        $rows = array_map(fn($item) => [
            ...$item,
            'booking_id' => $booking->id,
            'created_at' => now(),
            'updated_at' => now(),
        ], $items);

        $this->model->insert($rows);
    }

    public function deleteByBooking(Booking $booking): void
    {
        $this->model->where('booking_id', $booking->id)->delete();
    }

    /**
     * Cek apakah resource sudah dibooking pada rentang waktu tertentu.
     * Dipakai di conflict check sebelum booking disimpan.
     *
     * Logic overlap: dua range [s1,e1] dan [s2,e2] overlap jika s1 < e2 && e1 > s2
     */
    public function hasConflict(
        int $resourceId,
        string $startDatetime,
        string $endDatetime,
        ?int $excludeBookingId = null,
    ): bool {
        return $this->model
            ->whereHas('booking', fn($q) =>
                $q->whereNotIn('status', ['rejected', 'cancelled'])
                  ->when($excludeBookingId, fn($q) => $q->where('id', '!=', $excludeBookingId))
            )
            ->where('resource_id', $resourceId)
            ->where('start_datetime', '<', $endDatetime)
            ->where('end_datetime',   '>', $startDatetime)
            ->exists();
    }

    public function getConflicts(
        int $resourceId,
        string $startDatetime,
        string $endDatetime,
        ?int $excludeBookingId = null,
    ): Collection {
        return $this->model
            ->with(['booking:id,booking_number,status,user_id', 'booking.user:id,name'])
            ->whereHas('booking', fn($q) =>
                $q->whereNotIn('status', ['rejected', 'cancelled'])
                  ->when($excludeBookingId, fn($q) => $q->where('id', '!=', $excludeBookingId))
            )
            ->where('resource_id', $resourceId)
            ->where('start_datetime', '<', $endDatetime)
            ->where('end_datetime',   '>', $startDatetime)
            ->get();
    }
}