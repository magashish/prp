<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin – @yield('title', 'Dashboard') | Parking Stall Rental</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body>
<div class="sidebar d-flex flex-column">
    <div class="brand"><i class="bi bi-p-square-fill me-2"></i>Parking Admin</div>
    <nav class="nav flex-column mt-2">
        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
        <a href="{{ route('admin.bookings.index') }}" class="nav-link {{ request()->routeIs('admin.bookings.*') && !request()->routeIs('admin.bookings.past') ? 'active' : '' }}"><i class="bi bi-table me-2"></i>Current Bookings</a>
        <a href="{{ route('admin.bookings.past') }}" class="nav-link {{ request()->routeIs('admin.bookings.past') ? 'active' : '' }}"><i class="bi bi-archive me-2"></i>Past Bookings</a>
        <a href="{{ route('admin.calendar') }}" class="nav-link {{ request()->routeIs('admin.calendar') ? 'active' : '' }}"><i class="bi bi-calendar3 me-2"></i>Calendar View</a>
    </nav>
    <div class="mt-auto p-3">
        <form action="{{ route('admin.logout') }}" method="POST">
            @csrf
            <button class="btn btn-outline-light btn-sm w-100"><i class="bi bi-box-arrow-left me-1"></i>Logout</button>
        </form>
    </div>
</div>

<div class="main-content">
    <div class="topbar">
        <h5 class="mb-0">@yield('title', 'Dashboard')</h5>
        <span class="text-muted small"><i class="bi bi-person-circle me-1"></i>{{ session('admin_name', 'Admin') }}</span>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
