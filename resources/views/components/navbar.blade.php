<header class="topbar">
    <div class="topbar-title">
        <h1>@yield('breadcrumb', 'Dashboard')</h1>
        <p>{{ now()->translatedFormat('l, d F Y') }}</p>
    </div>

    <div class="topbar-actions">

        {{-- Date chip --}}
        <div class="topbar-date">
            {{ now()->format('H:i') }} WIB
        </div>

        {{-- Search --}}
        <button class="icon-btn" title="Pencarian">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
        </button>

        {{-- Notifications --}}
        <button class="icon-btn" title="Notifikasi">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/>
            </svg>
            <span class="notif-dot"></span>
        </button>

        {{-- User avatar dropdown --}}
        <div class="topbar-user" x-data="{ open: false }" @click.outside="open = false">
            <div class="topbar-avatar" @click="open = !open">
                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
            </div>

            {{-- Dropdown --}}
            <div class="topbar-dropdown" x-show="open" x-transition:enter="dropdown-enter"
                 x-transition:enter-start="dropdown-enter-start" x-transition:enter-end="dropdown-enter-end"
                 x-cloak>
                <div class="dropdown-header">
                    <div class="dropdown-name">{{ auth()->user()->name ?? 'User' }}</div>
                    <div class="dropdown-role">{{ auth()->user()->role?->label ?? '-' }}</div>
                </div>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                    </svg>
                    Profil Saya
                </a>
                <a class="dropdown-item" href="#">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14M4.93 4.93a10 10 0 0 0 0 14.14"/>
                    </svg>
                    Pengaturan
                </a>
                <div class="dropdown-divider"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item danger">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
                        </svg>
                        Keluar
                    </button>
                </form>
            </div>
        </div>

    </div>
</header>