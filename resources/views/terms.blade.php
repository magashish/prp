@extends('layouts.app')
@section('title', 'Terms & Conditions')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h2 class="fw-bold mb-4">Terms &amp; Conditions</h2>

            <div class="card border-0 shadow-sm p-4">
                <h5>1. Booking</h5>
                <p class="small text-muted">All bookings are subject to availability. Payment is required at the time of booking. Only paid bookings are confirmed.</p>

                <h5>2. Stall Types</h5>
                <p class="small text-muted">Reserved stalls ($45/day) are limited to 2 spaces. A parking printout must be placed on the vehicle dashboard. Non-reserved stalls ($35/day) provide access to up to 75 available spaces and require a parking pass with access code to enter the garage.</p>

                <h5>3. Booking Rules</h5>
                <ul class="small text-muted">
                    <li>No same-day bookings.</li>
                    <li>No next-day bookings after 4:00 PM.</li>
                    <li>Weekend bookings close Friday at 12:00 PM.</li>
                    <li>No Sunday bookings on Saturday.</li>
                    <li>International bookings and payments are accepted.</li>
                </ul>

                <h5>4. Cancellation &amp; Refunds</h5>
                <p class="small text-muted">Cancellations may be submitted online. Refunds are only issued if the <strong>Refund Protection Plan ($25)</strong> was purchased at the time of booking. Without this plan, no refund will be issued. Refunds are processed manually by the admin.</p>

                <h5>5. Payment</h5>
                <p class="small text-muted">All payments are processed securely through PayPal. Prices are quoted in USD. A 4.5% tax and 3% service fee apply to all bookings.</p>

                <h5>6. Access</h5>
                <p class="small text-muted">For non-reserved stalls, the customer must present a valid parking pass with the assigned access code to enter the parking garage. Entry is not permitted without the code.</p>

                <h5>7. Liability</h5>
                <p class="small text-muted">The parking facility is not responsible for theft, damage, or loss of vehicles or personal property while on the premises.</p>
            </div>
        </div>
    </div>
</div>
@endsection
