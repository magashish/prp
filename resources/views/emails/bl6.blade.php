<x-mail::message>
# Reminder: Your Parking Booking is Coming Up

Dear {{ $booking->full_name }},

This is a friendly reminder that your parking booking is coming up soon.

<x-mail::panel>
**Booking ID:** {{ $booking->booking_id }}
**Stall Type:** {{ ucwords(str_replace('_', ' ', $booking->stall_type)) }}{{ $booking->stall_number ? ' – Stall #' . $booking->stall_number : '' }}
**Check-in:** {{ $booking->check_in_date->format('l, F j, Y') }}
**Check-out:** {{ $booking->check_out_date->format('l, F j, Y') }}
</x-mail::panel>

@if($booking->stall_type === 'reserved')
> Remember to **print your confirmation email** and place it on your vehicle dashboard.
@else
> Remember to **bring your parking pass with the access code** to enter the garage.
@endif

See you soon!

{{ config('app.name') }}
</x-mail::message>
