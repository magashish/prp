<x-mail::message>
# Reserved Stall – Booking Confirmed

Dear {{ $booking->full_name }},

Your reserved parking stall has been confirmed. Please **print this email** and place it on your vehicle dashboard.

<x-mail::panel>
**Booking ID:** {{ $booking->booking_id }}
**Stall:** Reserved – Stall #{{ $booking->stall_number }}
**Check-in:** {{ $booking->check_in_date->format('l, F j, Y') }}
**Check-out:** {{ $booking->check_out_date->format('l, F j, Y') }}
**Name:** {{ $booking->full_name }}
**Total Paid:** ${{ number_format($booking->total_amount, 2) }}
</x-mail::panel>

> **Important:** Print this confirmation and place it visibly on your vehicle dashboard. No access code is required for reserved stalls.

Thank you for booking with us!

{{ config('app.name') }}
</x-mail::message>
