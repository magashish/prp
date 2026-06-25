@extends('layouts.admin')
@section('title', 'Past Bookings')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <form class="row g-2 align-items-end" method="GET">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search ID or name..." value="{{ request('search') }}">
            </div>
            <div class="col-md-auto">
                <button type="submit" class="btn btn-primary btn-sm">Search</button>
                <a href="{{ route('admin.bookings.past') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Booking ID</th><th>Name</th><th>Stall</th>
                    <th>Check-in</th><th>Check-out</th><th>Status</th><th>Total</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $b)
                @php $isCancelled = in_array($b->status, ['cancelled_with_refund', 'cancelled_no_refund']); @endphp
                <tr class="{{ $isCancelled ? 'table-danger' : '' }}">
                    <td><code>{{ $b->booking_id }}</code></td>
                    <td>{{ $b->full_name }}</td>
                    <td>{{ ucwords(str_replace('_',' ',$b->stall_type)) }}</td>
                    <td>{{ $b->check_in_date->format('M d, Y') }}</td>
                    <td>{{ $b->check_out_date->format('M d, Y') }}</td>
                    <td>
                        @if($b->status === 'active') <span class="badge bg-primary">Complete</span>
                        @elseif($b->status === 'cancelled_with_refund') <span class="badge bg-warning text-dark">Cancelled</span> <span class="badge bg-danger">⚠</span>
                        @else <span class="badge bg-secondary">Cancelled</span>
                        @endif
                    </td>
                    <td>${{ number_format($b->total_amount, 2) }}</td>
                    <td><a href="{{ route('admin.bookings.show', $b) }}" class="btn btn-sm btn-outline-primary">View</a></td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-muted py-4">No past bookings found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white">{{ $bookings->links() }}</div>
</div>
@endsection
