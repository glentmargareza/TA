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
        Schema::table('payments', function (Blueprint $table) {
            // 1. Identifikasi Unik Transaksi Core API
            $table->string('order_id', 100)->nullable()->unique()->after('booking_id');
            $table->string('payment_method', 50)->nullable()->after('payment_type');

            // 2. Data Bank Transfer / Virtual Account (BCA, BNI, BRI) & Mandiri Bill
            $table->string('va_number', 50)->nullable()->after('payment_method');
            $table->string('biller_code', 50)->nullable()->after('va_number');
            $table->string('bill_key', 50)->nullable()->after('biller_code');

            // 3. Data QRIS & E-Wallet (GoPay, ShopeePay)
            $table->text('qr_url')->nullable()->after('bill_key');
            $table->text('qr_string')->nullable()->after('qr_url');
            $table->text('deeplink_url')->nullable()->after('qr_string');

            // 4. Batas Waktu & Payload Lengkap Audit Trail
            $table->dateTime('expires_at')->nullable()->after('deeplink_url');
            $table->json('payload_response')->nullable()->after('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn([
                'order_id',
                'payment_method',
                'va_number',
                'biller_code',
                'bill_key',
                'qr_url',
                'qr_string',
                'deeplink_url',
                'expires_at',
                'payload_response',
            ]);
        });
    }
};
