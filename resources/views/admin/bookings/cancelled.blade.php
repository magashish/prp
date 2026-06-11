@extends('layouts.admin')
@section('title', 'Cancelled Bookings')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <form class="row g-2 align-items-end" method="GET">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search ID or name..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Cancellations</option>
                    <option value="cancelled_with_refund" {{ request('status') === 'cancelled_with_refund' ? 'selected' : '' }}>With Refund</option>
                    <option value="cancelled_no_refund" {{ request('status') === 'cancelled_no_refund' ? 'selected' : '' }}>No Refund</option>
                </select>
            </div>
            <div class="col-md-auto">
                <button type="submit" class="btn btn-primary btn-sm">Search</button>
                <a href="{{ route('admin.bookings.cancelled') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Booking ID</th><th>Name</th><th>Stall</th>
                    <th>Check-in</th><th>Check-out</th><th>Status</th><th>Total</th><th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $b)
                <tr>
                    <td><code>{{ $b->booking_id }}</code></td>
                    <td>{{ $b->full_name }}</td>
                    <td>{{ ucwords(str_replace('_',' ',$b->stall_type)) }}{{ $b->stall_number ? ' #'.$b->stall_number : '' }}</td>
                    <td>{{ $b->check_in_date->format('M d, Y') }}</td>
                    <td>{{ $b->check_out_date->format('M d, Y') }}</td>
                    <td>
                        @if($b->status === 'cancelled_with_refund')
                            <span class="badge bg-info">Cancelled + Refund</span>
                        @else
                            <span class="badge bg-danger">Cancelled</span>
                        @endif
                    </td>
                    <td>${{ number_format($b->total_amount, 2) }}</td>
                    <td><a href="{{ route('admin.bookings.show', $b) }}" class="btn btn-sm btn-outline-primary">View</a></td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-muted py-4">No cancelled bookings found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white">{{ $bookings->links() }}</div>
</div>
@endsection
