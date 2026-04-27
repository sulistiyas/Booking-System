<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('TRUNCATE TABLE roles RESTART IDENTITY CASCADE');
        DB::statement('TRUNCATE TABLE users RESTART IDENTITY CASCADE');
        DB::statement('TRUNCATE TABLE resource_types RESTART IDENTITY CASCADE');
        DB::statement('TRUNCATE TABLE resources RESTART IDENTITY CASCADE');
        DB::statement('TRUNCATE TABLE bookings RESTART IDENTITY CASCADE');
        DB::statement('TRUNCATE TABLE booking_items RESTART IDENTITY CASCADE');
        DB::statement('TRUNCATE TABLE booking_status_logs RESTART IDENTITY CASCADE');
        DB::statement('TRUNCATE TABLE notifications RESTART IDENTITY CASCADE');
        DB::statement('TRUNCATE TABLE settings RESTART IDENTITY CASCADE');
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

        // ── Generate Dummy Users ─────────────────────────────────────
        for ($i = 4; $i <= 50; $i++) {
            DB::table('users')->insert([
                'role_id' => $userRoleId,
                'name' => 'User ' . $i,
                'email' => 'user' . $i . '@example.com',
                'password' => Hash::make('password'),
                'is_active' => true,
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

        // ── 5. Resources ──────────────────────────────────────────────
        $roomTypeId = DB::table('resource_types')->where('name', 'room')->value('id');
        $equipmentTypeId = DB::table('resource_types')->where('name', 'equipment')->value('id');

        $resources = [
            [
                'resource_type_id' => $roomTypeId,
                'name' => 'Meeting Room A',
                'code' => 'ROOM-A',
                'location' => 'Lantai 1',
                'capacity' => 10,
                'is_active' => true,
            ],
            [
                'resource_type_id' => $roomTypeId,
                'name' => 'Meeting Room B',
                'code' => 'ROOM-B',
                'location' => 'Lantai 2',
                'capacity' => 20,
                'is_active' => true,
            ],
            [
                'resource_type_id' => $equipmentTypeId,
                'name' => 'Proyektor Epson',
                'code' => 'EQ-PRJ-01',
                'location' => 'Gudang',
                'capacity' => 1,
                'is_active' => true,
            ],
        ];

        foreach ($resources as $res) {
            DB::table('resources')->insertOrIgnore([
                ...$res,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ambil user
        $users = DB::table('users')->pluck('id')->toArray();
        $resources = DB::table('resources')->get();
        $adminId = DB::table('users')->where('email', 'admin@example.com')->value('id');
        $statuses = ['pending', 'approved', 'rejected', 'completed'];
        $paymentStatuses = ['unpaid', 'paid'];

        for ($i = 1; $i <= 200; $i++) {

            $userId = $users[array_rand($users)];
            $status = $statuses[array_rand($statuses)];
            $paymentStatus = $paymentStatuses[array_rand($paymentStatuses)];

            $start = now()->addDays(rand(-10, 20))->setTime(rand(8, 17), 0);
            $end = (clone $start)->addHours(rand(1, 3));

            $bookingNumber = 'BKG-' . str_pad($i, 4, '0', STR_PAD_LEFT);

            DB::table('bookings')->insert([
                'booking_number' => $bookingNumber,
                'user_id' => $userId,
                'title' => 'Booking #' . $i,
                'notes' => 'Auto generated booking',
                'status' => $status,
                'payment_status' => $paymentStatus,
                'start_datetime' => $start,
                'end_datetime' => $end,
                'approved_by' => $status === 'approved' ? $adminId : null,
                'approved_at' => $status === 'approved' ? now() : null,
                'rejected_by' => $status === 'rejected' ? $adminId : null,
                'rejected_at' => $status === 'rejected' ? now() : null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $bookingId = DB::table('bookings')
                ->where('booking_number', $bookingNumber)
                ->value('id');

            // ── ITEMS ─────────────────────────────────────
            $total = 0;

            $itemCount = rand(1, 3);
            for ($j = 0; $j < $itemCount; $j++) {

                $resource = $resources->random();

                $itemStart = (clone $start);
                $itemEnd = (clone $end);

                $price = $resource->price_per_unit ?? rand(50000, 200000);
                $qty = rand(1, 2);
                $subtotal = $price * $qty;

                $total += $subtotal;

                DB::table('booking_items')->insert([
                    'booking_id' => $bookingId,
                    'resource_id' => $resource->id,
                    'start_datetime' => $itemStart,
                    'end_datetime' => $itemEnd,
                    'quantity' => $qty,
                    'unit_price' => $price,
                    'subtotal' => $subtotal,
                    'notes' => 'Auto item',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // ── UPDATE TOTAL ──────────────────────────────
            DB::table('bookings')->where('id', $bookingId)->update([
                'total_price' => $total
            ]);

            // ── STATUS LOG ───────────────────────────────
            DB::table('booking_status_logs')->insert([
                'booking_id' => $bookingId,
                'changed_by' => $adminId,
                'from_status' => 'pending',
                'to_status' => $status,
                'reason' => 'Auto generated',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // ── NOTIFICATION ─────────────────────────────
            DB::table('notifications')->insert([
                'user_id' => $userId,
                'type' => 'booking',
                'title' => 'Booking Update',
                'body' => "Booking {$bookingNumber} is {$status}",
                'action_url' => '/bookings/' . $bookingId,
                'notifiable_type' => 'booking',
                'notifiable_id' => $bookingId,
                'is_read' => rand(0, 1),
                'read_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

    } 
}