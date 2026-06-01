@extends('layouts.app')
@section('title', 'Cancel Booking')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0"><i class="bi bi-x-circle me-2"></i>Cancel Your Booking</h5>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted small">Enter your booking details below to request a cancellation.</p>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
                        </div>
                    @endif

                    <form action="{{ route('cancellation.lookup') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Booking ID</label>
                            <input type="text" name="identifier" class="form-control" value="{{ old('identifier') }}" required>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Check-in Date</label>
                                <input type="date" name="check_in_date" class="form-control" value="{{ old('check_in_date') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Check-out Date</label>
                                <input type="date" name="check_out_date" class="form-control" value="{{ old('check_out_date') }}" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-danger w-100">Find My Booking</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
