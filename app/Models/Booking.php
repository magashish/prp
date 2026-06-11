<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'booking_id',
        'stall_type',
        'stall_number',
        'check_in_date',
        'check_out_date',
        'full_name',
        'phone_number',
        'email',
        'refund_plan',
        'status',
        'pass_status',
        'parking_code',
        'subtotal',
        'tax',
        'service_fee',
        'total_amount',
        'paypal_transaction_id',
        'notes',
    ];

    protected $casts = [
        'check_in_date'  => 'date',
        'check_out_date' => 'date',
        'refund_plan'    => 'boolean',
        'subtotal'       => 'decimal:2',
        'tax'            => 'decimal:2',
        'service_fee'    => 'decimal:2',
        'total_amount'   => 'decimal:2',
    ];

    public static function generateBookingId(): string
    {
        do {
            $id = 'BL-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 4));
        } while (self::where('booking_id', $id)->exists());

        return $id;
    }

    public function isPast(): bool
    {
        return $this->check_out_date->isPast();
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
