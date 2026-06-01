<x-mail::message>
Hi,

A new parking reservation has been made. Please prepare the parking code for the following reservations.

**RESERVATION DETAILS**

| | |
|:---|:---|
| Booking #: | **{{ $booking->booking_id }}** |
| Check-In Date: | **{{ $booking->check_in_date->format('m/d/Y') }}** |
| Check-Out Date: | **{{ $booking->check_out_date->format('m/d/Y') }}** |
| Parking Code: | |

</x-mail::message>
