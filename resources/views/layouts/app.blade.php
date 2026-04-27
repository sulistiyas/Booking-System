<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'Dashboard') — BookingMS</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">

    {{-- Favicon --}}
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'><rect width='32' height='32' rx='8' fill='%233B82F6'/><path d='M8 22V12l8-4 8 4v10l-8 4-8-4z' fill='none' stroke='white' stroke-width='2'/></svg>" />

    {{-- Core CSS -- urutan penting: main dulu, baru tambahan --}}
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

    {{-- Per-page styles --}}
    @stack('styles')

    {{-- Alpine.js via Vite --}}
    @vite(['resources/js/app.js'])
</head>
<body>

{{-- ── FLASH DATA (dibaca oleh Alpine flash component) ─────────────── --}}
<script id="flash-data" data-flash="{{ json_encode(array_filter([
    session('success') ? ['type' => 'success', 'text' => session('success')] : null,
    session('error')   ? ['type' => 'error',   'text' => session('error')]   : null,
    session('warning') ? ['type' => 'warning', 'text' => session('warning')] : null,
    session('info')    ? ['type' => 'info',    'text' => session('info')]    : null,
])) }}"></script>

{{-- ── FLASH NOTIFICATIONS ──────────────────────────────────────────── --}}
<div class="flash-wrap" x-data="flash" x-init="init()">
    <template x-for="msg in messages" :key="msg.id">
        <div class="flash" :class="msg.type"
             x-show="true"
             x-transition:enter="flash-enter"
             x-transition:enter-start="flash-enter-start"
             x-transition:enter-end="flash-enter-end"
             x-transition:leave="flash-leave"
             x-transition:leave-start="flash-leave-start"
             x-transition:leave-end="flash-leave-end">
            <span x-html="icon(msg.type)"></span>
            <span x-text="msg.text"></span>
            <button class="flash-close" @click="remove(msg.id)" aria-label="Tutup">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
    </template>
</div>

{{-- ── APP SHELL ─────────────────────────────────────────────────────── --}}
<div class="shell">

    {{-- SIDEBAR --}}
    @include('components.sidebar')
    

    {{-- MAIN --}}
    <div class="main">

        {{-- TOPBAR / NAVBAR --}}
        @include('components.navbar')

        {{-- PAGE CONTENT --}}
        @yield('content')

    </div>
</div>

{{-- ── PER-PAGE SCRIPTS ──────────────────────────────────────────────── --}}
@stack('scripts')

</body>
</html>