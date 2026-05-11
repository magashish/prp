<x-mail::message>
# Booking Cancellation Notice – {{ $booking->booking_id }}

A customer has cancelled their non-reserved parking booking.

<x-mail::panel>
**Booking ID:** {{ $booking->booking_id }}
**Customer:** {{ $booking->full_name }}
**Check-in:** {{ $booking->check_in_date->format('l, F j, Y') }}
**Check-out:** {{ $booking->check_out_date->format('l, F j, Y') }}
**Status:** {{ ucwords(str_replace('_', ' ', $booking->status)) }}
</x-mail::panel>

Please update your records accordingly.

{{ config('app.name') }}
</x-mail::message>
