<aside class="sidebar">

    {{-- Logo --}}
    <div class="sidebar-logo">
        <div class="sidebar-logo-icon">
            <svg viewBox="0 0 24 24" fill="white">
                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
            </svg>
        </div>
        <div class="sidebar-logo-text">
            <div class="sidebar-logo-name">BookingMS</div>
            <div class="sidebar-logo-sub">Management System</div>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="sidebar-nav">

        {{-- MAIN --}}
        <div class="nav-section-label">Main</div>

        <a class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}"
           href="{{ route('dashboard') }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="3" width="7" height="9"/><rect x="14" y="3" width="7" height="5"/>
                <rect x="14" y="12" width="7" height="9"/><rect x="3" y="16" width="7" height="5"/>
            </svg>
            Dashboard
        </a>

        {{-- User Management — admin only --}}
        @if(auth()->user()?->role?->name === 'admin')
        <a class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}"
           href="{{ route('users.index') }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
            User Management
            @php $pendingUsers = 0; @endphp
            @if($pendingUsers > 0)
                <span class="nav-badge">{{ $pendingUsers }}</span>
            @endif
        </a>
        @endif

        {{-- FEATURES --}}
        <div class="nav-section-label" style="margin-top:8px">Features</div>

        <a class="nav-item {{ request()->routeIs('bookings.*') ? 'active' : '' }}"
           href="{{ route('bookings.index') }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="4" width="18" height="18" rx="2"/>
                <path d="M16 2v4M8 2v4M3 10h18"/>
            </svg>
            Booking
            {{-- Badge pending hanya untuk admin/staff --}}
            @if(isset($pendingBookingsCount) && $pendingBookingsCount > 0)
                <span class="nav-badge">{{ $pendingBookingsCount }}</span>
            @endif
        </a>

        {{-- Resources — admin & staff --}}
        @if(in_array(auth()->user()?->role?->name, ['admin', 'staff']))
        <a class="nav-item {{ request()->routeIs('resources.*') ? 'active' : '' }}"
           href="#">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
            </svg>
            Resources
        </a>
        @endif

        <a class="nav-item {{ request()->routeIs('reports.*') ? 'active' : '' }}"
           href="#">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <polyline points="14 2 14 8 20 8"/>
                <line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>
            </svg>
            Reports
        </a>

        {{-- Settings — admin only --}}
        @if(auth()->user()?->role?->name === 'admin')
        <a class="nav-item {{ request()->routeIs('settings.*') ? 'active' : '' }}"
           href="#">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="3"/>
                <path d="M19.07 4.93a10 10 0 0 1 0 14.14M4.93 4.93a10 10 0 0 0 0 14.14"/>
            </svg>
            Settings
        </a>
        @endif

    </nav>

    {{-- Footer / User --}}
    <div class="sidebar-footer">
        <div class="sidebar-user" x-data="{ open: false }" @click.outside="open = false">
            <div class="avatar" @click="open = !open" style="cursor:pointer">
                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
            </div>
            <div class="sidebar-user-info">
                <div class="sidebar-user-name">{{ auth()->user()->name ?? 'User' }}</div>
                <div class="sidebar-user-role">{{ auth()->user()->role?->label ?? '-' }}</div>
            </div>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                 stroke="rgba(255,255,255,.4)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 18l6-6-6-6"/>
            </svg>
        </div>
    </div>

</aside>