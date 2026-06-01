<x-mail::message>
Hi Admin,

A parking reservation has been cancelled. See detail below.

**BOOKING INFORMATION DETAILS**

| | |
|:---|:---|
| Parking Code: | **{{ $booking->parking_code ?? 'N/A' }}** |
| Check-In Date: | **{{ $booking->check_in_date->format('m/d/Y') }}** |
| Check-Out Date: | **{{ $booking->check_out_date->format('m/d/Y') }}** |

Please review and process if necessary.
</x-mail::message>
