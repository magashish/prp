@extends('layouts.app')
@section('title', 'Complete Your Booking')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0"><i class="bi bi-credit-card me-2"></i>Complete Your Booking</h5>
                </div>
                <div class="card-body p-4">

                    <div class="booking-summary mb-4">
                        <div class="row g-2 small">
                            <div class="col-6"><strong>Stall Type:</strong></div>
                            <div class="col-6">{{ ucwords(str_replace('_', ' ', $pending['stall_type'])) }}{{ isset($pending['stall_number']) && $pending['stall_number'] ? ' – Stall #'.$pending['stall_number'] : '' }}</div>
                            <div class="col-6"><strong>Check-in:</strong></div>
                            <div class="col-6">{{ \Carbon\Carbon::parse($pending['check_in_date'])->format('M d, Y') }}</div>
                            <div class="col-6"><strong>Check-out:</strong></div>
                            <div class="col-6">{{ \Carbon\Carbon::parse($pending['check_out_date'])->format('M d, Y') }}</div>
                            <div class="col-6"><strong>Days:</strong></div>
                            <div class="col-6">{{ $pending['days'] }}</div>
                            <div class="col-6"><strong>Subtotal:</strong></div>
                            <div class="col-6">${{ number_format($pending['subtotal'], 2) }}</div>
                            <div class="col-6"><strong>Tax (4.5%):</strong></div>
                            <div class="col-6">${{ number_format($pending['tax'], 2) }}</div>
                            <div class="col-6"><strong>Service Fee (3%):</strong></div>
                            <div class="col-6">${{ number_format($pending['service_fee'], 2) }}</div>
                            <div class="col-6 fw-bold"><strong>Total:</strong></div>
                            <div class="col-6 fw-bold text-primary">${{ number_format($pending['total'], 2) }}</div>
                        </div>
                    </div>

                    <form action="{{ route('booking.store-session') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="full_name" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone Number</label>
                                <input type="tel" name="phone_number" class="form-control" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                        </div>

                        <div class="mt-3 p-3 border rounded bg-light">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="refund_plan" value="1" id="refundPlan">
                                <label class="form-check-label" for="refundPlan">
                                    <strong>Add Refund Protection Plan – $25.00</strong>
                                    <small class="d-block text-muted">Receive a full refund if you need to cancel.</small>
                                </label>
                            </div>
                        </div>

                        <div class="form-check mt-3">
                            <input class="form-check-input" type="checkbox" name="terms" id="terms" value="1" required>
                            <label class="form-check-label" for="terms">
                                I agree to the <a href="{{ route('terms') }}" target="_blank">Terms &amp; Conditions</a>
                            </label>
                        </div>

                        <button type="submit" class="btn btn-success w-100 mt-3">
                            <i class="bi bi-lock-fill me-1"></i> Continue to Payment
                        </button>
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary w-100 mt-2">← Change Dates / Stall Type</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection