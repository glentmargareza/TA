<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->string('transaction_ref', 100)->nullable();
            $table->enum('payment_stage', ['DOWN_PAYMENT', 'SETTLEMENT', 'FULL_PAYMENT']);
            $table->string('payment_type', 50)->nullable();
            $table->decimal('gross_amount', 12, 2);
            $table->enum('payment_status', ['PENDING', 'SETTLED', 'EXPIRED', 'FAILED'])->default('PENDING');
            $table->string('snap_token', 255)->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
