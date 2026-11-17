<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PaymentWebhookController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PaymentController;

// 1. Rute Publik Wisatawan
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::redirect('/packages', '/#paket')->name('packages.index');
Route::redirect('/paket-wisata', '/#paket')->name('packages.alias');
Route::get('/packages/{slug}', [HomeController::class, 'showPackage'])->name('packages.show');
Route::get('/paket-wisata/{slug}', [HomeController::class, 'showPackage'])->name('packages.show.alias');
Route::get('/syarat-ketentuan', [HomeController::class, 'terms'])->name('terms');
Route::get('/kebijakan-privasi', [HomeController::class, 'privacy'])->name('privacy');

// 2. Alur Pemesanan (Booking)
Route::get('/booking', [BookingController::class, 'create'])->name('booking.create');
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
Route::get('/booking/slot-availability', [BookingController::class, 'checkSlotAvailability'])->name('booking.slot_availability');
Route::get('/booking/resolve-link', [BookingController::class, 'resolvePlace'])->name('booking.resolve');
Route::match(['get', 'post'], '/api/resolve-place', [BookingController::class, 'resolvePlace'])->middleware('throttle:60,1')->name('places.resolve_place');
Route::match(['get', 'post'], '/api/resolve-map-link', [BookingController::class, 'resolvePlace'])->middleware('throttle:60,1')->name('places.resolve_link');
Route::get('/api/places-autocomplete', [BookingController::class, 'placesSearch'])->middleware('throttle:60,1')->name('places.autocomplete');
Route::get('/api/place-details', [BookingController::class, 'placesDetail'])->middleware('throttle:60,1')->name('places.details');
Route::get('/api/places/search', [BookingController::class, 'placesSearch'])->middleware('throttle:60,1')->name('places.search');
Route::get('/api/places/detail', [BookingController::class, 'placesDetail'])->middleware('throttle:60,1')->name('places.detail');
Route::get('/booking/{code}/payment', [BookingController::class, 'payment'])->name('booking.payment');
Route::post('/booking/{code}/confirm-transfer', [BookingController::class, 'confirmTransfer'])->name('booking.confirm_transfer');
Route::post('/booking/{code}/confirm-cash', [BookingController::class, 'confirmCash'])->name('booking.confirm_cash');
Route::post('/booking/{code}/charge', [PaymentController::class, 'charge'])->name('booking.charge');
Route::get('/booking/{code}/status', [PaymentController::class, 'status'])->name('booking.status');
Route::get('/booking/{code}/active-payment', [PaymentController::class, 'activePayment'])->name('booking.active_payment');
Route::get('/booking/distance', [BookingController::class, 'distance'])->middleware('throttle:60,1')->name('booking.distance');
Route::get('/booking/{code}/success', [BookingController::class, 'success'])->name('booking.success');
Route::get('/lokasi-jemput', [BookingController::class, 'placesSearch'])->middleware('throttle:60,1')->name('booking.location');
Route::get('/lokasi-jemput/reverse', [BookingController::class, 'reverseLocation'])->middleware('throttle:60,1')->name('booking.reverse');
Route::get('/lokasi-jemput/nearby', [BookingController::class, 'nearbyLodging'])->name('booking.nearby');

// 3. Pelacakan Tiket Mandiri (Wajib Login)
Route::get('/cek-tiket', [TicketController::class, 'check'])->middleware('auth')->name('ticket.check');

// 4. Portal Admin & Loket Basecamp
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/bookings', [AdminController::class, 'bookings'])->name('bookings');
    Route::get('/armada', [AdminController::class, 'armada'])->name('armada');
    Route::get('/notifikasi', [AdminController::class, 'notifications'])->name('notifications');

    Route::post('/bookings/{id}/settle', [AdminController::class, 'settleDp'])->name('settle');
    Route::post('/bookings/{id}/assign', [AdminController::class, 'assignDriver'])->name('assign');
    Route::post('/bookings/{id}/reschedule', [AdminController::class, 'rescheduleBooking'])->name('reschedule');
    Route::get('/bookings/slot-availability', [AdminController::class, 'checkSlotAvailability'])->name('slot-availability');
    Route::post('/bookings/{id}/cancel', [AdminController::class, 'cancelBooking'])->name('cancel');
    Route::post('/jeep', [AdminController::class, 'storeJeep'])->name('jeeps.store');
    Route::post('/jeep/{id}/status', [AdminController::class, 'updateJeepStatus'])->name('jeeps.status');
    Route::delete('/jeep/{id}', [AdminController::class, 'destroyJeep'])->name('jeeps.destroy');
    Route::post('/jeep/activate-all', [AdminController::class, 'activateAllJeeps'])->name('jeeps.activateAll');
    Route::post('/driver/{id}/status', [AdminController::class, 'toggleDriverStatus'])->name('drivers.status');
    Route::delete('/driver/{id}', [AdminController::class, 'destroyDriver'])->name('drivers.destroy');
    Route::post('/notifikasi/mark-all-read', [AdminController::class, 'markAllNotificationsRead'])->name('notifications.markAllRead');

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

    // Pengaturan Sistem & Operasional Basecamp
    Route::get('/pengaturan', [AdminController::class, 'settings'])->name('settings');
    Route::put('/pengaturan/profile', [AdminController::class, 'updateAdminProfile'])->name('settings.profile');
    Route::post('/pengaturan/basecamp', [AdminController::class, 'updateBasecampSettings'])->name('settings.basecamp');
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
Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');

// 7. Ulasan & Review Wisatawan (Wajib Login untuk menulis)
Route::get('/ulasan', [\App\Http\Controllers\ReviewController::class, 'index'])->name('reviews.index');
Route::post('/reviews', [\App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
Route::put('/reviews/{id}', [\App\Http\Controllers\ReviewController::class, 'update'])->name('reviews.update');
Route::delete('/reviews/{id}', [\App\Http\Controllers\ReviewController::class, 'destroy'])->name('reviews.destroy');


