<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_id', 20)->unique();
            $table->enum('stall_type', ['reserved', 'non_reserved']);
            $table->tinyInteger('stall_number')->nullable();
            $table->date('check_in_date');
            $table->date('check_out_date');
            $table->string('full_name');
            $table->string('phone_number');
            $table->string('email');
            $table->boolean('refund_plan')->default(false);
            $table->enum('status', ['active', 'cancelled_with_refund', 'cancelled_no_refund'])->default('active');
            $table->enum('pass_status', ['required', 'sent', 'not_required'])->default('not_required');
            $table->string('parking_code')->nullable();
            $table->decimal('subtotal', 8, 2);
            $table->decimal('tax', 8, 2);
            $table->decimal('service_fee', 8, 2);
            $table->decimal('total_amount', 8, 2);
            $table->string('paypal_transaction_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
