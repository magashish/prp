@php
    $totalDays = $booking->check_in_date->diffInDays($booking->check_out_date) + 1;
@endphp
<x-mail::message>
Hi Admin,

A new Parking Rental Booking has been successfully completed.

**BOOKING DETAILS**

| | |
|:---|:---|
| Booking #: | **{{ $booking->booking_id }}** |
| Check-In Date: | **{{ $booking->check_in_date->format('m/d/Y') }}** |
| Check-Out Date: | **{{ $booking->check_out_date->format('m/d/Y') }}** |
| Total Days: | **{{ $totalDays }} Day(s)** |
| Stall Type: | **{{ ucwords(str_replace('_', ' ', $booking->stall_type)) }}** |
| Stall Number: | **{{ $booking->stall_number ?? 'N/A' }}** |
| Amount Paid: | **${{ number_format($booking->total_amount, 2) }}** |
| Refund Plan: | **{{ $booking->refund_plan ? 'Yes (Purchased)' : 'No' }}** |
| Customer: | **{{ $booking->full_name }}** |
| Phone: | **{{ $booking->phone_number }}** |
| Email: | **{{ $booking->email }}** |

<x-mail::button :url="config('app.url') . '/admin/bookings/' . $booking->id">
View Booking in Admin
</x-mail::button>

</x-mail::message>
