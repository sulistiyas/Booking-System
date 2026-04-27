<?php

namespace App\Services;

use App\Models\Resource;
use App\Repositories\BookingItemRepository;
use App\Repositories\ResourceRepository;
use Illuminate\Validation\ValidationException;

class BookingConflictChecker
{
    public function __construct(
        protected BookingItemRepository $bookingItemRepository,
        protected ResourceRepository    $resourceRepository,
    ) {}

    /**
     * Cek konflik untuk semua item dalam satu booking.
     *
     * @param  array  $items  [ ['resource_id', 'start_datetime', 'end_datetime'], ... ]
     * @param  int|null  $excludeBookingId  Untuk kasus update (exclude booking sendiri)
     *
     * @throws ValidationException jika ada konflik
     */
    public function check(array $items, ?int $excludeBookingId = null): void
    {
        $errors = [];

        foreach ($items as $index => $item) {
            $resource = $this->resourceRepository->findById($item['resource_id'], ['resourceType']);

            if (! $resource) {
                $errors["items.{$index}.resource_id"] = "Resource tidak ditemukan.";
                continue;
            }

            if (! $resource->is_active) {
                $errors["items.{$index}.resource_id"] = "Resource \"{$resource->name}\" sedang tidak aktif.";
                continue;
            }

            // Skip conflict check jika resource type allow_overlap
            if ($resource->resourceType->allow_overlap) {
                continue;
            }

            $hasConflict = $this->bookingItemRepository->hasConflict(
                resourceId:      $resource->id,
                startDatetime:   $item['start_datetime'],
                endDatetime:     $item['end_datetime'],
                excludeBookingId: $excludeBookingId,
            );

            if ($hasConflict) {
                $conflicts = $this->bookingItemRepository->getConflicts(
                    resourceId:      $resource->id,
                    startDatetime:   $item['start_datetime'],
                    endDatetime:     $item['end_datetime'],
                    excludeBookingId: $excludeBookingId,
                );

                $conflictNumbers = $conflicts
                    ->pluck('booking.booking_number')
                    ->unique()
                    ->join(', ');

                $errors["items.{$index}.start_datetime"] =
                    "Resource \"{$resource->name}\" sudah dibooking pada waktu tersebut. " .
                    "Konflik dengan: {$conflictNumbers}.";
            }
        }

        if (! empty($errors)) {
            throw ValidationException::withMessages($errors);
        }
    }

    /**
     * Cek apakah resource tersedia pada rentang waktu tertentu.
     * Digunakan untuk API availability check (AJAX).
     */
    public function isAvailable(
        int $resourceId,
        string $startDatetime,
        string $endDatetime,
        ?int $excludeBookingId = null,
    ): bool {
        $resource = $this->resourceRepository->findById($resourceId, ['resourceType']);

        if (! $resource || ! $resource->is_active) {
            return false;
        }

        if ($resource->resourceType->allow_overlap) {
            return true;
        }

        return ! $this->bookingItemRepository->hasConflict(
            $resourceId, $startDatetime, $endDatetime, $excludeBookingId
        );
    }
}