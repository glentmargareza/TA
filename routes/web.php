<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PaymentWebhookController;
use App\Http\Controllers\AuthController;

// 1. Rute Publik Wisatawan
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/packages', [HomeController::class, 'packagesIndex'])->name('packages.index');
Route::get('/paket-wisata', [HomeController::class, 'packagesIndex'])->name('packages.alias');
Route::get('/packages/{slug}', [HomeController::class, 'showPackage'])->name('packages.show');
Route::get('/paket-wisata/{slug}', [HomeController::class, 'showPackage'])->name('packages.show.alias');

// 2. Alur Pemesanan (Booking)
Route::get('/booking', [BookingController::class, 'create'])->name('booking.create');
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
Route::get('/booking/{code}/success', [BookingController::class, 'success'])->name('booking.success');

// 3. Pelacakan Tiket Mandiri
Route::get('/cek-tiket', [TicketController::class, 'check'])->name('ticket.check');

// 4. Portal Admin & Loket Basecamp
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::post('/bookings/{id}/settle', [AdminController::class, 'settleDp'])->name('settle');
    Route::post('/bookings/{id}/assign', [AdminController::class, 'assignDriver'])->name('assign');

    // Laporan Penjualan
    Route::get('/laporan', [AdminController::class, 'reports'])->name('reports');
    Route::get('/laporan/export-csv', [AdminController::class, 'exportReportsCsv'])->name('reports.csv');

    // Kelola Paket Wisata
    Route::get('/paket', [AdminController::class, 'packages'])->name('packages');
    Route::post('/paket', [AdminController::class, 'storePackage'])->name('packages.store');
    Route::put('/paket/{id}', [AdminController::class, 'updatePackage'])->name('packages.update');
    Route::post('/paket/{id}/toggle', [AdminController::class, 'togglePackage'])->name('packages.toggle');
    Route::delete('/paket/{id}', [AdminController::class, 'destroyPackage'])->name('packages.destroy');

    // Kelola Jadwal Sesi
    Route::get('/jadwal', [AdminController::class, 'schedules'])->name('schedules');
    Route::post('/jadwal', [AdminController::class, 'storeSchedule'])->name('schedules.store');
    Route::put('/jadwal/{id}', [AdminController::class, 'updateSchedule'])->name('schedules.update');
    Route::post('/jadwal/{id}/toggle', [AdminController::class, 'toggleSchedule'])->name('schedules.toggle');
    Route::delete('/jadwal/{id}', [AdminController::class, 'destroySchedule'])->name('schedules.destroy');

    // Manajemen Ulasan
    Route::get('/ulasan', [AdminController::class, 'reviews'])->name('reviews');
});

// 5. Payment Gateway Webhook (Midtrans)
Route::post('/webhook/midtrans', [PaymentWebhookController::class, 'handle'])->name('midtrans.webhook');

// 6. Autentikasi & Profil (User Wisatawan & Admin Paguyuban)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/profile', [AuthController::class, 'profile'])->name('profile');

// 7. Ulasan & Review Wisatawan (Wajib Login untuk menulis)
Route::get('/ulasan', [\App\Http\Controllers\ReviewController::class, 'index'])->name('reviews.index');
Route::post('/reviews', [\App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
Route::put('/reviews/{id}', [\App\Http\Controllers\ReviewController::class, 'update'])->name('reviews.update');
Route::delete('/reviews/{id}', [\App\Http\Controllers\ReviewController::class, 'destroy'])->name('reviews.destroy');


