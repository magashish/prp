@extends('layouts.admin')
@section('title', 'Booking ' . $booking->booking_id)

@section('content')
<div class="row g-4">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Booking Details – <code>{{ $booking->booking_id }}</code></h6>
                <span class="badge {{ $booking->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                    {{ ucwords(str_replace('_', ' ', $booking->status)) }}
                </span>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr><th>Full Name</th><td>{{ $booking->full_name }}</td></tr>
                    <tr><th>Email</th><td>{{ $booking->email }}</td></tr>
                    <tr><th>Phone</th><td>{{ $booking->phone_number }}</td></tr>
                    <tr><th>Stall Type</th><td>{{ ucwords(str_replace('_',' ',$booking->stall_type)) }}{{ $booking->stall_number ? ' – Stall #'.$booking->stall_number : '' }}</td></tr>
                    <tr><th>Check-in</th><td>{{ $booking->check_in_date->format('M d, Y') }}</td></tr>
                    <tr><th>Check-out</th><td>{{ $booking->check_out_date->format('M d, Y') }}</td></tr>
                    <tr><th>Subtotal</th><td>${{ number_format($booking->subtotal, 2) }}</td></tr>
                    <tr><th>Tax</th><td>${{ number_format($booking->tax, 2) }}</td></tr>
                    <tr><th>Service Fee</th><td>${{ number_format($booking->service_fee, 2) }}</td></tr>
                    <tr><th>Total Paid</th><td class="fw-bold">${{ number_format($booking->total_amount, 2) }}</td></tr>
                    <tr><th>Refund Plan</th><td>{{ $booking->refund_plan ? 'Yes' : 'No' }}</td></tr>
                    <tr><th>PayPal TxID</th><td><small>{{ $booking->paypal_transaction_id ?? '—' }}</small></td></tr>
                    <tr><th>Pass Status</th><td>
                        @if($booking->pass_status === 'required') <span class="badge bg-danger">Required</span>
                        @elseif($booking->pass_status === 'sent') <span class="badge bg-success">Sent</span>
                        @else <span class="badge bg-secondary">N/A</span>
                        @endif
                    </td></tr>
                    @if($booking->parking_code)
                    <tr><th>Parking Code</th><td><strong>{{ $booking->parking_code }}</strong></td></tr>
                    @endif
                    @if($booking->notes)
                    <tr><th>Notes</th><td>{{ $booking->notes }}</td></tr>
                    @endif
                    <tr><th>Created</th><td>{{ $booking->created_at->format('M d, Y H:i') }}</td></tr>
                </table>
            </div>
            <div class="card-footer bg-white d-flex gap-2">
                <a href="{{ route('admin.bookings.edit', $booking) }}" class="btn btn-outline-primary btn-sm">Edit</a>
                @if($booking->status === 'active')
                <form action="{{ route('admin.bookings.cancel', $booking) }}" method="POST" onsubmit="return confirm('Cancel this booking?')">
                    @csrf
                    <button class="btn btn-warning btn-sm">Cancel Booking</button>
                </form>
                @endif
                <form action="{{ route('admin.bookings.destroy', $booking) }}" method="POST" onsubmit="return confirm('Permanently delete this booking?')" class="ms-auto">
                    @csrf @method('DELETE')
                    <button class="btn btn-danger btn-sm">Delete</button>
                </form>
            </div>
        </div>
    </div>

    @if($booking->stall_type === 'non_reserved' && $booking->status === 'active')
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm border-warning">
            <div class="card-header bg-warning">
                <h6 class="mb-0"><i class="bi bi-key-fill me-2"></i>Send Parking Pass</h6>
            </div>
            <div class="card-body">
                <p class="small text-muted">Enter the parking access code received from the parking company, then click Send to email the pass to the customer.</p>
                <form action="{{ route('admin.bookings.parking-code', $booking) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Parking Code</label>
                        <input type="text" name="parking_code" class="form-control" value="{{ $booking->parking_code }}" required>
                    </div>
                    <button type="submit" class="btn btn-warning w-100"><i class="bi bi-send-fill me-1"></i>Send Parking Pass to Customer</button>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>

<div class="mt-3">
    <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Back to Bookings</a>
</div>
@endsection
