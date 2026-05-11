<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Parking Stall Rental')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .navbar-brand { font-weight: 700; }
        .hero-section { background: linear-gradient(135deg, #1a3c5e 0%, #2d6a9f 100%); color: #fff; padding: 60px 0; }
        .price-badge { background: #0d6efd; color: #fff; border-radius: 8px; padding: 4px 10px; font-size: .85rem; }
        footer { background: #1a3c5e; color: #cdd8e3; }
        .form-label { font-weight: 500; }
        .booking-summary { background: #e9f3ff; border-left: 4px solid #0d6efd; border-radius: 4px; padding: 16px; }
        .step-indicator .step { width: 32px; height: 32px; border-radius: 50%; display:inline-flex; align-items:center; justify-content:center; font-weight:700; }
        .step-indicator .step.active { background:#0d6efd; color:#fff; }
        .step-indicator .step.done  { background:#198754; color:#fff; }
        .step-indicator .step.todo  { background:#dee2e6; color:#6c757d; }
    </style>
    @stack('styles')
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark" style="background:#1a3c5e">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}"><i class="bi bi-p-square-fill me-2"></i>Parking Stall Rental</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMain">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Book Now</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('cancellation.index') }}">Cancel Booking</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('contact') }}">Contact</a></li>
            </ul>
        </div>
    </div>
</nav>

@if(session('success'))
    <div class="container mt-3"><div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div></div>
@endif
@if(session('error'))
    <div class="container mt-3"><div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div></div>
@endif
@if(session('warning'))
    <div class="container mt-3"><div class="alert alert-warning alert-dismissible fade show">{{ session('warning') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div></div>
@endif

@yield('content')

<footer class="mt-5 py-4">
    <div class="container text-center">
        <p class="mb-1">&copy; {{ date('Y') }} Parking Stall Rental. All rights reserved.</p>
        <a href="{{ route('terms') }}" class="text-light small me-3">Terms &amp; Conditions</a>
        <a href="{{ route('contact') }}" class="text-light small">Contact Us</a>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
