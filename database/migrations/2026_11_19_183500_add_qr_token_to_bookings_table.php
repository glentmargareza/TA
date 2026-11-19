<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('bookings', 'qr_token')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->string('qr_token', 64)->nullable()->unique()->after('booking_code');
            });

            // Backfill existing bookings with unique tokens
            $bookings = DB::table('bookings')->whereNull('qr_token')->get();
            foreach ($bookings as $b) {
                DB::table('bookings')
                    ->where('id', $b->id)
                    ->update([
                        'qr_token' => 'WKR-QR-' . strtoupper(Str::random(20)) . '-' . $b->id,
                    ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('bookings', 'qr_token')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->dropColumn('qr_token');
            });
        }
    }
};
