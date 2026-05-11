<x-mail::message>
# Your Parking Pass – Access Code

Dear {{ $booking->full_name }},

Your parking access code is ready. Please **print or show this pass** on your mobile device to enter the parking garage.

<x-mail::panel>
**Booking ID:** {{ $booking->booking_id }}
**Check-in:** {{ $booking->check_in_date->format('l, F j, Y') }}
**Check-out:** {{ $booking->check_out_date->format('l, F j, Y') }}
</x-mail::panel>

## Your Access Code:

<div style="background:#fff3cd;border:2px dashed #ffc107;border-radius:6px;text-align:center;padding:20px;margin:20px 0;font-size:2rem;font-weight:bold;letter-spacing:6px;">
{{ $booking->parking_code }}
</div>

> **Critical:** You must present this parking pass with the access code to enter the parking garage. **Entry is not allowed without the code.**

If you have any questions, please contact us.

{{ config('app.name') }}
</x-mail::message>
