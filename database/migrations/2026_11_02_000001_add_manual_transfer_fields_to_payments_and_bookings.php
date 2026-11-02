<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Perluas opsi ENUM pada tabel bookings untuk mendukung status peninjauan bukti transfer
        DB::statement("ALTER TABLE bookings MODIFY COLUMN booking_status ENUM('PENDING', 'WAITING_CONFIRMATION', 'DP_PAID', 'FULL_PAID', 'CANCELLED', 'COMPLETED') NOT NULL DEFAULT 'PENDING'");

        // 2. Tambahkan kolom penampung bukti transfer privat & metadata pengirim pada tabel payments
        Schema::table('payments', function (Blueprint $table) {
            $table->string('proof_image', 255)->nullable()->after('gross_amount');
            $table->string('sender_bank', 50)->nullable()->after('proof_image');
            $table->string('sender_name', 100)->nullable()->after('sender_bank');
            $table->text('rejection_reason')->nullable()->after('sender_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['proof_image', 'sender_bank', 'sender_name', 'rejection_reason']);
        });

        DB::statement("ALTER TABLE bookings MODIFY COLUMN booking_status ENUM('PENDING', 'DP_PAID', 'FULL_PAID', 'CANCELLED', 'COMPLETED') NOT NULL DEFAULT 'PENDING'");
    }
};

