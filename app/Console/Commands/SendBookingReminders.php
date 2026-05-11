<?php

namespace App\Console\Commands;

use App\Mail\BL6BookingReminder;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendBookingReminders extends Command
{
    protected $signature = 'bookings:send-reminders';
    protected $description = 'Send reminder emails (BL6) for bookings checking in tomorrow';

    public function handle(): void
    {
        $tomorrow = Carbon::tomorrow()->toDateString();

        $bookings = Booking::where('status', 'active')
            ->where('check_in_date', $tomorrow)
            ->get();

        foreach ($bookings as $booking) {
            Mail::to($booking->email)->send(new BL6BookingReminder($booking));
            $this->info("Reminder sent to {$booking->email} for booking {$booking->booking_id}");
        }

        $this->info("Done. Sent {$bookings->count()} reminder(s).");
    }
}
