<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        // ── Stats ──────────────────────────────────────────────────────────
        $bookingQuery = Booking::query();

        // User biasa hanya lihat stats miliknya
        if ($user->role->name === 'user') {
            $bookingQuery->where('user_id', $user->id);
        }

        $bookingStats = $bookingQuery->selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END)  as approved,
            SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END)   as pending,
            SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed,
            SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END)  as rejected,
            SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled
        ")->first();

        $stats = [
            'users' => [
                'total'          => User::count(),
                'new_this_month' => User::whereMonth('created_at', now()->month)
                                        ->whereYear('created_at', now()->year)
                                        ->count(),
            ],
            'bookings' => [
                'total'          => (int) ($bookingStats->total     ?? 0),
                'approved'       => (int) ($bookingStats->approved  ?? 0),
                'pending'        => (int) ($bookingStats->pending   ?? 0),
                'completed'      => (int) ($bookingStats->completed ?? 0),
                'rejected'       => (int) ($bookingStats->rejected  ?? 0),
                'cancelled'      => (int) ($bookingStats->cancelled ?? 0),
                'new_this_month' => Booking::whereMonth('created_at', now()->month)
                                           ->whereYear('created_at', now()->year)
                                           ->when($user->role->name === 'user', fn($q) => $q->where('user_id', $user->id))
                                           ->count(),
            ],
        ];

        // ── Recent Bookings ────────────────────────────────────────────────
        $recentBookings = Booking::with(['user', 'items'])
            ->when($user->role->name === 'user', fn($q) => $q->where('user_id', $user->id))
            ->latest()
            ->limit(8)
            ->get();

        // ── Upcoming Bookings (admin/staff saja) ───────────────────────────
        $upcomingBookings = collect();

        if (in_array($user->role->name, ['admin', 'staff'])) {
            $upcomingBookings = Booking::with(['user'])
                ->whereIn('status', ['pending', 'approved'])
                ->where('start_datetime', '>=', now())
                ->orderBy('start_datetime')
                ->limit(5)
                ->get();
        }

        return view('dashboard.index', compact('stats', 'recentBookings', 'upcomingBookings'));
    }
}