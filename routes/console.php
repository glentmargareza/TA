<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\BookingAssignment;
use App\Models\Jeep;
use App\Models\ActivityLog;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('bookings:clear', function () {
    $this->info("Menghitung data booking sebelum pembersihan...");
    $bookingCount = Booking::count();
    $paymentCount = Payment::count();
    $assignmentCount = BookingAssignment::count();

    $this->info("Bookings: {$bookingCount}");
    $this->info("Payments: {$paymentCount}");
    $this->info("Assignments: {$assignmentCount}");

    DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    BookingAssignment::truncate();
    Payment::truncate();
    Booking::truncate();
    DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    // Reset status armada jeep kembali ke READY jika sedang ON_TRIP
    Jeep::where('status', 'ON_TRIP')->update(['status' => 'READY']);

    // Hapus semua activity logs & notifikasi
    ActivityLog::truncate();

    // Hapus file upload bukti transfer yang tersimpan
    $proofFiles = glob(public_path('uploads/proofs/*'));
    if ($proofFiles) {
        foreach ($proofFiles as $file) {
            if (is_file($file)) {
                @unlink($file);
            }
        }
    }

    $this->info("Berhasil! Seluruh data booking ({$bookingCount} data) telah dihapus bersih dan ID telah di-reset.");
})->purpose('Menghapus semua data booking, pembayaran, dan penugasan armada');

