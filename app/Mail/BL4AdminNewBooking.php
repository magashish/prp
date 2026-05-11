<?php
namespace App\Mail;
use App\Models\Booking;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\{Content, Envelope};
use Illuminate\Queue\SerializesModels;
class BL4AdminNewBooking extends Mailable {
    use SerializesModels;
    public function __construct(public Booking $booking) {}
    public function envelope(): Envelope { return new Envelope(subject: '[Admin] New Booking Received – ' . $booking->booking_id); }
    public function content(): Content { return new Content(view: 'emails.bl4'); }
    public function attachments(): array { return []; }
}
