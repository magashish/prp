<?php
namespace App\Mail;
use App\Models\Booking;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\{Content, Envelope};
use Illuminate\Queue\SerializesModels;
class BL3ParkingCompanyNewBooking extends Mailable {
    use SerializesModels;
    public function __construct(public Booking $booking) {}
    public function envelope(): Envelope { return new Envelope(subject: 'New BL Rentals Parking Reservation'); }
    public function content(): Content { return new Content(markdown: 'emails.bl3'); }
    public function attachments(): array { return []; }
}
