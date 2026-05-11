<x-mail::message>
# Booking Cancellation Confirmed

Dear {{ $booking->full_name }},

Your booking has been successfully cancelled.

<x-mail::panel>
**Booking ID:** {{ $booking->booking_id }}
**Stall Type:** {{ ucwords(str_replace('_', ' ', $booking->stall_type)) }}
**Check-in:** {{ $booking->check_in_date->format('l, F j, Y') }}
**Check-out:** {{ $booking->check_out_date->format('l, F j, Y') }}
**Cancellation Status:** {{ $booking->status === 'cancelled_with_refund' ? '✅ Cancelled with Refund' : '❌ Cancelled – No Refund' }}
</x-mail::panel>

@if($booking->status === 'cancelled_with_refund')
Your refund will be processed manually by our team. Please allow a few business days for it to appear.
@else
No refund is applicable as the Refund Protection Plan was not purchased at the time of booking.
@endif

If you believe this was a mistake, please contact us immediately.

{{ config('app.name') }}
</x-mail::message>
