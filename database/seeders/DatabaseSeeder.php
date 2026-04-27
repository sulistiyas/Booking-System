<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. Roles ──────────────────────────────────────────────────────
        $roles = [
            ['name' => 'admin', 'label' => 'Administrator',
             'description' => 'Akses penuh ke semua fitur sistem'],
            ['name' => 'staff', 'label' => 'Staff',
             'description' => 'Dapat mengelola booking dan resource'],
            ['name' => 'user',  'label' => 'User',
             'description' => 'Dapat membuat dan melihat booking sendiri'],
        ];
        foreach ($roles as $role) {
            DB::table('roles')->insertOrIgnore([...$role, 'created_at' => now(), 'updated_at' => now()]);
        }

        $adminRoleId = DB::table('roles')->where('name', 'admin')->value('id');
        $staffRoleId = DB::table('roles')->where('name', 'staff')->value('id');
        $userRoleId  = DB::table('roles')->where('name', 'user')->value('id');

        // ── 2. Default Users ───────────────────────────────────────────────
        $users = [
            ['role_id' => $adminRoleId, 'name' => 'Administrator',
             'email' => 'admin@example.com', 'password' => Hash::make('password')],
            ['role_id' => $staffRoleId, 'name' => 'Staff User',
             'email' => 'staff@example.com', 'password' => Hash::make('password')],
            ['role_id' => $userRoleId, 'name' => 'Regular User',
             'email' => 'user@example.com',  'password' => Hash::make('password')],
        ];
        foreach ($users as $user) {
            DB::table('users')->insertOrIgnore([...$user,
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ── 3. Resource Types ──────────────────────────────────────────────
        $resourceTypes = [
            ['name' => 'room',      'label' => 'Ruangan',    'icon' => 'building-office',
             'color' => 'blue',    'requires_approval' => true,  'allow_overlap' => false, 'sort_order' => 1,
             'description' => 'Peminjaman ruangan, aula, kelas, atau meeting room'],
            ['name' => 'equipment', 'label' => 'Peralatan',  'icon' => 'cpu-chip',
             'color' => 'purple',  'requires_approval' => true,  'allow_overlap' => false, 'sort_order' => 2,
             'description' => 'Peminjaman alat elektronik, perlengkapan, dan perangkat'],
            ['name' => 'schedule',  'label' => 'Jadwal',     'icon' => 'calendar-days',
             'color' => 'green',   'requires_approval' => false, 'allow_overlap' => false, 'sort_order' => 3,
             'description' => 'Booking slot jadwal, sesi konsultasi, atau pelatihan'],
            ['name' => 'service',   'label' => 'Layanan',    'icon' => 'sparkles',
             'color' => 'orange',  'requires_approval' => false, 'allow_overlap' => true,  'sort_order' => 4,
             'description' => 'Pemesanan layanan seperti catering, fotografi, atau jasa lainnya'],
        ];
        foreach ($resourceTypes as $type) {
            DB::table('resource_types')->insertOrIgnore([...$type,
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ── 4. Settings ───────────────────────────────────────────────────
        $settings = [
            ['key' => 'booking_number_prefix', 'value' => 'BKG', 'type' => 'string',
             'group' => 'booking', 'label' => 'Prefix Nomor Booking', 'is_public' => false],
            ['key' => 'max_booking_days_ahead', 'value' => '90', 'type' => 'integer',
             'group' => 'booking', 'label' => 'Maksimal Hari ke Depan untuk Booking', 'is_public' => true],
            ['key' => 'min_booking_hours_notice', 'value' => '2', 'type' => 'integer',
             'group' => 'booking', 'label' => 'Minimum Jam Sebelum Booking Dimulai', 'is_public' => true],
            ['key' => 'app_name', 'value' => 'BookingMS', 'type' => 'string',
             'group' => 'general', 'label' => 'Nama Aplikasi', 'is_public' => true],
            ['key' => 'app_timezone', 'value' => 'Asia/Jakarta', 'type' => 'string',
             'group' => 'general', 'label' => 'Zona Waktu Aplikasi', 'is_public' => true],
        ];
        foreach ($settings as $setting) {
            DB::table('settings')->insertOrIgnore([...$setting,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}