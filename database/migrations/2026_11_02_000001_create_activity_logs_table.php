<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('type', 50)->index(); // BOOKING_NEW, PAYMENT_SUCCESS, BOOKING_CANCELLED, SCHEDULE_FULL, ADMIN_ACTION
            $table->string('title');
            $table->text('message');
            $table->string('icon_type', 30)->default('admin'); // booking, payment, cancel, schedule, admin
            $table->string('link')->nullable();
            $table->boolean('is_read')->default(false)->index();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};

