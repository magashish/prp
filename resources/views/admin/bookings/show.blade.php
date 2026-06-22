@extends('layouts.admin')
@section('title', 'Booking ' . $booking->booking_id)

@php
    $isCancelled = in_array($booking->status, ['cancelled_with_refund', 'cancelled_no_refund']);
    $isPast = !$isCancelled && $booking->check_out_date->lt(\Carbon\Carbon::today());
    $from = $isCancelled ? 'cancelled' : ($isPast ? 'past' : 'index');
    $backRoute = $isCancelled ? route('admin.bookings.cancelled') : ($isPast ? route('admin.bookings.past') : route('admin.bookings.index'));
    $backLabel = $isCancelled ? 'Cancelled Bookings' : ($isPast ? 'Past Bookings' : 'Bookings');
    $needsRefund = $booking->status === 'active' && $booking->refund_plan;
@endphp
@section('content')

{{-- Refund required alert (shown after cancel) --}}
@if($booking->status === 'cancelled_with_refund')
<div class="alert alert-danger border-danger border-3 d-flex align-items-start gap-3 mb-4" role="alert">
    <i class="bi bi-exclamation-triangle-fill fs-2 text-danger mt-1"></i>
    <div>
        <h5 class="fw-bold mb-1">⚠ Refund Action Required</h5>
        <p class="mb-1">This customer purchased the <strong>Refund Protection Plan</strong> and is eligible for a full refund of <strong>${{ number_format($booking->total_amount, 2) }}</strong>.</p>
        <p class="mb-0">Please process the refund via PayPal or your payment processor. Transaction ID: <code>{{ $booking->paypal_transaction_id ?? 'N/A' }}</code></p>
    </div>
</div>
@endif

<div class="d-flex justify-content-between align-items-center mb-4">
    <a href="{{ $backRoute }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i>Back to {{ $backLabel }}
    </a>

    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal">
        Delete
    </button>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Booking Details – <code>{{ $booking->booking_id }}</code></h6>
                @if($booking->status === 'active')
                    <span class="badge bg-success fs-6 px-3 py-2">Active</span>
                @elseif($booking->status === 'cancelled_with_refund')
                    <span class="badge bg-warning text-dark fs-6 px-3 py-2">Cancelled – Refund Due</span>
                @else
                    <span class="badge bg-secondary fs-6 px-3 py-2">Cancelled</span>
                @endif
            </div>

            <div class="card-body">
                <table class="table table-sm mb-0">
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
                    <tr><th>Refund Plan</th><td>{!! $booking->refund_plan ? '<span class="badge bg-success">Yes – Eligible</span>' : 'No' !!}</td></tr>
                    <tr><th>PayPal TxID</th><td><small>{{ $booking->paypal_transaction_id ?? '—' }}</small></td></tr>
                    <tr>
                        <th>Pass Status</th>
                        <td>
                            @if($booking->pass_status === 'required')
                            <span class="badge bg-danger fs-6 px-3 py-2">Required</span>
                            @elseif($booking->pass_status === 'sent')
                            <span class="badge bg-success fs-6 px-3 py-2">Sent</span>
                            @else
                            <span class="badge bg-secondary fs-6 px-3 py-2">N/A</span>
                            @endif
                        </td>
                    </tr>
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
                @if($booking->status === 'active')
                <a href="{{ route('admin.bookings.edit', $booking) }}" class="btn btn-outline-primary btn-sm">Edit</a>
                <button type="button" class="btn btn-warning btn-sm ms-auto" data-bs-toggle="modal" data-bs-target="#cancelModal">
                    Cancel Booking
                </button>
                @endif
            </div>
        </div>
    </div>

    @if($booking->stall_type === 'non_reserved' && $booking->status === 'active')
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm border-warning">
            <div class="card-header bg-primary text-white">
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
                    <button type="submit" class="btn btn-warning w-100">
                        <i class="bi bi-send-fill me-1"></i>Send Parking Pass to Customer
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>

{{-- Cancel Booking Modal --}}
<div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title fw-bold">Cancel Booking?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @if($needsRefund)
                <div class="alert alert-danger border-danger border-3 mb-3">
                    <h6 class="fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i>Refund Required</h6>
                    <p class="mb-0">This customer purchased the <strong>Refund Protection Plan</strong>. Cancelling will mark this booking as <strong>Cancelled – Refund Due (${{ number_format($booking->total_amount, 2) }})</strong>. You must process the refund manually via PayPal.</p>
                </div>
                @else
                <p>This customer did <strong>not</strong> purchase the Refund Protection Plan. No refund is required.</p>
                @endif
                <p class="mb-0">Are you sure you want to cancel booking <strong>{{ $booking->booking_id }}</strong>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Go Back</button>
                <form action="{{ route('admin.bookings.cancel', $booking) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-warning fw-bold">Yes, Cancel Booking</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Delete Booking Modal --}}
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold"><i class="bi bi-trash-fill me-2"></i>Delete Booking?</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @if($needsRefund)
                <div class="alert alert-danger border-danger border-3 mb-3">
                    <h6 class="fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i>⚠ Refund Required Before Deleting</h6>
                    <p class="mb-0">This customer purchased the <strong>Refund Protection Plan</strong> and is owed a refund of <strong>${{ number_format($booking->total_amount, 2) }}</strong>. Deleting this record will permanently remove all booking data. Make sure you have already processed the refund via PayPal before proceeding.</p>
                </div>
                @endif
                <p class="mb-0">This action is <strong>permanent and cannot be undone</strong>. Delete booking <strong>{{ $booking->booking_id }}</strong>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Go Back</button>
                <form action="{{ route('admin.bookings.destroy', $booking) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="from" value="{{ $from }}">
                    <button type="submit" class="btn btn-danger fw-bold">Yes, Permanently Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@push('scripts')
@if(request('cancel') && $booking->status === 'active')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        new bootstrap.Modal(document.getElementById('cancelModal')).show();
    });
</script>
@endif
@endpush
@endsection
