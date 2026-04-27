@extends('layouts.app')

@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')
<main class="content">

    {{-- ── STAT CARDS ────────────────────────────────────────────── --}}
    <div class="stats-grid">

        {{-- Total Users --}}
        <div class="stat-card blue">
            <div class="stat-header">
                <div class="stat-icon blue">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </div>
                <div class="stat-trend up">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="7" y1="17" x2="17" y2="7"/><polyline points="7 7 17 7 17 17"/>
                    </svg>
                    12%
                </div>
            </div>
            <div class="stat-value">{{ number_format($stats['users']['total'] ?? 0) }}</div>
            <div class="stat-label">Total Users</div>
            <div class="stat-sub">↑ {{ $stats['users']['new_this_month'] ?? 0 }} users baru bulan ini</div>
            <div class="sparkline">
                <div class="sparkline-bar" style="height:40%;background:#BFDBFE"></div>
                <div class="sparkline-bar" style="height:55%;background:#93C5FD"></div>
                <div class="sparkline-bar" style="height:45%;background:#BFDBFE"></div>
                <div class="sparkline-bar" style="height:70%;background:#60A5FA"></div>
                <div class="sparkline-bar" style="height:60%;background:#93C5FD"></div>
                <div class="sparkline-bar" style="height:80%;background:#3B82F6"></div>
                <div class="sparkline-bar" style="height:100%;background:#2563EB"></div>
            </div>
        </div>

        {{-- Total Bookings --}}
        <div class="stat-card green">
            <div class="stat-header">
                <div class="stat-icon green">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2"/>
                        <path d="M16 2v4M8 2v4M3 10h18"/>
                    </svg>
                </div>
                <div class="stat-trend up">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="7" y1="17" x2="17" y2="7"/><polyline points="7 7 17 7 17 17"/>
                    </svg>
                    8%
                </div>
            </div>
            <div class="stat-value">{{ number_format($stats['bookings']['total'] ?? 0) }}</div>
            <div class="stat-label">Total Bookings</div>
            <div class="stat-sub">↑ {{ $stats['bookings']['new_this_month'] ?? 0 }} booking bulan ini</div>
            <div class="sparkline">
                <div class="sparkline-bar" style="height:50%;background:#A7F3D0"></div>
                <div class="sparkline-bar" style="height:65%;background:#6EE7B7"></div>
                <div class="sparkline-bar" style="height:75%;background:#34D399"></div>
                <div class="sparkline-bar" style="height:60%;background:#6EE7B7"></div>
                <div class="sparkline-bar" style="height:85%;background:#10B981"></div>
                <div class="sparkline-bar" style="height:70%;background:#34D399"></div>
                <div class="sparkline-bar" style="height:100%;background:#059669"></div>
            </div>
        </div>

        {{-- Approved / Active --}}
        <div class="stat-card yellow">
            <div class="stat-header">
                <div class="stat-icon yellow">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                    </svg>
                </div>
                <div class="stat-trend flat">— 0%</div>
            </div>
            <div class="stat-value">{{ number_format($stats['bookings']['approved'] ?? 0) }}</div>
            <div class="stat-label">Active Bookings</div>
            <div class="stat-sub">Disetujui &amp; sedang berjalan</div>
            <div class="sparkline">
                <div class="sparkline-bar" style="height:80%;background:#FDE68A"></div>
                <div class="sparkline-bar" style="height:70%;background:#FCD34D"></div>
                <div class="sparkline-bar" style="height:90%;background:#FBBF24"></div>
                <div class="sparkline-bar" style="height:75%;background:#F59E0B"></div>
                <div class="sparkline-bar" style="height:85%;background:#FBBF24"></div>
                <div class="sparkline-bar" style="height:70%;background:#FCD34D"></div>
                <div class="sparkline-bar" style="height:80%;background:#F59E0B"></div>
            </div>
        </div>

        {{-- Pending --}}
        <div class="stat-card red">
            <div class="stat-header">
                <div class="stat-icon red">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                        <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
                    </svg>
                </div>
                <div class="stat-trend down">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="7" y1="7" x2="17" y2="17"/><polyline points="17 7 17 17 7 17"/>
                    </svg>
                    3%
                </div>
            </div>
            <div class="stat-value">{{ number_format($stats['bookings']['pending'] ?? 0) }}</div>
            <div class="stat-label">Pending</div>
            <div class="stat-sub">Perlu tindakan segera</div>
            <div class="sparkline">
                <div class="sparkline-bar" style="height:60%;background:#FECACA"></div>
                <div class="sparkline-bar" style="height:80%;background:#FCA5A5"></div>
                <div class="sparkline-bar" style="height:50%;background:#FECACA"></div>
                <div class="sparkline-bar" style="height:90%;background:#F87171"></div>
                <div class="sparkline-bar" style="height:70%;background:#EF4444"></div>
                <div class="sparkline-bar" style="height:55%;background:#FCA5A5"></div>
                <div class="sparkline-bar" style="height:65%;background:#EF4444"></div>
            </div>
        </div>

    </div>

    {{-- ── CONTENT GRID ────────────────────────────────────────────── --}}
    <div class="content-grid">

        {{-- Recent Bookings Table --}}
        <div class="card">
            <div class="card-header">
                <div>
                    <div class="card-title">Booking Terbaru</div>
                    <div class="card-subtitle">10 booking terakhir yang masuk</div>
                </div>
                <a class="card-action" href="{{ route('bookings.index') }}">Lihat semua →</a>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>No. Booking</th>
                            <th>User</th>
                            <th>Keperluan</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentBookings as $booking)
                        <tr>
                            <td>
                                <a href="{{ route('bookings.show', $booking) }}"
                                   class="booking-number-link">
                                    {{ $booking->booking_number }}
                                </a>
                            </td>
                            <td>
                                <div class="user-cell">
                                    <div class="user-avatar ua-{{ ['blue','green','violet','orange','pink'][crc32($booking->user->name) % 5] }}">
                                        {{ strtoupper(substr($booking->user->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="user-name">{{ $booking->user->name }}</div>
                                        <div class="user-email">{{ $booking->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="action-text">{{ Str::limit($booking->title, 32) }}</div>
                                <div class="action-sub">
                                    {{ $booking->start_datetime->format('d M Y') }}
                                    · {{ $booking->items->count() }} item
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-{{ booking_status_color($booking->status) }}">
                                    {{ booking_status_label($booking->status) }}
                                </span>
                            </td>
                            <td>
                                <span class="date-text">
                                    {{ $booking->created_at->diffForHumans() }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="table-empty">
                                <div class="empty-state-sm">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <rect x="3" y="4" width="18" height="18" rx="2"/>
                                        <path d="M16 2v4M8 2v4M3 10h18"/>
                                    </svg>
                                    <p>Belum ada booking</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Right Column --}}
        <div class="dashboard-right">

            {{-- Booking by Status --}}
            <div class="card">
                <div class="card-header">
                    <div>
                        <div class="card-title">Status Booking</div>
                        <div class="card-subtitle">Distribusi dari total {{ number_format($stats['bookings']['total'] ?? 0) }}</div>
                    </div>
                </div>

                <div class="donut-wrap">
                    @php
                        $total      = max(1, $stats['bookings']['total'] ?? 0);
                        $approved   = $stats['bookings']['approved']  ?? 0;
                        $pending    = $stats['bookings']['pending']   ?? 0;
                        $completed  = $stats['bookings']['completed'] ?? 0;
                        $rejected   = $stats['bookings']['rejected']  ?? 0;

                        $approvedDeg  = round(($approved  / $total) * 360);
                        $pendingDeg   = round(($pending   / $total) * 360);
                        $completedDeg = round(($completed / $total) * 360);
                        $rejectedDeg  = 360 - $approvedDeg - $pendingDeg - $completedDeg;

                        $d1 = $approvedDeg;
                        $d2 = $d1 + $pendingDeg;
                        $d3 = $d2 + $completedDeg;
                    @endphp
                    <div class="donut" style="background: conic-gradient(
                        #10B981 0deg {{ $d1 }}deg,
                        #F59E0B {{ $d1 }}deg {{ $d2 }}deg,
                        #3B82F6 {{ $d2 }}deg {{ $d3 }}deg,
                        #EF4444 {{ $d3 }}deg 360deg
                    )">
                        <div class="donut-center">
                            <div class="donut-total">{{ number_format($total) }}</div>
                            <div class="donut-label">Total</div>
                        </div>
                    </div>
                </div>

                <div class="role-dist">
                    @foreach([
                        ['label' => 'Disetujui',  'color' => '#10B981', 'count' => $stats['bookings']['approved']  ?? 0],
                        ['label' => 'Menunggu',   'color' => '#F59E0B', 'count' => $stats['bookings']['pending']   ?? 0],
                        ['label' => 'Selesai',    'color' => '#3B82F6', 'count' => $stats['bookings']['completed'] ?? 0],
                        ['label' => 'Ditolak',    'color' => '#EF4444', 'count' => $stats['bookings']['rejected']  ?? 0],
                    ] as $item)
                    @php $pct = $total > 0 ? round(($item['count'] / $total) * 100) : 0; @endphp
                    <div class="role-item">
                        <div class="role-dot" style="background:{{ $item['color'] }}"></div>
                        <div class="role-info">
                            <div class="role-name">
                                {{ $item['label'] }}
                                <span class="role-count">{{ number_format($item['count']) }} · {{ $pct }}%</span>
                            </div>
                            <div class="role-bar">
                                <div class="role-bar-fill" style="width:{{ $pct }}%;background:{{ $item['color'] }}"></div>
                            </div>
                        </div>
                    </div>
                    @endforeach

                    <div style="height:1px;background:var(--border);margin:16px 0"></div>

                    {{-- Quick links --}}
                    @if(in_array(auth()->user()?->role?->name, ['admin','staff']))
                    <a href="{{ route('bookings.index', ['status' => 'pending']) }}"
                       class="dashboard-quick-link">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                        </svg>
                        Tinjau booking pending
                        <span class="ql-badge">{{ $stats['bookings']['pending'] ?? 0 }}</span>
                    </a>
                    @endif

                    <a href="{{ route('bookings.create') }}" class="dashboard-quick-link primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="8" x2="12" y2="16"/>
                            <line x1="8" y1="12" x2="16" y2="12"/>
                        </svg>
                        Buat booking baru
                    </a>
                </div>
            </div>

            {{-- Upcoming Bookings --}}
            @if(isset($upcomingBookings) && $upcomingBookings->isNotEmpty())
            <div class="card" style="margin-top:16px">
                <div class="card-header">
                    <div>
                        <div class="card-title">Akan Datang</div>
                        <div class="card-subtitle">Booking yang segera dimulai</div>
                    </div>
                </div>
                <div style="padding:8px 0">
                    @foreach($upcomingBookings as $b)
                    <a href="{{ route('bookings.show', $b) }}" class="upcoming-item">
                        <div class="upcoming-date">
                            <span class="upcoming-day">{{ $b->start_datetime->format('d') }}</span>
                            <span class="upcoming-mon">{{ $b->start_datetime->format('M') }}</span>
                        </div>
                        <div class="upcoming-info">
                            <div class="upcoming-title">{{ Str::limit($b->title, 28) }}</div>
                            <div class="upcoming-meta">
                                {{ $b->start_datetime->format('H:i') }} — {{ $b->user->name }}
                            </div>
                        </div>
                        <span class="badge badge-{{ booking_status_color($b->status) }}">
                            {{ booking_status_label($b->status) }}
                        </span>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

        </div>
    </div>

</main>
@endsection