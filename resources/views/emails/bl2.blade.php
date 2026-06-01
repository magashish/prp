<x-mail::message>
Aloha {{ $booking->full_name }},

Thank you for choosing BL Rentals. Your parking reservation is booked.

***IMPORTANT INFORMATION: Parking Pass Required for Entry***

Your Parking Pass will be **EMAILED** separately.
You must **PRINT** and have your Parking Pass available to **ENTER**.

*Entry may be denied without a valid Parking Pass.*

**YOUR RESERVATION DETAILS**

| | |
|:---|:---|
| Booking #: | **{{ $booking->booking_id }}** |
| Stall Type: | **{{ ucwords(str_replace('_', ' ', $booking->stall_type)) }}** |
| Stall Number: | **N/A** |
| Check-In Date: | **{{ $booking->check_in_date->format('m/d/Y') }}** |
| Check-In Starts: | **12:00 AM** |
| Check-Out Date: | **{{ $booking->check_out_date->format('m/d/Y') }}** |
| Check-Out By: | **11:59 PM** |
| Amount Paid: | **${{ number_format($booking->total_amount, 2) }}** |
| Refund Plan: | **{{ $booking->refund_plan ? 'Yes (Purchased)' : 'No' }}** |
| Location: | **Discovery Bay Condo Parking** |
| | **1778 Ala Moana Blvd., Honolulu, HI** |
| Entrance: | Accessible via Hobron Lane or the back street off Kaio'o Drive. |

---

If you have any questions, please contact us.

blrentals@gmail.com
(000) 000-0000

---

***CANCELLATION PROCESS***

*To cancel your booking, please submit a cancellation request through our [website]({{ route('cancellation.index') }}) using your Booking ID#*

---

***REFUND POLICY***

- *Full refunds are only provided if the Refund Protection Plan was purchased during booking.*
- *Cancellation is not allowed on the check-in date*

---

*All booking dates and deadlines are based on Hawaii Standard Time (HST).*
</x-mail::message>
