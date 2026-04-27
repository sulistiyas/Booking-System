<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'Dashboard') — Booking System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    {{-- Favicon --}}
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'><rect width='32' height='32' rx='6' fill='%23F59E0B'/><path d='M8 22V12l8-4 8 4v10l-8 4-8-4z' fill='none' stroke='white' stroke-width='1.5'/></svg>" />
    @vite(['resources/js/app.js'])
    {{-- CSS --}}
    <link rel="stylesheet" href="{{ asset('css/main.css') }}" />
</head>
<body>

<div class="shell">

  <!-- =========== SIDEBAR =========== -->
  @include('components.sidebar')

  <!-- =========== MAIN =========== -->
  <div class="main">

    <!-- TOPBAR -->
    @include('components.navbar')

    <!-- CONTENT -->
    @yield('content')
    
  </div>
</div>

</body>
</html>