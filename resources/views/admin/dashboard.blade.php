@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<div class="row g-4 mb-4">
    {{-- Active Bookings - highlighted blue --}}
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center p-3" style="background:#dce8f7;">
            <div class="fs-1 fw-bold" style="color:#0f4c81;">{{ $totalActive }}</div>
            <div class="text-muted small">Active Bookings</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="text-dark fs-1 fw-bold">{{ $reservedCount }}</div>
            <div class="text-muted small">Reserved Stalls</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="text-dark fs-1 fw-bold">{{ $nonResCount }}</div>
            <div class="text-muted small">Non-Reserved</div>
        </div>
    </div>
    {{-- Pending Parking Pass - highlighted red --}}
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center p-3" style="background:#fde8e8;">
            <div class="fs-1 fw-bold text-danger">{{ $pendingPass }}</div>
            <div class="text-muted small">Pending Parking Pass</div>
        </div>
    </div>
    <div class="col-md-3">
        <a href="{{ route('admin.bookings.cancelled') }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm text-center p-3">
                <div class="text-warning fs-1 fw-bold">{{ $cancelledCount }}</div>
                <div class="text-muted small">Cancelled Bookings</div>
            </div>
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h6 class="mb-0">Recent Bookings</h6>
        <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Booking ID</th><th>Name</th><th>Stall</th><th>Check-in</th><th>Check-out</th><th>Pass Status</th><th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentBookings as $b)
                <tr>
                    <td><code>{{ $b->booking_id }}</code></td>
                    <td>{{ $b->full_name }}</td>
                    <td>{{ ucwords(str_replace('_',' ',$b->stall_type)) }}{{ $b->stall_number ? ' #'.$b->stall_number : '' }}</td>
                    <td>{{ $b->check_in_date->format('M d, Y') }}</td>
                    <td>{{ $b->check_out_date->format('M d, Y') }}</td>
                    <td>
                        @if($b->pass_status === 'required') <span class="badge badge-pass-required">Required</span>
                        @elseif($b->pass_status === 'sent') <span class="badge badge-pass-sent">Sent</span>
                        @else <span class="badge badge-pass-not-required">N/A</span>
                        @endif
                    </td>
                    <td><a href="{{ route('admin.bookings.show', $b) }}" class="btn btn-sm btn-outline-secondary">View</a></td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">No active bookings.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
