<?php
namespace App\Mail;
use App\Models\Booking;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\{Content, Envelope};
use Illuminate\Queue\SerializesModels;
class BL3ParkingCompanyNewBooking extends Mailable {
    use SerializesModels;
    public function __construct(public Booking $booking) {}
    public function envelope(): Envelope { return new Envelope(subject: 'New Parking Booking – Action Required'); }
    public function content(): Content { return new Content(view: 'emails.bl3'); }
    public function attachments(): array { return []; }
}
