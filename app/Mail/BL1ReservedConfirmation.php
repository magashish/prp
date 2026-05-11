<?php
namespace App\Mail;
use App\Models\Booking;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\{Content, Envelope};
use Illuminate\Queue\SerializesModels;
class BL1ReservedConfirmation extends Mailable {
    use SerializesModels;
    public function __construct(public Booking $booking) {}
    public function envelope(): Envelope { return new Envelope(subject: 'Your Reserved Parking Stall – Booking Confirmed'); }
    public function content(): Content { return new Content(markdown: 'emails.bl1'); }
    public function attachments(): array { return []; }
}
