<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin – @yield('title', 'Dashboard') | Parking Stall Rental</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --bs-primary: #0f4c81;
            --bs-primary-rgb: 15,76,129;
            --bs-success: #02A38A;
            --bs-success-rgb: 2,163,138;
            --bs-link-color: #0f4c81;
            --bs-link-hover-color: #082B4D;
        }
        .btn-primary { background-color:#0f4c81; border-color:#0f4c81; }
        .btn-primary:hover, .btn-primary:focus, .btn-primary:active { background-color:#082B4D !important; border-color:#082B4D !important; }
        .btn-success { background-color:#02A38A; border-color:#02A38A; }
        .btn-success:hover, .btn-success:focus, .btn-success:active { background-color:#018a74 !important; border-color:#018a74 !important; }
        .btn-outline-primary { color:#0f4c81; border-color:#0f4c81; }
        .btn-outline-primary:hover { background-color:#0f4c81; border-color:#0f4c81; color:#fff; }
        .text-primary { color:#0f4c81 !important; }
        .text-success { color:#02A38A !important; }
        .bg-primary { background-color:#0f4c81 !important; }
        .bg-success { background-color:#02A38A !important; }
        .alert-success { background-color:#d1f5ef; border-color:#02A38A; color:#015a4d; }
        .table-primary { background-color:#d6e8f7 !important; }
        .badge.bg-primary { background-color:#0f4c81 !important; }
        .badge.bg-success { background-color:#02A38A !important; }
        a { color:#0f4c81; }
        a:hover { color:#082B4D; }
        body { background:#f4f4f4; }
        .sidebar { width:240px; min-height:100vh; background:#082B4D; position:fixed; top:0; left:0; z-index:100; }
        .sidebar .nav-link { color:#cdd8e3; padding: .6rem 1.2rem; border-radius:6px; margin:2px 8px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background:#0f4c81; color:#fff; }
        .sidebar .brand { padding:20px 16px 10px; color:#fff; font-size:1.1rem; font-weight:700; }
        .main-content { margin-left:240px; padding:24px; }
        .topbar { background:#fff; border-bottom:1px solid #dee2e6; padding:12px 24px; margin:-24px -24px 24px; display:flex; justify-content:space-between; align-items:center; }
        .badge-pass-required { background:#dc3545; }
        .badge-pass-sent { background:#02A38A; }
        .badge-pass-not-required { background:#6c757d; }
    </style>
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
