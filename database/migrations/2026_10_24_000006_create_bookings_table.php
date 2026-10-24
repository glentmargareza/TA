<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code', 50)->unique();
            $table->string('customer_name', 100);
            $table->string('customer_phone', 20);
            $table->string('customer_email', 100)->nullable();
            
            $table->foreignId('package_id')->constrained('packages')->onDelete('restrict');
            $table->foreignId('slot_id')->constrained('schedule_slots')->onDelete('restrict');
            $table->date('trip_date');
            $table->integer('jeep_quantity')->default(1);
            $table->integer('passenger_count');
            
            $table->enum('arrival_type', ['self_drive', 'pickup'])->default('self_drive');
            $table->foreignId('pickup_zone_id')->nullable()->constrained('pickup_zones')->nullOnDelete();
            $table->text('pickup_address')->nullable();
            
            $table->decimal('package_subtotal', 12, 2);
            $table->decimal('pickup_fee', 12, 2)->default(0.00);
            $table->decimal('total_amount', 12, 2);
            $table->decimal('paid_amount', 12, 2)->default(0.00);
            $table->decimal('remaining_amount', 12, 2)->default(0.00);
            
            $table->enum('payment_mode', ['DP', 'FULL'])->default('FULL');
            $table->enum('booking_status', ['PENDING', 'DP_PAID', 'FULL_PAID', 'CANCELLED', 'COMPLETED'])->default('PENDING');
            $table->dateTime('lock_expires_at')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
