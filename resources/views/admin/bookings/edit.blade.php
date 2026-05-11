@extends('layouts.admin')
@section('title', 'Edit Booking ' . $booking->booking_id)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h6 class="mb-0">Edit – <code>{{ $booking->booking_id }}</code></h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.bookings.update', $booking) }}" method="POST">
                    @csrf @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="full_name" class="form-control" value="{{ old('full_name', $booking->full_name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number', $booking->phone_number) }}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $booking->email) }}" required>
                        </div>
                        @if($booking->stall_type === 'non_reserved')
                        <div class="col-12">
                            <label class="form-label">Parking Code</label>
                            <input type="text" name="parking_code" class="form-control" value="{{ old('parking_code', $booking->parking_code) }}">
                        </div>
                        @endif
                        <div class="col-12">
                            <label class="form-label">Admin Notes</label>
                            <textarea name="notes" class="form-control" rows="3">{{ old('notes', $booking->notes) }}</textarea>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
