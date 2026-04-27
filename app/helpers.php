<?php

// app/helpers.php
// Daftarkan di composer.json → autoload → files: ["app/helpers.php"]

use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

if (! function_exists('settings')) {
    /**
     * Ambil nilai setting dari database dengan cache.
     *
     * Contoh:
     *   settings('app_name')              → 'BookingMS'
     *   settings('max_booking_days_ahead') → '90'
     *   settings()                         → Collection semua settings
     */
    function settings(?string $key = null, mixed $default = null): mixed
    {
        $all = Cache::remember('app.settings', now()->addHour(), function () {
            return Setting::pluck('value', 'key');
        });

        if ($key === null) {
            return $all;
        }

        return $all->get($key, $default);
    }
}

if (! function_exists('role_is')) {
    /**
     * Cek role user yang sedang login.
     *
     * Contoh:
     *   role_is('admin')           → true/false
     *   role_is('admin', 'staff')  → true jika salah satu match
     */
    function role_is(string ...$roles): bool
    {
        return in_array(Auth::user()?->role?->name, $roles, true);
    }
}

if (! function_exists('booking_status_label')) {
    /**
     * Ambil label Bahasa Indonesia dari status booking.
     */
    function booking_status_label(string $status): string
    {
        return match ($status) {
            'pending'   => 'Menunggu',
            'approved'  => 'Disetujui',
            'rejected'  => 'Ditolak',
            'cancelled' => 'Dibatalkan',
            'completed' => 'Selesai',
            default     => ucfirst($status),
        };
    }
}

if (! function_exists('booking_status_color')) {
    /**
     * Ambil warna CSS class untuk badge status booking.
     */
    function booking_status_color(string $status): string
    {
        return match ($status) {
            'pending'   => 'yellow',
            'approved'  => 'green',
            'rejected'  => 'red',
            'cancelled' => 'gray',
            'completed' => 'blue',
            default     => 'gray',
        };
    }
}