<x-mail::message>
# Parking Booking Confirmed

Dear {{ $booking->full_name }},

Your non-reserved parking booking has been confirmed. Your **parking access code will be sent to you shortly** in a separate email once it has been processed by the parking facility.

<x-mail::panel>
**Booking ID:** {{ $booking->booking_id }}
**Stall Type:** Non-Reserved
**Check-in:** {{ $booking->check_in_date->format('l, F j, Y') }}
**Check-out:** {{ $booking->check_out_date->format('l, F j, Y') }}
**Name:** {{ $booking->full_name }}
**Total Paid:** ${{ number_format($booking->total_amount, 2) }}
**Refund Plan:** {{ $booking->refund_plan ? 'Yes (Purchased)' : 'No' }}
</x-mail::panel>

> **Important:** You **must** present your parking pass with the access code to enter the parking garage. Entry will not be permitted without it.

Thank you for booking with us!

{{ config('app.name') }}
</x-mail::message>
