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
                    <th>Booking ID</th><th>Name</th><th>Email</th><th>Stall</th>
                    <th>Check-in</th><th>Check-out</th><th>Total</th><th>Pass</th><th>Status</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $b)
                <tr>
                    <td><code>{{ $b->booking_id }}</code></td>
                    <td>{{ $b->full_name }}</td>
                    <td class="small">{{ $b->email }}</td>
                    <td>{{ ucwords(str_replace('_',' ',$b->stall_type)) }}{{ $b->stall_number ? ' #'.$b->stall_number : '' }}</td>
                    <td>{{ $b->check_in_date->format('M d, Y') }}</td>
                    <td>{{ $b->check_out_date->format('M d, Y') }}</td>
                    <td>${{ number_format($b->total_amount, 2) }}</td>
                    <td>
                        @if($b->pass_status === 'required') <span class="badge bg-danger">Required</span>
                        @elseif($b->pass_status === 'sent') <span class="badge bg-success">Sent</span>
                        @else <span class="badge bg-secondary">N/A</span>
                        @endif
                    </td>
                    <td>{{ $b->status }}</td>
                    <td>
                        <a href="{{ route('admin.bookings.show', $b) }}" class="btn btn-sm btn-outline-primary">View</a>
                        <a href="{{ route('admin.bookings.edit', $b) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" class="text-center text-muted py-4">No bookings found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer bg-white">
        {{ $bookings->links() }}
    </div>
</div>
@endsection
