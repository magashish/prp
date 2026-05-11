@extends('layouts.app')
@section('title', 'Contact Us')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <h2 class="fw-bold mb-4">Contact Us</h2>
            <div class="card border-0 shadow-sm p-4">
                <p class="text-muted">Have questions about your booking or parking? Reach out to us.</p>
                <ul class="list-unstyled">
                    <li class="mb-2"><i class="bi bi-envelope-fill text-primary me-2"></i><a href="mailto:{{ config('mail.admin_email', 'admin@parkingrental.com') }}">{{ config('mail.admin_email', 'admin@parkingrental.com') }}</a></li>
                    <li class="mb-2"><i class="bi bi-clock-fill text-primary me-2"></i>Monday – Friday, 8:00 AM – 5:00 PM</li>
                </ul>
                <hr>
                <p class="small text-muted mb-0">For cancellations, please use the <a href="{{ route('cancellation.index') }}">Cancel Booking</a> page.</p>
            </div>
        </div>
    </div>
</div>
@endsection
