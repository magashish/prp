<?php
namespace App\Mail;
use App\Models\Booking;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\{Content, Envelope};
use Illuminate\Queue\SerializesModels;
class BL6BookingReminder extends Mailable {
    use SerializesModels;
    public function __construct(public Booking $booking) {}
    public function envelope(): Envelope { return new Envelope(subject: 'Reminder: Your Parking Booking is Coming Up'); }
    public function content(): Content { return new Content(markdown: 'emails.bl6'); }
    public function attachments(): array { return []; }
}
