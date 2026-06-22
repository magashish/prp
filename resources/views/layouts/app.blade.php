<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Parking Stall Rental')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark" style="background:#082B4D">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}"> <img src="{{ asset('/images/prp.png') }}"> </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMain">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="https://blparkingrentals.com/">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Book Now</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('cancellation.index') }}">Cancel Booking</a></li>
                <li class="nav-item"><a class="nav-link" href="https://blparkingrentals.com/contact/">Contact</a></li>
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
        <a href="https://blparkingrentals.com/terms-condition/" class="text-light small me-3">Terms &amp; Conditions</a>
        <a href="https://blparkingrentals.com/contact/" class="text-light small">Contact Us</a>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
