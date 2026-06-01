@php
    $totalDays = $booking->check_in_date->diffInDays($booking->check_out_date) + 1;
    $refundAmount = $booking->status === 'cancelled_with_refund' ? $booking->total_amount : 0;
    $statusLabel = $booking->status === 'cancelled_with_refund' ? 'Cancelled with Refund' : 'Cancelled No Refund';
@endphp
<x-mail::message>
Hi Admin,

A booking has been cancelled. See detail below.

**BOOKING INFORMATION DETAILS**

| | |
|:---|:---|
| Booking #: | **{{ $booking->booking_id }}** |
| Customer: | **{{ $booking->full_name }}** |
| Check-In Date: | **{{ $booking->check_in_date->format('m/d/Y') }}** |
| Check-Out Date: | **{{ $booking->check_out_date->format('m/d/Y') }}** |
| Total Days: | **{{ $totalDays }} Day(s)** |
| Stall Type: | **{{ ucwords(str_replace('_', ' ', $booking->stall_type)) }}** |

---

**CANCELLATION DETAILS**

| | |
|:---|:---|
| Amount Paid: | **${{ number_format($booking->total_amount, 2) }}** |
| Refund Amount: | **${{ number_format($refundAmount, 2) }}** |
| Status: | **{{ $statusLabel }}** |

Please review and process if necessary.

<x-mail::button :url="config('app.url') . '/admin/bookings/' . $booking->id">
View in Admin
</x-mail::button>
</x-mail::message>
