<x-mail::message>
# New Booking Received – {{ $booking->booking_id }}

A new booking has been submitted and payment confirmed.

<x-mail::panel>
**Booking ID:** {{ $booking->booking_id }}
**Customer:** {{ $booking->full_name }} ({{ $booking->email }})
**Phone:** {{ $booking->phone_number }}
**Stall Type:** {{ ucwords(str_replace('_', ' ', $booking->stall_type)) }}{{ $booking->stall_number ? ' – Stall #' . $booking->stall_number : '' }}
**Check-in:** {{ $booking->check_in_date->format('l, F j, Y') }}
**Check-out:** {{ $booking->check_out_date->format('l, F j, Y') }}
**Total Paid:** ${{ number_format($booking->total_amount, 2) }}
**Refund Plan:** {{ $booking->refund_plan ? 'Yes' : 'No' }}
**Pass Status:** {{ ucwords(str_replace('_', ' ', $booking->pass_status)) }}
**PayPal Tx:** {{ $booking->paypal_transaction_id ?? 'N/A' }}
</x-mail::panel>

@if($booking->stall_type === 'non_reserved')
<x-mail::button :url="config('app.url') . '/admin/bookings/' . $booking->id">
Open Booking in Admin
</x-mail::button>
@endif

{{ config('app.name') }}
</x-mail::message>
