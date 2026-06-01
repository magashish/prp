<x-mail::message>
Aloha {{ $booking->full_name }},

Your parking reservation has been successfully cancelled.

**BOOKING DETAILS**

| | |
|:---|:---|
| Booking #: | **{{ $booking->booking_id }}** |
| Check-In Date: | **{{ $booking->check_in_date->format('m/d/Y') }}** |
| Check-Out Date: | **{{ $booking->check_out_date->format('m/d/Y') }}** |
| Stall Type: | **{{ ucwords(str_replace('_', ' ', $booking->stall_type)) }}** |

---

**REFUND DETAILS**

@if($booking->status === 'cancelled_with_refund')
| Refund Amount: | **${{ number_format($booking->total_amount, 2) }}** |
|:---|:---|

The refund will be processed accordingly.
@else
This booking is not eligible for a refund, as the Refund Protection Plan was not purchased.
@endif

---

**Important Note**

All cancellation and refund policies are based on Hawaii Standard Time (HST).

If you have any questions, please contact us.

blrentals@gmail.com
(000) 000-0000
</x-mail::message>
