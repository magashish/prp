<x-mail::message>
Aloha {{ $booking->full_name }},

This is a reminder of your upcoming parking reservation.

**RESERVATION DETAILS**

| | |
|:---|:---|
| Booking #: | **{{ $booking->booking_id }}** |
| Stall Type: | **{{ ucwords(str_replace('_', ' ', $booking->stall_type)) }}** |
@if($booking->stall_type === 'reserved')
| Stall Number: | **{{ $booking->stall_number }}** |
@endif
| Check-In Date: | **{{ $booking->check_in_date->format('m/d/Y') }}** |
| Check-In Time: | **12:00 AM** |
| Check-Out Date: | **{{ $booking->check_out_date->format('m/d/Y') }}** |
| Check-Out Time: | **11:59 PM** |
| Location: | **Discovery Bay Condo Parking** |
| | **1778 Ala Moana Blvd., Honolulu, HI** |
| Entrance: | Accessible via Hobron Lane or the back street off Kaio'o Drive. |

---

***IMPORTANT INFORMATION***

**Non-Reserved Parking** requires a Parking Pass with access code to enter the parking garage. You must print and have your Parking Pass available upon arrival.

**Reserved Parking** do not require a parking code for entry. However, the Parking Pass must be printed and placed visibly on your vehicle dashboard during your stay.

Entry or parking privileges may be denied without a valid Parking Pass.

---

If you have any questions, please contact us.

blrentals@gmail.com
(000) 000-0000

---

***CANCELLATION PROCESS***

*To cancel your booking, please submit a cancellation request through our [website]({{ route('cancellation.index') }}) using your Booking ID#*

---

***REFUND POLICY***

- *Full refunds are only provided if the Refund Protection Plan was purchased during booking*
- *Cancellation is not allowed on the check-in date*

---

*All booking dates and deadlines are based on Hawaii Standard Time (HST).*
</x-mail::message>
