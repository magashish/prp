<?php
namespace App\Mail;
use App\Models\Booking;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\{Content, Envelope};
use Illuminate\Queue\SerializesModels;
class BL5ParkingPass extends Mailable {
    use SerializesModels;
    public function __construct(public Booking $booking) {}
    public function envelope(): Envelope { return new Envelope(subject: 'Your Parking Pass – Access Code Inside'); }
    public function content(): Content { return new Content(markdown: 'emails.bl5'); }
    public function attachments(): array { return []; }
}
