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
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SquareController extends Controller
{
    private function baseUrl(): string
    {
        return config('square.environment') === 'production'
            ? 'https://connect.squareup.com'
            : 'https://connect.squareupsandbox.com';
    }

    private function http()
    {
        return Http::withToken(config('square.access_token'))
            ->withHeaders(['Square-Version' => '2024-11-20'])
            ->acceptJson();
    }

    // ─── Charge endpoint ─────────────────────────────────────────────

    public function charge(Request $request)
    {
        $request->validate(['source_id' => 'required|string']);

        $pending = session('booking_pending');
        if (!$pending || empty($pending['email'])) {
            return response()->json(['success' => false, 'message' => 'Session expired.'], 400);
        }

        $amountCents = (int) round($pending['total'] * 100);

        $response = $this->http()->post($this->baseUrl() . '/v2/payments', [
            'source_id'      => $request->input('source_id'),
            'idempotency_key'=> Str::uuid()->toString(),
            'amount_money'   => ['amount' => $amountCents, 'currency' => 'USD'],
            'location_id'    => config('square.location_id'),
            'note'           => ucwords(str_replace('_', ' ', $pending['stall_type']))
                                . ' Stall – ' . $pending['days'] . ' day(s) | Check-in: ' . $pending['check_in_date'],
        ]);

        if (!$response->successful() || !empty($response->json('errors'))) {
            $errors = $response->json('errors');
            $msg = $errors ? $errors[0]['detail'] ?? $errors[0]['code'] : 'Payment failed. Please try again.';
            return response()->json(['success' => false, 'message' => $msg], 400);
        }

        $paymentId = $response->json('payment.id');

        $booking = $this->saveBooking($pending, $paymentId);

        if (!$booking) {
            $this->refund($paymentId, $pending['total'], 'Stall no longer available. Full refund issued.');
            session()->forget('booking_pending');
            return response()->json([
                'success' => false,
                'message' => 'Sorry, that stall was just taken by another booking. A full refund has been issued.',
            ], 409);
        }

        session()->forget('booking_pending');
        return response()->json([
            'success'  => true,
            'redirect' => route('booking.confirmation', $booking),
        ]);
    }

    // ─── Shared booking save ──────────────────────────────────────────

    private function saveBooking(array $pending, string $paymentId): ?Booking
    {
        return DB::transaction(function () use ($pending, $paymentId) {
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

                if ($conflict) return null;
            } else {
                $cursor = $checkIn->copy();
                while ($cursor->lte($checkOut)) {
                    $count = Booking::where('stall_type', 'non_reserved')
                        ->where('status', 'active')
                        ->where('check_in_date', '<=', $cursor->toDateString())
                        ->where('check_out_date', '>=', $cursor->toDateString())
                        ->lockForUpdate()
                        ->count();

                    if ($count >= 75) return null;
                    $cursor->addDay();
                }
            }

            $booking = Booking::create([
                'booking_id'        => Booking::generateBookingId(),
                'stall_type'        => $pending['stall_type'],
                'stall_number'      => $pending['stall_number'],
                'check_in_date'     => $pending['check_in_date'],
                'check_out_date'    => $pending['check_out_date'],
                'full_name'         => $pending['full_name'],
                'phone_number'      => $pending['phone_number'],
                'email'             => $pending['email'],
                'refund_plan'       => $pending['refund_plan'],
                'status'            => 'active',
                'pass_status'       => $pending['stall_type'] === 'reserved' ? 'not_required' : 'required',
                'subtotal'          => $pending['subtotal'],
                'tax'               => $pending['tax'],
                'service_fee'       => $pending['service_fee'],
                'total_amount'      => $pending['total'],
                'square_payment_id' => $paymentId,
            ]);

            if ($booking->stall_type === 'reserved') {
                Mail::to($booking->email)->send(new BL1ReservedConfirmation($booking));
                Mail::to(config('mail.admin_email'))->send(new BL4AdminNewBooking($booking));
            } else {
                Mail::to($booking->email)->send(new BL2NonReservedConfirmation($booking));
                Mail::to(config('mail.parking_company_email'))->send(new BL3ParkingCompanyNewBooking($booking));
                Mail::to(config('mail.admin_email'))->send(new BL4AdminNewBooking($booking));
            }

            return $booking;
        });
    }

    private function refund(string $paymentId, float $total, string $reason): void
    {
        $this->http()->post($this->baseUrl() . '/v2/refunds', [
            'idempotency_key' => Str::uuid()->toString(),
            'payment_id'      => $paymentId,
            'amount_money'    => ['amount' => (int) round($total * 100), 'currency' => 'USD'],
            'reason'          => $reason,
        ]);
    }
}
