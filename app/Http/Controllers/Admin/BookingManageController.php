<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Mail\BL5ParkingPass;
use App\Mail\BL7CancellationConfirmation;
use App\Mail\BL8ParkingCompanyCancel;
use App\Mail\BL9AdminCancellationNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class BookingManageController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::today()->toDateString();
        $query = Booking::where('check_out_date', '>=', $today);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('booking_id', 'like', "%$s%")
                  ->orWhere('full_name', 'like', "%$s%")
                  ->orWhere('email', 'like', "%$s%");
            });
        }

        if ($request->filled('stall_type')) {
            $query->where('stall_type', $request->stall_type);
        }

        if ($request->filled('pass_status')) {
            $query->where('pass_status', $request->pass_status);
        }

        $bookings = $query->orderBy('check_in_date')->paginate(20)->withQueryString();

        return view('admin.bookings.index', compact('bookings'));
    }

    public function past(Request $request)
    {
        $today = Carbon::today()->toDateString();
        $query = Booking::where('check_out_date', '<', $today);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('booking_id', 'like', "%$s%")
                  ->orWhere('full_name', 'like', "%$s%");
            });
        }

        $bookings = $query->orderByDesc('check_out_date')->paginate(20)->withQueryString();

        return view('admin.bookings.past', compact('bookings'));
    }

    public function cancelled(Request $request)
    {
        $query = Booking::whereIn('status', ['cancelled_with_refund', 'cancelled_no_refund']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('booking_id', 'like', "%$s%")
                  ->orWhere('full_name', 'like', "%$s%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $bookings = $query->orderByDesc('updated_at')->paginate(20)->withQueryString();

        return view('admin.bookings.cancelled', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        return view('admin.bookings.show', compact('booking'));
    }

    public function edit(Booking $booking)
    {
        return view('admin.bookings.edit', compact('booking'));
    }

    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'full_name'    => 'required|string|max:255',
            'phone_number' => 'required|string|max:30',
            'email'        => 'required|email',
            'notes'        => 'nullable|string',
            'parking_code' => 'nullable|string|max:50',
        ]);

        $booking->update($request->only('full_name', 'phone_number', 'email', 'notes', 'parking_code'));

        return redirect()->route('admin.bookings.show', $booking)->with('success', 'Booking updated.');
    }

    public function destroy(Request $request, Booking $booking)
    {
        $booking->delete();
        $from = $request->input('from');
        $route = match($from) {
            'past'      => 'admin.bookings.past',
            'cancelled' => 'admin.bookings.cancelled',
            default     => 'admin.bookings.index',
        };
        return redirect()->route($route)->with('success', 'Booking deleted.');
    }

    public function sendParkingCode(Request $request, Booking $booking)
    {
        $request->validate([
            'parking_code' => 'required|string|max:50',
        ]);

        $booking->update([
            'parking_code' => $request->parking_code,
            'pass_status'  => 'sent',
        ]);

        Mail::to($booking->email)->send(new BL5ParkingPass($booking));

        return redirect()->route('admin.bookings.show', $booking)->with('success', 'Parking pass sent to customer.');
    }

    public function cancel(Request $request, Booking $booking)
    {
        $status = $booking->refund_plan ? 'cancelled_with_refund' : 'cancelled_no_refund';
        $booking->update(['status' => $status]);

        Mail::to($booking->email)->send(new BL7CancellationConfirmation($booking));
        Mail::to(config('mail.parking_company_email'))->send(new BL8ParkingCompanyCancel($booking));
        Mail::to(config('mail.admin_email'))->send(new BL9AdminCancellationNotification($booking));

        return redirect()->route('admin.bookings.show', $booking)
            ->with('success', 'Booking cancelled and notification emails sent.');
    }
}
