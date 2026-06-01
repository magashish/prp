<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    const RESERVED_PRICE     = 45.00;
    const NON_RESERVED_PRICE = 35.00;
    const REFUND_PLAN_PRICE  = 25.00;
    const TAX_RATE           = 0.045;
    const SERVICE_FEE_RATE   = 0.03;
    const RESERVED_CAPACITY  = 2;
    const NON_RESERVED_CAP   = 75;

    public function index()
    {
        return view('home');
    }

    public function checkAvailability(Request $request)
    {
        $request->validate([
            'stall_type'    => 'required|in:reserved,non_reserved',
            'check_in_date' => 'required|date',
            'check_out_date'=> 'required|date|after:check_in_date',
        ]);

        $tz         = config('app.timezone');
        $stallType  = $request->stall_type;
        $checkIn    = Carbon::createFromFormat('Y-m-d', $request->check_in_date, $tz)->startOfDay();
        $checkOut   = Carbon::createFromFormat('Y-m-d', $request->check_out_date, $tz)->startOfDay();
        $now        = Carbon::now($tz);

        // Validate booking rules
        $ruleError = $this->validateBookingRules($checkIn, $checkOut, $now);
        if ($ruleError) {
            return response()->json(['available' => false, 'message' => $ruleError]);
        }

        if ($stallType === 'reserved') {
            $stallNumber = $this->findAvailableReservedStall($checkIn, $checkOut);
            if (!$stallNumber) {
                return response()->json([
                    'available' => false,
                    'message'   => 'Both reserved stalls are unavailable for the selected dates.',
                ]);
            }
            $days     = $checkIn->diffInDays($checkOut) + 1;
            $subtotal = $days * self::RESERVED_PRICE;
        } else {
            if (!$this->nonReservedAvailable($checkIn, $checkOut)) {
                return response()->json([
                    'available' => false,
                    'message'   => 'Non-reserved stalls are fully booked for the selected dates.',
                ]);
            }
            $stallNumber = null;
            $days        = $checkIn->diffInDays($checkOut) + 1;
            $subtotal    = $days * self::NON_RESERVED_PRICE;
        }

        $tax        = round($subtotal * self::TAX_RATE, 2);
        $serviceFee = round($subtotal * self::SERVICE_FEE_RATE, 2);
        $total      = $subtotal + $tax + $serviceFee;

        // Store in session for checkout
        session([
            'booking_pending' => [
                'stall_type'    => $stallType,
                'stall_number'  => $stallNumber,
                'check_in_date' => $checkIn->toDateString(),
                'check_out_date'=> $checkOut->toDateString(),
                'days'          => $days,
                'subtotal'      => $subtotal,
                'tax'           => $tax,
                'service_fee'   => $serviceFee,
                'total'         => $total,
            ]
        ]);

        return response()->json([
            'available'   => true,
            'stall_type'  => $stallType,
            'stall_number'=> $stallNumber,
            'days'        => $days,
            'subtotal'    => number_format($subtotal, 2),
            'tax'         => number_format($tax, 2),
            'service_fee' => number_format($serviceFee, 2),
            'total'       => number_format($total, 2),
            'refund_plan' => number_format(self::REFUND_PLAN_PRICE, 2),
        ]);
    }

    public function checkout()
    {
        $pending = session('booking_pending');
        if (!$pending) {
            return redirect()->route('home')->with('error', 'Please check availability first.');
        }

        return view('booking.checkout', compact('pending'));
    }

    public function paymentPage()
    {
        $pending = session('booking_pending');
        if (!$pending || empty($pending['email'])) {
            return redirect()->route('home')->with('error', 'Session expired. Please start again.');
        }

        $mode     = config('paypal.mode');
        $clientId = config("paypal.{$mode}.client_id");

        return view('booking.payment', compact('pending', 'clientId'));
    }

    public function storeSession(Request $request)
    {
        $request->validate([
            'full_name'   => 'required|string|max:255',
            'phone_number'=> 'required|string|max:30',
            'email'       => 'required|email',
            'refund_plan' => 'nullable|boolean',
            'terms'       => 'accepted',
        ]);

        $pending = session('booking_pending');
        if (!$pending) {
            return redirect()->route('home')->with('error', 'Session expired. Please start again.');
        }

        $refundPlan  = $request->boolean('refund_plan');
        $refundCost  = $refundPlan ? self::REFUND_PLAN_PRICE : 0;
        $total       = $pending['subtotal'] + $pending['tax'] + $pending['service_fee'] + $refundCost;

        session()->put('booking_pending.full_name',    $request->full_name);
        session()->put('booking_pending.phone_number', $request->phone_number);
        session()->put('booking_pending.email',        $request->email);
        session()->put('booking_pending.refund_plan',  $refundPlan);
        session()->put('booking_pending.refund_cost',  $refundCost);
        session()->put('booking_pending.total',        $total);

        return redirect()->route('booking.payment');
    }

    public function confirmation(Booking $booking)
    {
        return view('booking.confirmation', compact('booking'));
    }

    // ─── Availability Helpers ─────────────────────────────────────

    private function findAvailableReservedStall(Carbon $checkIn, Carbon $checkOut): ?int
    {
        for ($stall = 1; $stall <= self::RESERVED_CAPACITY; $stall++) {
            $conflict = Booking::where('stall_type', 'reserved')
                ->where('stall_number', $stall)
                ->where('status', 'active')
                ->where(function ($q) use ($checkIn, $checkOut) {
                    $q->where('check_in_date', '<=', $checkOut->toDateString())
                      ->where('check_out_date', '>=', $checkIn->toDateString());
                })->exists();

            if (!$conflict) {
                return $stall;
            }
        }

        return null;
    }

    private function nonReservedAvailable(Carbon $checkIn, Carbon $checkOut): bool
    {
        $cursor = $checkIn->copy();
        while ($cursor->lte($checkOut)) {
            $count = Booking::where('stall_type', 'non_reserved')
                ->where('status', 'active')
                ->where('check_in_date', '<=', $cursor->toDateString())
                ->where('check_out_date', '>=', $cursor->toDateString())
                ->count();

            if ($count >= self::NON_RESERVED_CAP) {
                return false;
            }
            $cursor->addDay();
        }

        return true;
    }

    // ─── Business Rule Validation ─────────────────────────────────

    private function validateBookingRules(Carbon $checkIn, Carbon $checkOut, Carbon $now): ?string
    {
        $todayStr    = $now->toDateString();
        $tomorrowStr = $now->copy()->addDay()->toDateString();
        $checkInStr  = $checkIn->toDateString();
        $dow         = $now->dayOfWeek; // Carbon: 0=Sun,1=Mon,...,5=Fri,6=Sat

        // No same-day booking
        if ($checkInStr <= $todayStr) {
            return 'Same-day booking is not allowed. Please select a future date.';
        }

        // No Sunday booking on Saturday (checked before next-day rule to show the right message)
        if ($dow === Carbon::SATURDAY) {
            $upcomingSun = $now->copy()->next(Carbon::SUNDAY)->toDateString();
            if ($checkInStr === $upcomingSun) {
                return 'Sunday booking is not available on Saturday.';
            }
        }

        // No weekend booking after Friday 12:00 PM (blocks upcoming Saturday and Sunday only)
        if ($dow === Carbon::FRIDAY && $now->hour >= 12) {
            $upcomingSat = $now->copy()->next(Carbon::SATURDAY)->toDateString();
            $upcomingSun = $now->copy()->next(Carbon::SUNDAY)->toDateString();
            if ($checkInStr === $upcomingSat || $checkInStr === $upcomingSun) {
                return 'Weekend booking is closed after Friday 12:00 PM.';
            }
        }

        // No next-day booking after 4:00 PM (system time)
        if ($checkInStr === $tomorrowStr && $now->hour >= 16) {
            return 'Next-day booking is closed after 4:00 PM.';
        }

        return null;
    }
}