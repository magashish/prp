<x-mail::message>
# New Non-Reserved Booking – Action Required

A new non-reserved parking booking has been received. Please review, process payment from the client (outside the system), then **send the parking access code to admin**.

<x-mail::panel>
**Booking ID:** {{ $booking->booking_id }}
**Customer Name:** {{ $booking->full_name }}
**Customer Email:** {{ $booking->email }}
**Customer Phone:** {{ $booking->phone_number }}
**Check-in:** {{ $booking->check_in_date->format('l, F j, Y') }}
**Check-out:** {{ $booking->check_out_date->format('l, F j, Y') }}
</x-mail::panel>

Please send the parking access code to admin via email, phone, or text as soon as possible.

{{ config('app.name') }}
</x-mail::message>
