<?php

namespace App\Services;

use App\Repositories\BookingRepository;

class BookingNumberGenerator
{
    public function __construct(protected BookingRepository $bookingRepository) {}

    /**
     * Generate nomor booking unik.
     * Format: {PREFIX}-{YEAR}-{SEQUENCE}
     * Contoh: BKG-2024-0001, BKG-2024-0042
     *
     * Sequence di-reset setiap tahun.
     */
    public function generate(string $prefix = 'BKG'): string
    {
        $year = now()->year;
        $last = $this->bookingRepository->getLastBookingNumber();

        if ($last) {
            // Ambil sequence terakhir dari nomor yang ada
            $parts    = explode('-', $last);
            $sequence = (int) end($parts);
        } else {
            $sequence = 0;
        }

        $next = str_pad($sequence + 1, 4, '0', STR_PAD_LEFT);

        return "{$prefix}-{$year}-{$next}";
    }
}