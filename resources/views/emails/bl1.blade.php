<x-mail::message>
Aloha {{ $booking->full_name }},

Thank you for choosing BL Rentals. Your parking reservation is confirmed.

---

## ⚠ PARKING PASS — PRINT &amp; PLACE ON DASHBOARD

**Please print this email and place it visibly on your vehicle dashboard.**

<x-mail::panel>
**Booking #:** {{ $booking->booking_id }}
**Name:** {{ $booking->full_name }}
**Stall Type:** {{ ucwords(str_replace('_', ' ', $booking->stall_type)) }}
**Stall Number:** {{ $booking->stall_number }}
**Check-In Date:** {{ $booking->check_in_date->format('m/d/Y') }} at 12:00 AM
**Check-Out Date:** {{ $booking->check_out_date->format('m/d/Y') }} by 11:59 PM
**Amount Paid:** ${{ number_format($booking->total_amount, 2) }}
**Location:** Discovery Bay Condo Parking · 1778 Ala Moana Blvd., Honolulu, HI
**Entrance:** Accessible via Hobron Lane or the back street off Kaio'o Drive.
</x-mail::panel>

> You do **not** need a Parking Code. This email serves as your Parking Pass. Print it and display it on your dashboard for the duration of your stay.

---

**CANCELLATION PROCESS**

*To cancel your reservation, submit a cancellation request through our [website]({{ route('cancellation.index') }}) using your Booking ID#.*

---

**REFUND POLICY**

- Full refunds are only provided if the Refund Protection Plan was purchased during booking.
- Cancellation is not allowed on the check-in date.

---

*Questions? Contact us at blrentals@gmail.com or (000) 000-0000*

*All booking dates and deadlines are based on Hawaii Standard Time (HST).*
</x-mail::message>
