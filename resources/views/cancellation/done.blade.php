@extends('layouts.app')
@section('title', 'Cancellation Complete')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 text-center">
            <div class="card border-0 shadow p-5">
                <div style="font-size:4rem; color:#dc3545;">&#x2715;</div>
                <h2 class="fw-bold mt-2">Booking Cancelled</h2>
                <p class="text-muted">Your booking <strong>{{ session('cancelled_booking') }}</strong> has been cancelled. A confirmation email has been sent to you.</p>
                <a href="{{ route('home') }}" class="btn btn-primary mt-3">Book Again</a>
            </div>
        </div>
    </div>
</div>
@endsection
