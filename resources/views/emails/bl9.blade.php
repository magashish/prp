<x-mail::message>
# [Admin] Booking Cancelled – {{ $booking->booking_id }}

A booking has been cancelled.

<x-mail::panel>
**Booking ID:** {{ $booking->booking_id }}
**Customer:** {{ $booking->full_name }} ({{ $booking->email }})
**Stall Type:** {{ ucwords(str_replace('_', ' ', $booking->stall_type)) }}
**Check-in:** {{ $booking->check_in_date->format('l, F j, Y') }}
**Check-out:** {{ $booking->check_out_date->format('l, F j, Y') }}
**Total Paid:** ${{ number_format($booking->total_amount, 2) }}
**Refund Plan:** {{ $booking->refund_plan ? 'Yes – Refund Required' : 'No – No Refund' }}
**Status:** {{ ucwords(str_replace('_', ' ', $booking->status)) }}
</x-mail::panel>

@if($booking->status === 'cancelled_with_refund')
**Action Required:** Please process the refund of ${{ number_format($booking->total_amount, 2) }} via PayPal for transaction `{{ $booking->paypal_transaction_id }}`.
@endif

<x-mail::button :url="config('app.url') . '/admin/bookings/' . $booking->id">
View in Admin
</x-mail::button>

{{ config('app.name') }}
</x-mail::message>
