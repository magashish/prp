@extends('layouts.app')
@section('title', 'Booking Confirmed')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card border-0 shadow">
                <div class="card-body text-center p-5">
                    <div class="mb-3" style="font-size:4rem; color:#02A38A;">&#10003;</div>
                    <h2 class="fw-bold text-success">Booking Confirmed!</h2>
                    <p class="text-muted mb-4">Thank you, {{ $booking->full_name }}. Your booking has been confirmed and a confirmation email has been sent to <strong>{{ $booking->email }}</strong>.</p>

                    <div class="booking-summary text-start mb-4">
                        <div class="row g-2 small">
                            <div class="col-6"><strong>Booking ID:</strong></div><div class="col-6">{{ $booking->booking_id }}</div>
                            <div class="col-6"><strong>Stall Type:</strong></div><div class="col-6">{{ ucwords(str_replace('_', ' ', $booking->stall_type)) }}{{ $booking->stall_number ? ' – Stall ' . $booking->stall_number : '' }}</div>
                            <div class="col-6"><strong>Check-in:</strong></div><div class="col-6">{{ $booking->check_in_date->format('M d, Y') }}</div>
                            <div class="col-6"><strong>Check-out:</strong></div><div class="col-6">{{ $booking->check_out_date->format('M d, Y') }}</div>
                            <div class="col-6"><strong>Total Paid:</strong></div><div class="col-6 fw-bold text-success">${{ number_format($booking->total_amount, 2) }}</div>
                            <div class="col-6"><strong>Refund Protection:</strong></div><div class="col-6">{{ $booking->refund_plan ? 'Yes' : 'No' }}</div>
                        </div>
                    </div>

                    @if($booking->stall_type === 'reserved')
                    <div class="alert alert-info text-start shadow-sm" style="border-left: 5px solid #0f4c81;">
                        <i class="bi bi-printer-fill me-2"></i>A <strong>parking printout</strong> has been emailed to you. Please print and place it on your vehicle dashboard.
                    </div>
                    @else
                    <div class="alert alert-danger text-start shadow" style="border: 2px solid #dc3545; border-left: 8px solid #dc3545; font-size: 1.1rem; background-color: #f8d7da;">
                        <i class="bi bi-exclamation-triangle-fill me-2 text-danger"></i><strong>ATTENTION:</strong> Your <strong>parking access code</strong> will be emailed to you shortly once processed. You must present this pass to enter the garage.
                    </div>
                    @endif

                    <div class="mt-4 d-flex justify-content-center gap-2">
                        <a href="{{ route('home') }}" class="btn btn-outline-primary">Make Another Booking</a>
                        <a href="{{ url('/') }}" class="btn btn-primary">Main Page</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
