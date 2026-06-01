<x-mail::message>
Aloha {{ $booking->full_name }},

Your Parking Pass with access code is now ready.

**IMPORTANT:** This email contains your Parking Pass attachment, which is required for access to the parking garage.

*You must print or have the attached Parking Pass available upon arrival. Entry may be denied without the Parking Pass and Code.*

**RESERVATION DETAILS**

| | |
|:---|:---|
| Booking #: | **{{ $booking->booking_id }}** |
| Stall Type: | **{{ ucwords(str_replace('_', ' ', $booking->stall_type)) }}** |
| Check-In Date: | **{{ $booking->check_in_date->format('m/d/Y') }}** |
| Check-In Starts: | **12:00 AM** |
| Check-Out Date: | **{{ $booking->check_out_date->format('m/d/Y') }}** |
| Check-Out By: | **11:59 PM** |
| Location: | **Discovery Bay Condo Parking** |
| | **1778 Ala Moana Blvd., Honolulu, HI** |
| Entrance: | Accessible via Hobron Lane or the back street off Kaio'o Drive. |

---

**PARKING INSTRUCTIONS**

- Print or save the attached Parking Pass before arrival
- The Parking Pass with access code are required for garage entry
- Place the Parking Pass visibly on your vehicle dashboard if instructed
- Follow all parking instructions listed on the attached Parking Pass

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
