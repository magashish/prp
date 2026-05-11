@extends('layouts.app')
@section('title', 'Confirm Cancellation')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-warning py-3">
                    <h5 class="mb-0"><i class="bi bi-exclamation-triangle me-2"></i>Confirm Cancellation</h5>
                </div>
                <div class="card-body p-4">
                    <div class="booking-summary mb-4">
                        <div class="row g-2 small">
                            <div class="col-6"><strong>Booking ID:</strong></div><div class="col-6">{{ $booking->booking_id }}</div>
                            <div class="col-6"><strong>Name:</strong></div><div class="col-6">{{ $booking->full_name }}</div>
                            <div class="col-6"><strong>Stall Type:</strong></div><div class="col-6">{{ ucwords(str_replace('_', ' ', $booking->stall_type)) }}</div>
                            <div class="col-6"><strong>Check-in:</strong></div><div class="col-6">{{ $booking->check_in_date->format('M d, Y') }}</div>
                            <div class="col-6"><strong>Check-out:</strong></div><div class="col-6">{{ $booking->check_out_date->format('M d, Y') }}</div>
                            <div class="col-6"><strong>Amount Paid:</strong></div><div class="col-6">${{ number_format($booking->total_amount, 2) }}</div>
                        </div>
                    </div>

                    @if($booking->refund_plan)
                        <div class="alert alert-success"><i class="bi bi-shield-check me-2"></i>You purchased the <strong>Refund Protection Plan</strong>. You are eligible for a refund.</div>
                    @else
                        <div class="alert alert-danger"><i class="bi bi-shield-x me-2"></i>No Refund Protection Plan was purchased. <strong>No refund will be issued.</strong></div>
                    @endif

                    <p class="text-muted small">Are you sure you want to cancel this booking? This action cannot be undone.</p>

                    <form action="{{ route('cancellation.confirm') }}" method="POST">
                        @csrf
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-danger flex-fill">Yes, Cancel Booking</button>
                            <a href="{{ route('cancellation.index') }}" class="btn btn-outline-secondary flex-fill">Go Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
