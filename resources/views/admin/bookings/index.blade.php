@extends('layouts.admin')
@section('title', 'Current Bookings')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <form class="row g-2 align-items-end" method="GET">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search ID, name, email..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="stall_type" class="form-select form-select-sm">
                    <option value="">All Stall Types</option>
                    <option value="reserved" {{ request('stall_type') === 'reserved' ? 'selected' : '' }}>Reserved</option>
                    <option value="non_reserved" {{ request('stall_type') === 'non_reserved' ? 'selected' : '' }}>Non-Reserved</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="pass_status" class="form-select form-select-sm">
                    <option value="">All Pass Status</option>
                    <option value="required" {{ request('pass_status') === 'required' ? 'selected' : '' }}>Required</option>
                    <option value="sent" {{ request('pass_status') === 'sent' ? 'selected' : '' }}>Sent</option>
                    <option value="not_required" {{ request('pass_status') === 'not_required' ? 'selected' : '' }}>Not Required</option>
                </select>
            </div>
            <div class="col-md-auto">
                <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Booking ID</th><th>Name</th><th>Stall</th>
                    <th>Check-in</th><th>Check-out</th><th>Total</th><th>Pass</th><th>Status</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $b)
                @php $isCancelled = in_array($b->status, ['cancelled_with_refund', 'cancelled_no_refund']); @endphp
                <tr class="{{ $isCancelled ? 'table-danger' : '' }}">
                    <td><code>{{ $b->booking_id }}</code></td>
                    <td>{{ $b->full_name }}</td>
                    <td>{{ ucwords(str_replace('_',' ',$b->stall_type)) }}{{ $b->stall_number ? ' #'.$b->stall_number : '' }}</td>
                    <td>{{ $b->check_in_date->format('M d, Y') }}</td>
                    <td>{{ $b->check_out_date->format('M d, Y') }}</td>
                    <td>${{ number_format($b->total_amount, 2) }}</td>
                    <td>
                        @if($b->pass_status === 'required') <span class="badge bg-danger fs-6 px-3 py-2">Required</span>
                        @elseif($b->pass_status === 'sent') <span class="badge bg-success fs-6 px-3 py-2">Sent</span>
                        @else <span class="badge bg-secondary fs-6 px-3 py-2">N/A</span>
                        @endif
                    </td>
                    <td>
                        @if($b->status === 'active') <span class="badge bg-success">Active</span>
                        @elseif($b->status === 'cancelled_with_refund') <span class="badge bg-warning text-dark">Cancelled</span> <span class="badge bg-danger">⚠ Refund Due</span>
                        @else <span class="badge bg-secondary">Cancelled</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.bookings.show', $b) }}" class="btn btn-sm btn-outline-primary">View</a>
                        @if($b->status === 'active')
                        <a href="{{ route('admin.bookings.edit', $b) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-muted py-4">No bookings found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer bg-white">
        {{ $bookings->links() }}
    </div>
</div>
@endsection
