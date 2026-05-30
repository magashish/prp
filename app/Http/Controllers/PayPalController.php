<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Mail\BL1ReservedConfirmation;
use App\Mail\BL2NonReservedConfirmation;
use App\Mail\BL3ParkingCompanyNewBooking;
use App\Mail\BL4AdminNewBooking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PayPalController extends Controller
{
    // ─── Inline payment page ──────────────────────────────────────────

    public function createOrder()
    {
        $pending = session('booking_pending');
        if (!$pending || empty($pending['email'])) {
            return response()->json(['error' => 'Session expired.'], 400);
        }

        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->setAccessToken($provider->getAccessToken());

        $total       = number_format($pending['total'], 2, '.', '');
        $description = ucwords(str_replace('_', ' ', $pending['stall_type'])) . ' Stall - '
            . $pending['days'] . ' day(s) | Check-in: ' . $pending['check_in_date'];

        $order = $provider->createOrder([
            'intent' => 'CAPTURE',
            'application_context' => [
                'return_url' => route('paypal.success'),
                'cancel_url' => route('paypal.cancel'),
            ],
            'purchase_units' => [[
                'description' => $description,
                'amount'      => [
                    'currency_code' => 'USD',
                    'value'         => $total,
                ],
            ]],
        ]);

        if (isset($order['id']) && $order['status'] === 'CREATED') {
            session(['paypal_order_id' => $order['id']]);
            return response()->json(['id' => $order['id']]);
        }

        return response()->json(['error' => 'Unable to create PayPal order.'], 500);
    }

    public function captureInline(Request $request)
    {
        $orderId = $request->input('order_id');
        $pending = session('booking_pending');

        if (!$pending || !$orderId) {
            return response()->json(['success' => false, 'message' => 'Session expired.'], 400);
        }

        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->setAccessToken($provider->getAccessToken());

        $result = $provider->capturePaymentOrder($orderId);

        if (isset($result['status']) && $result['status'] === 'COMPLETED') {
            $transactionId = $result['purchase_units'][0]['payments']['captures'][0]['id'] ?? null;
            $booking = $this->saveBooking($pending, $transactionId, $provider);

            if (!$booking) {
                session()->forget(['booking_pending', 'paypal_order_id']);
                return response()->json([
                    'success' => false,
                    'message' => 'Sorry, that stall was just taken by another booking. A full refund has been issued.',
                ], 409);
            }

            session()->forget(['booking_pending', 'paypal_order_id']);
            return response()->json([
                'success'  => true,
                'redirect' => route('booking.confirmation', $booking),
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Payment was not completed. Please try again.'], 400);
    }

    // ─── PayPal redirect flow (return_url fallback) ───────────────────

    public function redirect()
    {
        $pending = session('booking_pending');
        if (!$pending || empty($pending['email'])) {
            return redirect()->route('home')->with('error', 'Session expired. Please start again.');
        }

        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->setAccessToken($provider->getAccessToken());

        $total       = number_format($pending['total'], 2, '.', '');
        $description = ucwords(str_replace('_', ' ', $pending['stall_type'])) . ' Stall - '
            . $pending['days'] . ' day(s) | Check-in: ' . $pending['check_in_date'];

        $order = $provider->createOrder([
            'intent' => 'CAPTURE',
            'application_context' => [
                'return_url' => route('paypal.success'),
                'cancel_url' => route('paypal.cancel'),
            ],
            'purchase_units' => [[
                'description' => $description,
                'amount'      => [
                    'currency_code' => 'USD',
                    'value'         => $total,
                ],
            ]],
        ]);

        if (isset($order['id']) && $order['status'] === 'CREATED') {
            foreach ($order['links'] as $link) {
                if ($link['rel'] === 'approve') {
                    session(['paypal_order_id' => $order['id']]);
                    return redirect()->away($link['href']);
                }
            }
        }

        return redirect()->route('home')->with('error', 'Unable to initiate PayPal payment. Please try again.');
    }

    public function success(Request $request)
    {
        $pending = session('booking_pending');
        $orderId = session('paypal_order_id');

        if (!$pending || !$orderId) {
            return redirect()->route('home')->with('error', 'Session expired.');
        }

        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->setAccessToken($provider->getAccessToken());

        $result = $provider->capturePaymentOrder($orderId);

        if (isset($result['status']) && $result['status'] === 'COMPLETED') {
            $transactionId = $result['purchase_units'][0]['payments']['captures'][0]['id'] ?? null;
            $booking = $this->saveBooking($pending, $transactionId, $provider);

            if (!$booking) {
                session()->forget(['booking_pending', 'paypal_order_id']);
                return redirect()->route('home')->with('error',
                    'Sorry, that stall was just taken by another booking while you were paying. A full refund has been issued to your PayPal account.');
            }

            session()->forget(['booking_pending', 'paypal_order_id']);
            return redirect()->route('booking.confirmation', $booking);
        }

        return redirect()->route('home')->with('error', 'Payment was not completed. Please try again.');
    }

    public function cancel()
    {
        return redirect()->route('booking.payment')->with('warning', 'Payment was cancelled. You can try again.');


    // ─── Shared booking save + emails ─────────────────────────────────

    private function saveBooking(array $pending, ?string $transactionId, PayPalClient $provider): ?Booking
    {
        $booking = DB::transaction(function () use ($pending, $transactionId, $provider) {
            $checkIn  = Carbon::parse($pending['check_in_date']);
            $checkOut = Carbon::parse($pending['check_out_date']);

            if ($pending['stall_type'] === 'reserved') {
                $conflict = Booking::where('stall_type', 'reserved')
                    ->where('stall_number', $pending['stall_number'])
                    ->where('status', 'active')
                    ->where('check_in_date', '<=', $checkOut->toDateString())
                    ->where('check_out_date', '>=', $checkIn->toDateString())
                    ->lockForUpdate()
                    ->exists();

                if ($conflict) {
                    $provider->refundCapturedPayment($transactionId, [
                        'amount'        => ['value' => number_format($pending['total'], 2, '.', ''), 'currency_code' => 'USD'],
                        'note_to_payer' => 'Stall no longer available. Full refund issued.',
                    ]);
                    return null;
                }
            } else {
                $cursor = $checkIn->copy();
                while ($cursor->lte($checkOut)) {
                    $count = Booking::where('stall_type', 'non_reserved')
                        ->where('status', 'active')
                        ->where('check_in_date', '<=', $cursor->toDateString())
                        ->where('check_out_date', '>=', $cursor->toDateString())
                        ->lockForUpdate()
                        ->count();

                    if ($count >= 75) {
                        $provider->refundCapturedPayment($transactionId, [
                            'amount'        => ['value' => number_format($pending['total'], 2, '.', ''), 'currency_code' => 'USD'],
                            'note_to_payer' => 'Parking full. Full refund issued.',
                        ]);
                        return null;
                    }
                    $cursor->addDay();
                }
            }

            return Booking::create([
                'booking_id'            => Booking::generateBookingId(),
                'stall_type'            => $pending['stall_type'],
                'stall_number'          => $pending['stall_number'],
                'check_in_date'         => $pending['check_in_date'],
                'check_out_date'        => $pending['check_out_date'],
                'full_name'             => $pending['full_name'],
                'phone_number'          => $pending['phone_number'],
                'email'                 => $pending['email'],
                'refund_plan'           => $pending['refund_plan'],
                'status'                => 'active',
                'pass_status'           => $pending['stall_type'] === 'reserved' ? 'not_required' : 'required',
                'subtotal'              => $pending['subtotal'],
                'tax'                   => $pending['tax'],
                'service_fee'           => $pending['service_fee'],
                'total_amount'          => $pending['total'],
                'paypal_transaction_id' => $transactionId,
            ]);
        });

        if (!$booking) {
            return null;
        }

        if ($booking->stall_type === 'reserved') {
            Mail::to($booking->email)->send(new BL1ReservedConfirmation($booking));
            Mail::to(config('mail.admin_email'))->send(new BL4AdminNewBooking($booking));
        } else {
            Mail::to($booking->email)->send(new BL2NonReservedConfirmation($booking));
            Mail::to(config('mail.parking_company_email'))->send(new BL3ParkingCompanyNewBooking($booking));
            Mail::to(config('mail.admin_email'))->send(new BL4AdminNewBooking($booking));
        }

        return $booking;
    }
}