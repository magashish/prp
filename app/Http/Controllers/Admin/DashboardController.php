<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $today        = Carbon::today();
        $totalActive  = Booking::where('status', 'active')->whereDate('check_out_date', '>=', $today)->count();
        $reservedCount= Booking::where('stall_type', 'reserved')->where('status', 'active')->whereDate('check_out_date', '>=', $today)->count();
        $nonResCount  = Booking::where('stall_type', 'non_reserved')->where('status', 'active')->whereDate('check_out_date', '>=', $today)->count();
        $pendingPass    = Booking::where('pass_status', 'required')->where('status', 'active')->whereDate('check_out_date', '>=', $today)->count();
        $cancelledCount = Booking::whereIn('status', ['cancelled_with_refund', 'cancelled_no_refund'])->count();
        $recentBookings = Booking::where('status', 'active')->whereDate('check_out_date', '>=', $today)->latest()->take(10)->get();

        return view('admin.dashboard', compact('totalActive', 'reservedCount', 'nonResCount', 'pendingPass', 'cancelledCount', 'recentBookings'));
    }

    public function calendar()
    {
        return view('admin.calendar');
    }

    public function calendarData(Request $request)
    {
        $month = $request->input('month', Carbon::now()->month);
        $year  = $request->input('year', Carbon::now()->year);

        $startOfMonth = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endOfMonth   = $startOfMonth->copy()->endOfMonth();

        $bookings = Booking::where('status', 'active')
            ->where('check_in_date', '<=', $endOfMonth->toDateString())
            ->where('check_out_date', '>=', $startOfMonth->toDateString())
            ->get(['stall_type', 'stall_number', 'full_name', 'check_in_date', 'check_out_date', 'booking_id']);

        $days = [];
        $cursor = $startOfMonth->copy();
        while ($cursor->lte($endOfMonth)) {
            $dateStr = $cursor->toDateString();
            $stall1  = null;
            $stall2  = null;
            $openCount = 0;

            foreach ($bookings as $b) {
                if ($b->check_in_date->toDateString() <= $dateStr && $b->check_out_date->toDateString() >= $dateStr) {
                    if ($b->stall_type === 'reserved') {
                        if ($b->stall_number == 1) $stall1 = $b->full_name;
                        if ($b->stall_number == 2) $stall2 = $b->full_name;
                    } else {
                        $openCount++;
                    }
                }
            }

            $days[$dateStr] = [
                'stall1'      => $stall1,
                'stall2'      => $stall2,
                'open_booked' => $openCount,
            ];

            $cursor->addDay();
        }

        return response()->json(['days' => $days, 'month' => $month, 'year' => $year]);
    }
}
