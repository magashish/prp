<?php
namespace App\Mail;
use App\Models\Booking;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\{Content, Envelope};
use Illuminate\Queue\SerializesModels;
class BL7CancellationConfirmation extends Mailable {
    use SerializesModels;
    public function __construct(public Booking $booking) {}
    public function envelope(): Envelope { return new Envelope(subject: 'Booking Cancellation Confirmed – ' . $booking->booking_id); }
    public function content(): Content { return new Content(markdown: 'emails.bl7'); }
    public function attachments(): array { return []; }
}
