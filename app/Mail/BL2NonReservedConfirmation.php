<?php
namespace App\Mail;
use App\Models\Booking;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\{Content, Envelope};
use Illuminate\Queue\SerializesModels;
class BL2NonReservedConfirmation extends Mailable {
    use SerializesModels;
    public function __construct(public Booking $booking) {}
    public function envelope(): Envelope { return new Envelope(subject: 'Parking Booking Confirmed – Parking Pass Coming Soon'); }
    public function content(): Content { return new Content(markdown: 'emails.bl2'); }
    public function attachments(): array { return []; }
}
