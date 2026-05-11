<?php
namespace App\Mail;
use App\Models\Booking;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\{Content, Envelope};
use Illuminate\Queue\SerializesModels;
class BL8ParkingCompanyCancel extends Mailable {
    use SerializesModels;
    public function __construct(public Booking $booking) {}
    public function envelope(): Envelope { return new Envelope(subject: 'Booking Cancelled – ' . $this->booking->booking_id); }
    public function content(): Content { return new Content(markdown: 'emails.bl8'); }
    public function attachments(): array { return []; }
}
