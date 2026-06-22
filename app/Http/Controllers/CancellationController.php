<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Mail\BL7CancellationConfirmation;
use App\Mail\BL8ParkingCompanyCancel;
use App\Mail\BL9AdminCancellationNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CancellationController extends Controller
{
    public function index()
    {
        return view('cancellation.index');
    }

    public function lookup(Request $request)
    {
        $request->validate([
    'identifier'    => 'required|string',
    'check_in_date' => 'required|date',
    'check_out_date'=> 'required|date',
]);

$booking = Booking::where('status', 'active')
    ->where('check_in_date', $request->check_in_date)
    ->where('check_out_date', $request->check_out_date)
    ->where('booking_id', $request->identifier)
    ->first();

        if (!$booking) {
            return back()->withErrors([
                'identifier' => 'We could not find a booking with the information provided.',
            ])->withInput();
        }

        // Cancellation not allowed on or after the check-in date (Hawaii time)
        $todayHawaii = Carbon::now('Pacific/Honolulu')->startOfDay();
        if ($todayHawaii->gte(Carbon::parse($booking->check_in_date)->startOfDay())) {
            return back()->withErrors([
                'identifier' => 'Cancellations are not allowed on or after the check-in date. Please contact us directly for assistance.',
            ])->withInput();
        }

        session(['cancellation_booking_id' => $booking->id]);

        return view('cancellation.review', compact('booking'));
    }

    public function confirm(Request $request)
    {
        $bookingDbId = session('cancellation_booking_id');
        if (!$bookingDbId) {
            return redirect()->route('cancellation.index');
        }

        $booking = Booking::findOrFail($bookingDbId);

        $todayHawaii = Carbon::now('Pacific/Honolulu')->startOfDay();
        if ($todayHawaii->gte(Carbon::parse($booking->check_in_date)->startOfDay())) {
            session()->forget('cancellation_booking_id');
            return redirect()->route('cancellation.index')->with('error', 'Cancellations are not allowed on or after the check-in date. Please contact us directly for assistance.');
        }

        if ($booking->refund_plan) {
            $booking->status = 'cancelled_with_refund';
        } else {
            $booking->status = 'cancelled_no_refund';
        }
        $booking->save();

        // Send cancellation emails
        Mail::to($booking->email)->send(new BL7CancellationConfirmation($booking));
        Mail::to(config('mail.parking_company_email'))->send(new BL8ParkingCompanyCancel($booking));
        Mail::to(config('mail.admin_email'))->send(new BL9AdminCancellationNotification($booking));

        session()->forget('cancellation_booking_id');

        return redirect()->route('cancellation.done')->with('cancelled_booking', $booking->booking_id);
    }

    public function done()
    {
        return view('cancellation.done');
    }
}
