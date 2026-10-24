<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\AdminController;

// 1. Rute Publik Wisatawan
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/packages/{slug}', [HomeController::class, 'showPackage'])->name('packages.show');

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
});
