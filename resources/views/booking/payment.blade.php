@extends('layouts.app')

@section('title', 'Pembayaran Booking ' . $booking->booking_code . ' | Jeep Wukirsari')

@section('content')
<div class="py-10 sm:py-14 bg-wukirsari-cream min-h-screen">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- STEPPER INDIKATOR PROGRES RESERVASI -->
        <div class="mb-8">
            <div class="bg-white rounded-2xl border border-wukirsari-border p-4 sm:p-5 shadow-xs max-w-3xl mx-auto">
                <div class="flex items-center justify-between text-xs sm:text-sm font-medium">
                    <!-- Step 1: Selesai -->
                    <div class="flex items-center gap-2 text-wukirsari-primary font-semibold">
                        <span class="w-7 h-7 rounded-full bg-wukirsari-sage text-wukirsari-primary flex items-center justify-center text-xs font-bold shrink-0">
                            <svg class="w-4 h-4 text-wukirsari-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                        </span>
                        <span class="hidden sm:inline">Data Pesanan</span>
                    </div>

                    <div class="w-8 sm:w-16 h-0.5 bg-wukirsari-primary"></div>

                    <!-- Step 2: Pembayaran (Aktif) -->
                    <div class="flex items-center gap-2 text-wukirsari-primary font-bold">
                        <span class="w-7 h-7 rounded-full bg-wukirsari-primary text-white flex items-center justify-center text-xs font-bold shrink-0 shadow-sm shadow-wukirsari-primary/30">
                            2
                        </span>
                        <span>Pembayaran</span>
                    </div>

                    <div class="w-8 sm:w-16 h-0.5 bg-wukirsari-border"></div>

                    <!-- Step 3: Tiket & Konfirmasi -->
                    <div class="flex items-center gap-2 text-merapiText-muted">
                        <span class="w-7 h-7 rounded-full bg-slate-100 text-merapiText-muted flex items-center justify-center text-xs font-bold shrink-0">
                            3
                        </span>
                        <span class="hidden sm:inline">E-Tiket</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- HEADER HALAMAN -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-merapiText-primary font-display">
                        Pembayaran Reservasi
                    </h1>
                    <p class="text-merapiText-secondary text-sm mt-1">
                        Selesaikan pembayaran dengan aman melalui metode pembayaran digital resmi yang tersedia.
                    </p>
                </div>
                <div class="flex items-center gap-2 self-start sm:self-auto">
                    <span class="text-xs font-medium text-merapiText-muted">Kode Booking:</span>
                    <span class="px-3 py-1 rounded-full bg-wukirsari-sage text-wukirsari-primary font-mono font-bold text-xs">
                        {{ $booking->booking_code }}
                    </span>
                </div>
            </div>
        </div>

        <!-- TATA LETAK 2 KOLOM (DESKTOP) / BERTUMPUK (MOBILE) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- ========================================== -->
            <!-- KOLOM KIRI (7 SPAN): RINGKASAN & METODE    -->
            <!-- ========================================== -->
            <div class="lg:col-span-7 space-y-6">
                
                <!-- 1. KARTU RINGKASAN PESANAN -->
                <div class="bg-white rounded-3xl border border-wukirsari-border p-6 shadow-xs space-y-5">
                    <div class="flex items-center justify-between border-b border-wukirsari-border pb-3">
                        <h2 class="text-base font-bold text-merapiText-primary font-display flex items-center gap-2">
                            <svg class="w-5 h-5 text-wukirsari-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <span>Ringkasan Pesanan</span>
                        </h2>
                        <span id="bookingStatusBadge" class="text-xs px-2.5 py-0.5 rounded-full font-semibold {{ $booking->booking_status === 'PENDING' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800' }}">
                            {{ $booking->booking_status === 'PENDING' ? 'Menunggu Pembayaran' : $booking->booking_status }}
                        </span>
                    </div>

                    <!-- Informasi Paket Wisata -->
                    <div class="flex items-start gap-4 p-4 rounded-2xl bg-wukirsari-cream/60 border border-wukirsari-border">
                        <img src="{{ $booking->package->cover_image }}" alt="{{ $booking->package->name }}" class="w-20 h-20 rounded-xl object-cover shrink-0 border border-wukirsari-border">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold uppercase tracking-wider text-wukirsari-primary">
                                    Paket Wisata
                                </span>
                                @if($booking->package->category_badge)
                                <span class="px-2 py-0.5 rounded-full bg-wukirsari-primary text-white text-[10px] font-semibold">
                                    {{ $booking->package->category_badge }}
                                </span>
                                @endif
                            </div>
                            <h3 class="text-base font-bold text-merapiText-primary truncate font-display mt-0.5">
                                {{ $booking->package->name }}
                            </h3>
                            <div class="flex items-center gap-3 text-xs text-merapiText-secondary mt-1">
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-merapiText-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                    <span>{{ $booking->passenger_count }} Orang</span>
                                </span>
                                <span>•</span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-merapiText-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                                    <span>{{ $booking->jeep_quantity }} Jeep</span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Rincian Parameter Reservasi -->
                    <div class="grid grid-cols-2 gap-3 text-xs">
                        <div class="p-3 rounded-xl bg-slate-50 border border-wukirsari-border">
                            <span class="text-merapiText-muted block mb-0.5">Tanggal Trip:</span>
                            <span class="font-bold text-merapiText-primary">
                                {{ $booking->trip_date->format('d M Y') }}
                            </span>
                        </div>
                        <div class="p-3 rounded-xl bg-slate-50 border border-wukirsari-border">
                            <span class="text-merapiText-muted block mb-0.5">Sesi Waktu:</span>
                            <span class="font-bold text-merapiText-primary">
                                {{ $booking->slot->slot_label }}
                            </span>
                        </div>
                        <div class="col-span-2 p-3 rounded-xl bg-slate-50 border border-wukirsari-border flex items-center justify-between">
                            <div>
                                <span class="text-merapiText-muted block mb-0.5">Opsi Kedatangan:</span>
                                <span class="font-bold text-merapiText-primary">
                                    {{ $booking->arrival_type === 'self_drive' ? 'Datang Sendiri ke Basecamp' : 'Layanan Antar-Jemput (Shuttle)' }}
                                </span>
                            </div>
                            @if($booking->pickupZone)
                            <span class="text-[11px] font-semibold text-wukirsari-primary bg-wukirsari-sage px-2 py-0.5 rounded-full">
                                {{ $booking->pickupZone->zone_name }}
                            </span>
                            @endif
                        </div>
                    </div>

                    <!-- Kalkulasi Tagihan Finansial -->
                    <div class="space-y-2.5 text-xs sm:text-sm pt-3 border-t border-wukirsari-border">
                        <div class="flex justify-between text-merapiText-secondary">
                            <span>Sewa Jeep ({{ $booking->jeep_quantity }} unit × Rp {{ number_format($booking->package->price_per_jeep, 0, ',', '.') }})</span>
                            <span class="font-semibold text-merapiText-primary">Rp {{ number_format($booking->package_subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-merapiText-secondary">
                            <span>Layanan Shuttle / Jemputan</span>
                            <span class="font-semibold text-merapiText-primary">Rp {{ number_format($booking->pickup_fee, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-merapiText-secondary">
                            <span>Asuransi & BBM Kendaraan</span>
                            <span class="font-semibold text-emerald-600">Gratis (Termasuk)</span>
                        </div>
                        <div class="pt-2 border-t border-wukirsari-border flex justify-between items-baseline">
                            <span class="font-bold text-merapiText-primary">Total Biaya Reservasi</span>
                            <span class="text-base sm:text-lg font-extrabold text-merapiText-primary font-display">
                                Rp {{ number_format($booking->total_amount, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <!-- Kotak Tagihan Wajib Sekarang -->
                    <div class="p-4 rounded-2xl bg-wukirsari-sage/40 border border-wukirsari-border space-y-2">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-xs text-merapiText-secondary block">Skema Pembayaran:</span>
                                <span class="font-bold text-xs sm:text-sm text-wukirsari-primary">
                                    {{ $booking->payment_mode === 'FULL' ? 'Pembayaran Lunas (100%)' : 'Uang Muka (DP minimal 30%)' }}
                                </span>
                            </div>
                            <div class="text-right">
                                <span class="text-xs text-merapiText-secondary block">Wajib Dibayar Sekarang:</span>
                                <span class="text-lg sm:text-xl font-extrabold text-wukirsari-primary font-display" id="displayPayableAmount">
                                    Rp {{ number_format($booking->paid_amount, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        @if($booking->remaining_amount > 0)
                        <div class="pt-2 border-t border-wukirsari-border flex justify-between items-center text-xs text-amber-800">
                            <span>Sisa Pembayaran di Loket Basecamp:</span>
                            <span class="font-bold text-sm">
                                Rp {{ number_format($booking->remaining_amount, 0, ',', '.') }}
                            </span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- 2. PILIHAN METODE PEMBAYARAN -->
                <div class="bg-white rounded-3xl border border-wukirsari-border p-6 shadow-xs space-y-4" id="sectionPaymentMethods">
                    <h2 class="text-base font-bold text-merapiText-primary font-display flex items-center gap-2">
                        <svg class="w-5 h-5 text-wukirsari-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        <span>Pilih Metode Pembayaran Digital</span>
                    </h2>
                    <p class="text-xs text-merapiText-secondary">
                        Pilih salah satu metode pembayaran di bawah ini untuk menerbitkan detail transaksi resmi Midtrans:
                    </p>

                    <div class="space-y-3" id="paymentMethodGroup">
                        
                        <!-- Bank BCA -->
                        <label class="payment-method-card flex items-center justify-between p-4 rounded-2xl border cursor-pointer transition-all border-wukirsari-primary bg-wukirsari-sage/30 ring-1 ring-wukirsari-primary" data-method="bca_va">
                            <div class="flex items-center gap-3.5">
                                <input type="radio" name="payment_channel" value="bca_va" checked class="w-4 h-4 text-wukirsari-primary border-wukirsari-border focus:ring-wukirsari-primary">
                                <div class="w-10 h-10 rounded-xl bg-white border border-wukirsari-border flex items-center justify-center font-bold text-xs text-blue-700 shrink-0 shadow-xs">
                                    BCA
                                </div>
                                <div>
                                    <span class="block text-sm font-bold text-merapiText-primary">BCA Virtual Account</span>
                                    <span class="block text-xs text-merapiText-secondary mt-0.5">Transfer m-BCA, KlikBCA, atau ATM BCA</span>
                                </div>
                            </div>
                            <span class="text-xs text-wukirsari-primary font-semibold">Pilih</span>
                        </label>

                        <!-- Bank Mandiri (E-Channel) -->
                        <label class="payment-method-card flex items-center justify-between p-4 rounded-2xl border cursor-pointer transition-all border-wukirsari-border hover:bg-slate-50" data-method="mandiri_echannel">
                            <div class="flex items-center gap-3.5">
                                <input type="radio" name="payment_channel" value="mandiri_echannel" class="w-4 h-4 text-wukirsari-primary border-wukirsari-border focus:ring-wukirsari-primary">
                                <div class="w-10 h-10 rounded-xl bg-white border border-wukirsari-border flex items-center justify-center font-bold text-xs text-blue-900 shrink-0 shadow-xs">
                                    MDR
                                </div>
                                <div>
                                    <span class="block text-sm font-bold text-merapiText-primary">Mandiri Bill Payment</span>
                                    <span class="block text-xs text-merapiText-secondary mt-0.5">Livin' by Mandiri atau ATM Mandiri</span>
                                </div>
                            </div>
                            <span class="text-xs text-merapiText-muted font-semibold">Pilih</span>
                        </label>

                        <!-- Bank BNI -->
                        <label class="payment-method-card flex items-center justify-between p-4 rounded-2xl border cursor-pointer transition-all border-wukirsari-border hover:bg-slate-50" data-method="bni_va">
                            <div class="flex items-center gap-3.5">
                                <input type="radio" name="payment_channel" value="bni_va" class="w-4 h-4 text-wukirsari-primary border-wukirsari-border focus:ring-wukirsari-primary">
                                <div class="w-10 h-10 rounded-xl bg-white border border-wukirsari-border flex items-center justify-center font-bold text-xs text-orange-600 shrink-0 shadow-xs">
                                    BNI
                                </div>
                                <div>
                                    <span class="block text-sm font-bold text-merapiText-primary">BNI Virtual Account</span>
                                    <span class="block text-xs text-merapiText-secondary mt-0.5">BNI Mobile Banking atau ATM BNI</span>
                                </div>
                            </div>
                            <span class="text-xs text-merapiText-muted font-semibold">Pilih</span>
                        </label>

                        <!-- Bank BRI -->
                        <label class="payment-method-card flex items-center justify-between p-4 rounded-2xl border cursor-pointer transition-all border-wukirsari-border hover:bg-slate-50" data-method="bri_va">
                            <div class="flex items-center gap-3.5">
                                <input type="radio" name="payment_channel" value="bri_va" class="w-4 h-4 text-wukirsari-primary border-wukirsari-border focus:ring-wukirsari-primary">
                                <div class="w-10 h-10 rounded-xl bg-white border border-wukirsari-border flex items-center justify-center font-bold text-xs text-blue-600 shrink-0 shadow-xs">
                                    BRI
                                </div>
                                <div>
                                    <span class="block text-sm font-bold text-merapiText-primary">BRI Virtual Account (BRIVA)</span>
                                    <span class="block text-xs text-merapiText-secondary mt-0.5">BRImo atau ATM BRI</span>
                                </div>
                            </div>
                            <span class="text-xs text-merapiText-muted font-semibold">Pilih</span>
                        </label>

                        <!-- QRIS -->
                        <label class="payment-method-card flex items-center justify-between p-4 rounded-2xl border cursor-pointer transition-all border-wukirsari-border hover:bg-slate-50" data-method="qris">
                            <div class="flex items-center gap-3.5">
                                <input type="radio" name="payment_channel" value="qris" class="w-4 h-4 text-wukirsari-primary border-wukirsari-border focus:ring-wukirsari-primary">
                                <div class="w-10 h-10 rounded-xl bg-white border border-wukirsari-border flex items-center justify-center font-bold text-xs text-red-600 shrink-0 shadow-xs">
                                    QRIS
                                </div>
                                <div>
                                    <span class="block text-sm font-bold text-merapiText-primary">QRIS Dinamis</span>
                                    <span class="block text-xs text-merapiText-secondary mt-0.5">Scan via GoPay, OVO, DANA, BCA, Livin, dll.</span>
                                </div>
                            </div>
                            <span class="text-xs text-merapiText-muted font-semibold">Pilih</span>
                        </label>

                        <!-- GoPay -->
                        <label class="payment-method-card flex items-center justify-between p-4 rounded-2xl border cursor-pointer transition-all border-wukirsari-border hover:bg-slate-50" data-method="gopay">
                            <div class="flex items-center gap-3.5">
                                <input type="radio" name="payment_channel" value="gopay" class="w-4 h-4 text-wukirsari-primary border-wukirsari-border focus:ring-wukirsari-primary">
                                <div class="w-10 h-10 rounded-xl bg-white border border-wukirsari-border flex items-center justify-center font-bold text-xs text-teal-600 shrink-0 shadow-xs">
                                    GOPAY
                                </div>
                                <div>
                                    <span class="block text-sm font-bold text-merapiText-primary">GoPay / Gojek App</span>
                                    <span class="block text-xs text-merapiText-secondary mt-0.5">Buka langsung di aplikasi Gojek / scan QR</span>
                                </div>
                            </div>
                            <span class="text-xs text-merapiText-muted font-semibold">Pilih</span>
                        </label>

                    </div>
                </div>

            </div>

            <!-- ========================================== -->
            <!-- KOLOM KANAN (5 SPAN): INFORMASI TRANSAKSI  -->
            <!-- ========================================== -->
            <div class="lg:col-span-5 space-y-6 lg:sticky lg:top-28">
                
                <!-- KARTU INFORMASI PEMBAYARAN -->
                <div class="bg-white rounded-3xl border border-wukirsari-border p-6 sm:p-7 shadow-xs space-y-5">
                    
                    <div class="flex items-center justify-between border-b border-wukirsari-border pb-3">
                        <h2 class="text-base font-bold text-merapiText-primary font-display flex items-center gap-2">
                            <svg class="w-5 h-5 text-wukirsari-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Informasi Pembayaran</span>
                        </h2>
                        <span class="text-xs font-semibold text-emerald-800 bg-emerald-100 px-2.5 py-0.5 rounded-full flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 animate-pulse"></span>
                            <span>Midtrans Gateway</span>
                        </span>
                    </div>

                    <!-- Alert Notifikasi / Feedback Error -->
                    <div id="paymentAlertBox" class="hidden p-3.5 rounded-2xl text-xs font-medium border"></div>

                    <!-- STATE 1: SEBELUM CHARGE (INISIALISASI) -->
                    <div id="panelInitialPrompt" class="space-y-4">
                        <div class="p-4 rounded-2xl bg-wukirsari-cream/60 border border-wukirsari-border space-y-2">
                            <span class="text-xs font-bold text-wukirsari-primary block uppercase tracking-wider">
                                Konfirmasi Pilihan Metode
                            </span>
                            <p class="text-xs text-merapiText-secondary leading-relaxed" id="selectedMethodSummaryText">
                                Anda memilih metode Virtual Account. Klik tombol di bawah ini untuk menerbitkan kode pembayaran resmi dari Midtrans.
                            </p>
                        </div>

                        <button type="button" id="btnInitiateCharge" class="w-full py-4 px-6 rounded-full bg-wukirsari-primary hover:bg-wukirsari-hover text-white font-bold text-sm flex items-center justify-center gap-2 shadow-lg shadow-wukirsari-primary/25 active:scale-98 transition-all">
                            <span id="btnChargeText">Bayar Sekarang (Rp {{ number_format($booking->paid_amount, 0, ',', '.') }}) →</span>
                            <svg id="btnChargeSpinner" class="hidden w-5 h-5 text-white animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- STATE 2: SETELAH CHARGE TERBIT (INFORMASI RIIL MIDTRANS) -->
                    <div id="panelActiveTransaction" class="hidden space-y-5">
                        
                        <!-- Timer Hitung Mundur -->
                        <div class="p-3.5 rounded-2xl bg-amber-50 border border-amber-200 flex items-center justify-between">
                            <div class="flex items-center gap-2.5 text-xs text-amber-900 font-semibold">
                                <svg class="w-4 h-4 text-amber-700 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>Selesaikan Pembayaran Dalam:</span>
                            </div>
                            <span id="countdownTimer" class="font-mono font-bold text-sm text-amber-900 bg-amber-100 px-2.5 py-0.5 rounded-lg">
                                --:--
                            </span>
                        </div>

                        <!-- 2A: Tampilan Bank Transfer (BCA, BNI, BRI) -->
                        <div id="boxBankVa" class="hidden space-y-3 p-4 rounded-2xl bg-slate-50 border border-wukirsari-border">
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-merapiText-muted uppercase tracking-wider font-semibold" id="vaBankNameLabel">
                                    Virtual Account
                                </span>
                                <span class="text-[11px] text-wukirsari-primary font-bold" id="vaOrderIdLabel">
                                    --
                                </span>
                            </div>

                            <div class="p-3.5 bg-white rounded-xl border border-wukirsari-border flex items-center justify-between gap-2 shadow-xs">
                                <div>
                                    <span class="text-[10px] text-merapiText-muted block">Nomor Virtual Account:</span>
                                    <span id="vaNumberDisplay" class="font-mono font-extrabold text-base sm:text-lg text-merapiText-primary tracking-wider">
                                        ----------------
                                    </span>
                                </div>
                                <button type="button" id="btnCopyVa" class="px-3.5 py-1.5 rounded-lg bg-wukirsari-sage text-wukirsari-primary hover:bg-wukirsari-sage/80 font-bold text-xs flex items-center gap-1 transition-all">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                    <span id="btnCopyVaText">Salin</span>
                                </button>
                            </div>

                            <div class="text-[11px] text-merapiText-secondary space-y-1 pt-1">
                                <div class="flex justify-between">
                                    <span>Atas Nama:</span>
                                    <span class="font-semibold text-merapiText-primary">Jeep WisataSeri Wukirsari</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Nominal Transfer:</span>
                                    <span class="font-bold text-wukirsari-primary" id="vaGrossAmountText">Rp --</span>
                                </div>
                            </div>
                        </div>

                        <!-- 2B: Tampilan Mandiri Bill Payment -->
                        <div id="boxMandiriBill" class="hidden space-y-3 p-4 rounded-2xl bg-slate-50 border border-wukirsari-border">
                            <span class="text-xs text-merapiText-muted uppercase tracking-wider font-semibold block">
                                Mandiri Bill Payment (E-Channel)
                            </span>

                            <div class="grid grid-cols-1 gap-2">
                                <div class="p-3 bg-white rounded-xl border border-wukirsari-border flex items-center justify-between">
                                    <div>
                                        <span class="text-[10px] text-merapiText-muted block">Kode Perusahaan (Biller Code):</span>
                                        <span id="mandiriBillerCodeDisplay" class="font-mono font-bold text-sm text-merapiText-primary">-----</span>
                                    </div>
                                    <button type="button" id="btnCopyBillerCode" class="px-3 py-1 rounded-md bg-wukirsari-sage text-wukirsari-primary text-xs font-bold">Salin</button>
                                </div>

                                <div class="p-3 bg-white rounded-xl border border-wukirsari-border flex items-center justify-between">
                                    <div>
                                        <span class="text-[10px] text-merapiText-muted block">Nomor Tagihan (Bill Key):</span>
                                        <span id="mandiriBillKeyDisplay" class="font-mono font-bold text-sm text-merapiText-primary">----------</span>
                                    </div>
                                    <button type="button" id="btnCopyBillKey" class="px-3 py-1 rounded-md bg-wukirsari-sage text-wukirsari-primary text-xs font-bold">Salin</button>
                                </div>
                            </div>
                        </div>

                        <!-- 2C: Tampilan QRIS Dinamis -->
                        <div id="boxQris" class="hidden text-center space-y-3 p-4 rounded-2xl bg-slate-50 border border-wukirsari-border">
                            <span class="text-xs text-merapiText-muted uppercase tracking-wider font-semibold block">
                                Pindai QRIS Dinamis
                            </span>
                            <div class="w-48 h-48 mx-auto bg-white p-2.5 rounded-2xl border border-wukirsari-border shadow-xs flex items-center justify-center">
                                <img id="qrisImageDisplay" src="" alt="QRIS Code" class="w-full h-full object-contain">
                            </div>
                            <p class="text-[11px] text-merapiText-secondary leading-tight">
                                Buka aplikasi m-Banking atau e-wallet apa pun, pilih menu QRIS, dan arahkan kamera ke kode di atas.
                            </p>
                        </div>

                        <!-- 2D: Tampilan E-Wallet (GoPay Deeplink) -->
                        <div id="boxEwallet" class="hidden space-y-3 p-4 rounded-2xl bg-slate-50 border border-wukirsari-border text-center">
                            <span class="text-xs text-merapiText-muted uppercase tracking-wider font-semibold block">
                                Pembayaran E-Wallet
                            </span>
                            <a id="btnEwalletDeeplink" href="#" target="_blank" class="w-full py-3 px-4 rounded-full bg-teal-600 hover:bg-teal-700 text-white font-bold text-xs inline-flex items-center justify-center gap-2 shadow-sm transition-all">
                                <span>Buka Aplikasi E-Wallet →</span>
                            </a>
                            <p class="text-[11px] text-merapiText-secondary">
                                Klik tombol di atas untuk menyelesaikan transaksi di aplikasi GoPay / ShopeePay Anda.
                            </p>
                        </div>

                        <!-- Indikator Polling Verifikasi Otomatis -->
                        <div class="p-3.5 rounded-xl bg-emerald-50/80 border border-emerald-200 flex items-center gap-3">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-600 animate-ping shrink-0"></span>
                            <div class="text-[11px] text-emerald-900 leading-tight">
                                <span class="font-bold block">Menunggu Pembayaran</span>
                                <span>Halaman akan otomatis dialihkan ke E-Tiket begitu transaksi Anda terverifikasi oleh sistem.</span>
                            </div>
                        </div>

                    </div>

                    <!-- Tautan Batal / Kembali -->
                    <div class="text-center pt-2">
                        <a href="{{ route('booking.create') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-merapiText-muted hover:text-rose-700 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            <span>Batalkan Reservasi / Ubah Pesanan</span>
                        </a>
                    </div>

                    <!-- Footer Keamanan Transaksi -->
                    <div class="pt-4 border-t border-wukirsari-border flex items-center justify-center gap-2 text-[11px] text-merapiText-muted">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <span>Transaksi Diamankan dengan Enkripsi SSL & SHA-512</span>
                    </div>

                </div>

            </div>

        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const bookingCode = '{{ $booking->booking_code }}';
        const chargeUrl = '{{ route('booking.charge', $booking->booking_code) }}';
        const statusUrl = '{{ route('booking.status', $booking->booking_code) }}';
        const activePaymentUrl = '{{ route('booking.active_payment', $booking->booking_code) }}';
        const csrfToken = '{{ csrf_token() }}';

        // DOM Elements
        const methodCards = document.querySelectorAll('.payment-method-card');
        const alertBox = document.getElementById('paymentAlertBox');
        const panelInitial = document.getElementById('panelInitialPrompt');
        const panelActive = document.getElementById('panelActiveTransaction');
        const btnCharge = document.getElementById('btnInitiateCharge');
        const btnChargeText = document.getElementById('btnChargeText');
        const btnChargeSpinner = document.getElementById('btnChargeSpinner');
        const timerDisplay = document.getElementById('countdownTimer');

        // Payment sub-boxes
        const boxBankVa = document.getElementById('boxBankVa');
        const boxMandiriBill = document.getElementById('boxMandiriBill');
        const boxQris = document.getElementById('boxQris');
        const boxEwallet = document.getElementById('boxEwallet');

        let selectedMethod = 'bca_va';
        let pollingInterval = null;
        let countdownInterval = null;

        function showAlert(msg, isError = true) {
            alertBox.classList.remove('hidden', 'bg-rose-50', 'border-rose-200', 'text-rose-800', 'bg-emerald-50', 'border-emerald-200', 'text-emerald-800');
            if (isError) {
                alertBox.classList.add('bg-rose-50', 'border-rose-200', 'text-rose-800');
            } else {
                alertBox.classList.add('bg-emerald-50', 'border-emerald-200', 'text-emerald-800');
            }
            alertBox.innerText = msg;
        }

        function formatCurrency(num) {
            return 'Rp ' + Number(num).toLocaleString('id-ID');
        }

        // 1. Interaksi Pemilihan Metode Pembayaran
        methodCards.forEach(card => {
            card.addEventListener('click', function () {
                methodCards.forEach(c => {
                    c.classList.remove('border-wukirsari-primary', 'bg-wukirsari-sage/30', 'ring-1', 'ring-wukirsari-primary');
                    c.classList.add('border-wukirsari-border');
                    const radio = c.querySelector('input[type="radio"]');
                    if (radio) radio.checked = false;
                });

                this.classList.add('border-wukirsari-primary', 'bg-wukirsari-sage/30', 'ring-1', 'ring-wukirsari-primary');
                this.classList.remove('border-wukirsari-border');
                const radio = this.querySelector('input[type="radio"]');
                if (radio) radio.checked = true;

                selectedMethod = this.dataset.method;
            });
        });

        // 2. Render Informasi Transaksi Aktif
        function renderPaymentDetails(data) {
            panelInitial.classList.add('hidden');
            panelActive.classList.remove('hidden');

            boxBankVa.classList.add('hidden');
            boxMandiriBill.classList.add('hidden');
            boxQris.classList.add('hidden');
            boxEwallet.classList.add('hidden');

            // Virtual Account BCA / BNI / BRI
            if (data.va_number) {
                boxBankVa.classList.remove('hidden');
                document.getElementById('vaBankNameLabel').innerText = (data.payment_method || 'Virtual Account').toUpperCase().replace('_', ' ');
                document.getElementById('vaOrderIdLabel').innerText = data.order_id || '';
                document.getElementById('vaNumberDisplay').innerText = data.va_number;
                document.getElementById('vaGrossAmountText').innerText = formatCurrency(data.gross_amount);
            } 
            // Mandiri Bill
            else if (data.biller_code && data.bill_key) {
                boxMandiriBill.classList.remove('hidden');
                document.getElementById('mandiriBillerCodeDisplay').innerText = data.biller_code;
                document.getElementById('mandiriBillKeyDisplay').innerText = data.bill_key;
            } 
            // QRIS
            else if (data.qr_url || data.qr_string) {
                boxQris.classList.remove('hidden');
                if (data.qr_url) {
                    document.getElementById('qrisImageDisplay').src = data.qr_url;
                }
            } 
            // E-Wallet (GoPay / ShopeePay)
            else if (data.deeplink_url) {
                boxEwallet.classList.remove('hidden');
                document.getElementById('btnEwalletDeeplink').href = data.deeplink_url;
            }

            // Mulai Timer Hitung Mundur jika ada expires_at
            if (data.expires_at) {
                startCountdown(new Date(data.expires_at).getTime());
            }

            // Mulai Polling Status Otomatis
            startPolling();
        }

        // 3. Hitung Mundur Waktu Transaksi
        function startCountdown(targetTime) {
            if (countdownInterval) clearInterval(countdownInterval);

            function updateTimer() {
                const now = new Date().getTime();
                const diff = targetTime - now;

                if (diff <= 0) {
                    timerDisplay.innerText = 'Kedaluwarsa';
                    clearInterval(countdownInterval);
                    showAlert('Batas waktu pembayaran telah habis. Silakan muat ulang atau pilih metode lain.');
                    return;
                }

                const mins = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                const secs = Math.floor((diff % (1000 * 60)) / 1000);
                timerDisplay.innerText = `${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
            }

            updateTimer();
            countdownInterval = setInterval(updateTimer, 1000);
        }

        // 4. Polling Status Pembayaran Otomatis
        function startPolling() {
            if (pollingInterval) clearInterval(pollingInterval);

            pollingInterval = setInterval(async function () {
                try {
                    const res = await fetch(statusUrl, {
                        headers: { 'Accept': 'application/json' }
                    });
                    const json = await res.json();

                    if (json.is_paid) {
                        clearInterval(pollingInterval);
                        showAlert('Pembayaran terverifikasi! Mengalihkan ke E-Tiket...', false);
                        setTimeout(() => {
                            window.location.href = json.redirect_url || '{{ route('booking.success', $booking->booking_code) }}';
                        }, 1200);
                    }
                } catch (e) {
                    // Silent polling retry
                }
            }, 3000);
        }

        // 5. Inisiasi Charge Transaksi Baru
        btnCharge.addEventListener('click', async function () {
            btnCharge.disabled = true;
            btnChargeText.innerText = 'Menghubungi Gateway...';
            btnChargeSpinner.classList.remove('hidden');
            alertBox.classList.add('hidden');

            try {
                const res = await fetch(chargeUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ payment_method: selectedMethod })
                });

                const json = await res.json();

                if (res.status === 200 && json.status === 'already_paid') {
                    window.location.href = json.redirect_url;
                    return;
                }

                if (json.status === 'success' && json.data) {
                    renderPaymentDetails(json.data);
                } else {
                    showAlert(json.message || 'Gagal membuat transaksi pembayaran.');
                }
            } catch (err) {
                showAlert('Terjadi kesalahan jaringan saat memproses pembayaran. Silakan coba lagi.');
            } finally {
                btnCharge.disabled = false;
                btnChargeText.innerText = 'Bayar Sekarang →';
                btnChargeSpinner.classList.add('hidden');
            }
        });

        // 6. Fitur Salin Teks Instan
        function setupCopyButton(btnId, displayId, labelText) {
            const btn = document.getElementById(btnId);
            const display = document.getElementById(displayId);
            if (!btn || !display) return;

            btn.addEventListener('click', function () {
                const text = display.innerText.trim();
                navigator.clipboard.writeText(text).then(() => {
                    const originalHtml = btn.innerHTML;
                    btn.innerText = 'Tersalin!';
                    btn.classList.add('bg-emerald-600', 'text-white');
                    setTimeout(() => {
                        btn.innerHTML = originalHtml;
                        btn.classList.remove('bg-emerald-600', 'text-white');
                    }, 2000);
                });
            });
        }

        setupCopyButton('btnCopyVa', 'vaNumberDisplay', 'Salin');
        setupCopyButton('btnCopyBillerCode', 'mandiriBillerCodeDisplay', 'Salin');
        setupCopyButton('btnCopyBillKey', 'mandiriBillKeyDisplay', 'Salin');

        // 7. Muat Transaksi Aktif saat Halaman Dibuka Kembali
        async function loadActivePaymentOnLoad() {
            try {
                const res = await fetch(activePaymentUrl, {
                    headers: { 'Accept': 'application/json' }
                });
                const json = await res.json();
                if (json.status === 'success' && json.data) {
                    renderPaymentDetails(json.data);
                }
            } catch (e) {
                // Biarkan di panel inisialisasi jika tidak ada active payment
            }
        }

        loadActivePaymentOnLoad();
    });
</script>
@endsection
