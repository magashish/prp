<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Mail\BL7CancellationConfirmation;
use App\Mail\BL8ParkingCompanyCancel;
use App\Mail\BL9AdminCancellationNotification;
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
            'stall_type'    => 'required|in:reserved,non_reserved',
            'email'         => 'required|email',
        ]);

        $booking = Booking::where('status', 'active')
            ->where('email', $request->email)
            ->where('stall_type', $request->stall_type)
            ->where('check_in_date', $request->check_in_date)
            ->where('check_out_date', $request->check_out_date)
            ->where(function ($q) use ($request) {
                $q->where('booking_id', $request->identifier)
                  ->orWhere('full_name', 'like', '%' . $request->identifier . '%');
            })
            ->first();

        if (!$booking) {
            return back()->withErrors([
                'identifier' => 'We could not find a booking with the information provided.',
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
