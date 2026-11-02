@extends('layouts.app')

@section('title', 'Pembayaran Booking ' . $booking->booking_code . ' | Jeep Wukirsari')

@section('content')
@php
    $payableAmount = (int) round($booking->paid_amount);
    $activeManualPayment = $booking->payments->where('payment_type', 'MANUAL_BPD_DIY')->where('payment_status', 'PENDING')->first();
    $activeCashPayment = $booking->payments->where('payment_type', 'CASH')->where('payment_status', 'PENDING')->first();
    $latestFailedManualPayment = $booking->payments->where('payment_type', 'MANUAL_BPD_DIY')->where('payment_status', 'FAILED')->sortByDesc('id')->first();
    $bpdConfig = config('services.bpd_diy');
    $isWaiting = ($booking->booking_status === 'WAITING_CONFIRMATION');
    $isCashActive = ($activeCashPayment !== null);
@endphp

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
                        <span>{{ $isWaiting ? 'Verifikasi Pembayaran' : 'Pembayaran' }}</span>
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

        <!-- NOTIFIKASI FLASH & FEEDBACK -->
        @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-300 text-emerald-900 rounded-2xl text-xs sm:text-sm font-medium flex items-center gap-3 shadow-xs">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>{{ session('success') }}</div>
        </div>
        @endif

        @if(session('info'))
        <div class="mb-6 p-4 bg-wukirsari-sage/70 border border-wukirsari-primary/30 text-wukirsari-primary rounded-2xl text-xs sm:text-sm font-medium flex items-center gap-3 shadow-xs">
            <svg class="w-5 h-5 text-wukirsari-primary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>{{ session('info') }}</div>
        </div>
        @endif

        @if(session('warning'))
        <div class="mb-6 p-4 bg-amber-50 border border-amber-300 text-amber-900 rounded-2xl text-xs sm:text-sm font-medium flex items-center gap-3 shadow-xs">
            <svg class="w-5 h-5 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <div>{{ session('warning') }}</div>
        </div>
        @endif

        @if(session('error'))
        <div class="mb-6 p-4 bg-rose-50 border border-rose-300 text-rose-900 rounded-2xl text-xs sm:text-sm font-medium flex items-center gap-3 shadow-xs">
            <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>{{ session('error') }}</div>
        </div>
        @endif

        @if(isset($errors) && $errors->any())
        <div class="mb-6 p-4 bg-rose-50 border border-rose-300 text-rose-900 rounded-2xl text-xs sm:text-sm shadow-xs">
            <div class="font-bold mb-1 flex items-center gap-2">
                <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Mohon periksa kembali formulir unggah bukti:</span>
            </div>
            <ul class="list-disc list-inside space-y-0.5 text-rose-800">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- HEADER HALAMAN -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-merapiText-primary font-display">
                        {{ $isWaiting ? 'Pemeriksaan Pembayaran' : 'Pembayaran Reservasi' }}
                    </h1>
                    <p class="text-merapiText-secondary text-sm mt-1">
                        {{ $isWaiting ? 'Bukti pembayaran Anda telah diterima dan sedang menunggu persetujuan Admin Paguyuban.' : 'Pilih metode pembayaran resmi Jeep Wukirsari untuk menyelesaikan pesanan Anda.' }}
                    </p>
                </div>
                <div class="flex items-center gap-2 self-start sm:self-auto">
                    <span class="text-xs font-medium text-merapiText-muted">Kode Booking:</span>
                    <span class="px-3.5 py-1.5 rounded-full bg-wukirsari-sage text-wukirsari-primary font-mono font-bold text-xs shadow-2xs">
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
                        @if($booking->booking_status === 'WAITING_CONFIRMATION')
                        <span class="text-xs px-3 py-1 rounded-full font-bold bg-amber-100 text-amber-900 border border-amber-300">
                            Menunggu Verifikasi Admin
                        </span>
                        @elseif($booking->booking_status === 'FULL_PAID')
                        <span class="text-xs px-3 py-1 rounded-full font-bold bg-emerald-100 text-emerald-800">
                            Lunas (100%)
                        </span>
                        @elseif($booking->booking_status === 'DP_PAID')
                        <span class="text-xs px-3 py-1 rounded-full font-bold bg-wukirsari-sage text-wukirsari-primary">
                            DP Terverifikasi
                        </span>
                        @else
                        <span class="text-xs px-3 py-1 rounded-full font-bold bg-amber-50 text-amber-800 border border-amber-200">
                            Menunggu Pembayaran
                        </span>
                        @endif
                    </div>

                    <!-- Informasi Paket Wisata -->
                    <div class="flex items-start gap-4 p-4 rounded-2xl bg-wukirsari-cream/60 border border-wukirsari-border">
                        <img src="{{ asset($booking->package->cover_image) }}" alt="{{ $booking->package->name }}" class="w-20 h-20 rounded-xl object-cover shrink-0 border border-wukirsari-border">
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
                                    <svg class="w-3.5 h-3.5 text-merapiText-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>{{ $booking->package->duration_text }}</span>
                                </span>
                                <span>•</span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-merapiText-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                    <span>{{ $booking->package->destinations_count }} Destinasi</span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Wisatawan & Jadwal -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                        <div class="p-3 bg-slate-50/80 rounded-xl border border-wukirsari-border">
                            <span class="text-merapiText-muted block text-[11px]">Nama Pemesan & Kontak:</span>
                            <span class="font-bold text-merapiText-primary block text-sm">{{ $booking->customer_name }}</span>
                            <span class="text-merapiText-secondary">{{ $booking->customer_phone }}</span>
                        </div>
                        <div class="p-3 bg-slate-50/80 rounded-xl border border-wukirsari-border">
                            <span class="text-merapiText-muted block text-[11px]">Jadwal Trip & Waktu:</span>
                            <span class="font-bold text-merapiText-primary block text-sm">{{ $booking->trip_date->format('d M Y') }}</span>
                            <span class="text-merapiText-secondary">Sesi: {{ $booking->slot->slot_label }}</span>
                        </div>
                    </div>

                    <!-- Rincian Biaya -->
                    <div class="space-y-2 pt-2 text-xs border-t border-wukirsari-border">
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
                                <span class="text-xs text-merapiText-secondary block">Wajib Ditransfer Sekarang:</span>
                                <span class="text-lg sm:text-xl font-extrabold text-wukirsari-primary font-display" id="displayPayableAmount">
                                    Rp {{ number_format($payableAmount, 0, ',', '.') }}
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

                <!-- ======================================================== -->
                <!-- 2. STATUS WAITING CONFIRMATION ATAU PILIHAN METODE BAYAR -->
                <!-- ======================================================== -->
                @if($isWaiting)
                <!-- TAMPILAN JIKA BUKTI SUDAH DIUNGGAH (MENUNGGU VERIFIKASI) -->
                <div class="bg-white rounded-3xl border border-wukirsari-border p-6 sm:p-7 shadow-xs space-y-5">
                    <div class="flex items-center gap-3 border-b border-wukirsari-border pb-4">
                        <div class="w-11 h-11 rounded-2xl bg-amber-50 border border-amber-200 text-amber-800 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-merapiText-primary font-display">
                                Bukti Pembayaran Sedang Diverifikasi
                            </h2>
                            <p class="text-xs text-merapiText-secondary mt-0.5">
                                Terima kasih, berkas bukti transfer Anda telah tersimpan dengan aman di server kami.
                            </p>
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-amber-50/70 border border-amber-200/90 space-y-3 text-xs">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-amber-950">
                            <div>
                                <span class="text-amber-800 text-[11px] block">Tujuan Transfer:</span>
                                <span class="font-bold">{{ $bpdConfig['bank_name'] ?? 'Bank BPD DIY' }}</span>
                            </div>
                            <div>
                                <span class="text-amber-800 text-[11px] block">Nominal Ditransfer:</span>
                                <span class="font-bold font-mono text-sm">Rp {{ number_format($activeManualPayment->gross_amount ?? $payableAmount, 0, ',', '.') }}</span>
                            </div>
                            <div>
                                <span class="text-amber-800 text-[11px] block">Atas Nama Rekening Pengirim:</span>
                                <span class="font-semibold">{{ $activeManualPayment->sender_name ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="text-amber-800 text-[11px] block">Bank Asal Pengirim:</span>
                                <span class="font-semibold">{{ $activeManualPayment->sender_bank ?? '-' }}</span>
                            </div>
                        </div>

                        <div class="pt-2 border-t border-amber-200 text-amber-900 flex items-center gap-2">
                            <svg class="w-4 h-4 shrink-0 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Waktu Unggah: <strong>{{ $activeManualPayment ? $activeManualPayment->created_at->format('d M Y, H:i') : '-' }} WIB</strong></span>
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-wukirsari-sage/40 border border-wukirsari-border flex items-start gap-3 text-xs text-merapiText-secondary">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-600 animate-ping mt-1 shrink-0"></span>
                        <p class="leading-relaxed">
                            Admin Loket Basecamp Wukirsari sedang melakukan pencocokan mutasi rekening. <strong>Halaman ini akan otomatis dialihkan ke E-Tiket resmi</strong> tanpa perlu dimuat ulang begitu pembayaran disetujui.
                        </p>
                    </div>
                </div>

                @else

                <!-- PERINGATAN JIKA ADA RIWAYAT BUKTI DITOLAK SEBELUMNYA -->
                @if($latestFailedManualPayment)
                <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 text-xs sm:text-sm space-y-1.5 shadow-2xs">
                    <div class="flex items-center gap-2 font-bold text-rose-800">
                        <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <span>Bukti Pembayaran Sebelumnya Perlu Diperbaiki:</span>
                    </div>
                    <p class="text-rose-800 pl-6 leading-relaxed">
                        Catatan Admin: <em>"{{ $latestFailedManualPayment->rejection_reason ?? 'Nominal atau bukti transfer tidak sesuai.' }}"</em>
                    </p>
                    <p class="text-[11px] text-rose-700 pl-6">
                        Silakan periksa kembali nominal mutasi Anda, lalu unggah kembali struk/bukti transfer yang benar di bawah ini.
                    </p>
                </div>
                @endif

                <!-- PILIHAN KATEGORI METODE PEMBAYARAN -->
                <div class="bg-white rounded-3xl border border-wukirsari-border p-6 shadow-xs space-y-5" id="sectionPaymentSelection">
                    <div>
                        <h2 class="text-base font-bold text-merapiText-primary font-display flex items-center gap-2">
                            <svg class="w-5 h-5 text-wukirsari-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                            <span>Pilih Jalur Pembayaran</span>
                        </h2>
                        <p class="text-xs text-merapiText-secondary mt-1">
                            Pilih cara pembayaran yang paling nyaman bagi Anda:
                        </p>
                    </div>

                    <!-- TAB SELECTOR KATEGORI METODE (OPSI 1: GRID 2 ATAS + FULL-WIDTH CASH DI BAWAH) -->
                    <div class="space-y-3" id="paymentCategoryTabs">
                        <!-- Baris 1: 2 Kolom untuk Pembayaran Non-Tunai / Online -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <!-- Tab 1: Transfer Manual BPD DIY (Aktif Default) -->
                            <button type="button" id="tabManualBpd" class="p-3.5 sm:p-4 rounded-2xl border text-left transition-all border-wukirsari-primary bg-wukirsari-sage/35 ring-1 ring-wukirsari-primary flex items-start gap-3 cursor-pointer">
                                <div class="w-10 h-10 rounded-xl bg-white border border-wukirsari-border flex items-center justify-center font-bold text-xs text-wukirsari-primary shrink-0 shadow-2xs">
                                    BPD
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-1">
                                        <span class="font-bold text-xs sm:text-sm text-merapiText-primary truncate">Bank BPD DIY</span>
                                        <span class="px-2 py-0.5 rounded-full bg-wukirsari-primary text-white text-[9px] font-bold shrink-0">Utama</span>
                                    </div>
                                    <span class="text-[11px] text-merapiText-secondary block mt-0.5">Transfer manual & upload bukti</span>
                                </div>
                            </button>

                            <!-- Tab 2: DANA (Midtrans Gateway) -->
                            <button type="button" id="tabDanaMidtrans" class="p-3.5 sm:p-4 rounded-2xl border text-left transition-all border-wukirsari-border hover:bg-slate-50 flex items-start gap-3 cursor-pointer">
                                <div class="w-10 h-10 rounded-xl bg-blue-50 border border-blue-200 flex items-center justify-center font-bold text-xs text-blue-600 shrink-0 shadow-2xs">
                                    DANA
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-1">
                                        <span class="font-bold text-xs sm:text-sm text-merapiText-primary truncate">DANA Midtrans</span>
                                        <span class="px-2 py-0.5 rounded-full bg-slate-200 text-slate-600 text-[9px] font-semibold shrink-0">Segera</span>
                                    </div>
                                    <span class="text-[11px] text-merapiText-muted block mt-0.5">E-Wallet Digital Instan</span>
                                </div>
                            </button>
                        </div>

                        <!-- Baris 2: Full Width untuk Pembayaran Tunai di Loket Basecamp (Cash) -->
                        <button type="button" id="tabCashLoket" class="w-full p-3.5 sm:p-4 rounded-2xl border text-left transition-all border-wukirsari-border hover:bg-slate-50 flex items-center justify-between gap-3 cursor-pointer">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center justify-center font-bold text-xs text-emerald-800 shrink-0 shadow-2xs">
                                    CASH
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="font-bold text-xs sm:text-sm text-merapiText-primary">Bayar Tunai di Loket Kasir</span>
                                        <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[9px] font-bold">Lokasi Basecamp</span>
                                    </div>
                                    <span class="text-[11px] text-merapiText-secondary block mt-0.5">Bayar langsung secara tunai saat tiba di basecamp sebelum jadwal keberangkatan</span>
                                </div>
                            </div>
                            <div class="shrink-0 text-slate-400 hidden sm:block">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </button>
                    </div>

                    <!-- ============================================== -->
                    <!-- PANEL A: FORM TRANSFER MANUAL BANK BPD DIY     -->
                    <!-- ============================================== -->
                    <div id="panelBpdManual" class="space-y-6 pt-2">
                        
                        <!-- KARTU INFORMASI REKENING RESMI BPD DIY -->
                        <div class="p-5 rounded-2xl bg-gradient-to-br from-wukirsari-sage/70 via-white to-wukirsari-sage/40 border border-wukirsari-border space-y-4 shadow-2xs">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full bg-wukirsari-primary"></span>
                                    <span class="text-xs font-bold text-wukirsari-primary uppercase tracking-wider">
                                        Rekening Resmi Paguyuban
                                    </span>
                                </div>
                                <span class="text-[10px] text-merapiText-muted font-medium">
                                    {{ $bpdConfig['branch'] ?? 'Cabang Sleman / DIY' }}
                                </span>
                            </div>

                            <!-- Baris Nomor Rekening -->
                            <div class="p-4 bg-white rounded-xl border border-wukirsari-border flex items-center justify-between gap-3 shadow-2xs">
                                <div>
                                    <span class="text-[10px] text-merapiText-muted block font-medium">Nomor Rekening Bank BPD DIY:</span>
                                    <span id="bpdAccountNumberText" class="font-mono font-extrabold text-lg sm:text-xl text-merapiText-primary tracking-wider">
                                        {{ $bpdConfig['account_number'] ?: 'Belum Dikonfigurasi di .env' }}
                                    </span>
                                </div>
                                <button type="button" id="btnCopyBpdAcc" class="px-3.5 py-2 rounded-lg bg-wukirsari-sage text-wukirsari-primary hover:bg-wukirsari-sage/80 font-bold text-xs flex items-center gap-1.5 transition-all cursor-pointer shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                    <span id="btnCopyBpdAccText">Salin Rekening</span>
                                </button>
                            </div>

                            <!-- Baris Atas Nama & Nominal -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                                <div class="p-3 bg-white/90 rounded-xl border border-wukirsari-border">
                                    <span class="text-merapiText-muted block text-[10px]">Atas Nama Pemilik Rekening:</span>
                                    <span class="font-bold text-merapiText-primary block mt-0.5 text-sm">
                                        {{ $bpdConfig['account_name'] ?: 'Jeep Wukirsari Adventure' }}
                                    </span>
                                </div>
                                <div class="p-3 bg-white/90 rounded-xl border border-wukirsari-border flex items-center justify-between">
                                    <div>
                                        <span class="text-merapiText-muted block text-[10px]">Nominal Transfer Wajib:</span>
                                        <span id="bpdNominalText" class="font-extrabold text-wukirsari-primary block text-sm sm:text-base font-mono">
                                            Rp {{ number_format($payableAmount, 0, ',', '.') }}
                                        </span>
                                    </div>
                                    <button type="button" id="btnCopyNominal" class="px-2.5 py-1.5 rounded-lg bg-wukirsari-sage text-wukirsari-primary hover:bg-wukirsari-sage/80 font-semibold text-[11px] transition-all cursor-pointer">
                                        Salin
                                    </button>
                                </div>
                            </div>

                            <p class="text-[11px] text-merapiText-secondary leading-relaxed pt-1">
                                Pastikan melakukan transfer persis sesuai nominal tagihan di atas. Setelah transfer berhasil, unggah foto bukti transfer pada formulir di bawah ini.
                            </p>
                        </div>

                        <!-- FORMULIR PENGUNGGAHAN BUKTI TRANSFER -->
                        <form action="{{ route('booking.manual_transfer', $booking->booking_code) }}" method="POST" enctype="multipart/form-data" class="space-y-4 pt-1">
                            @csrf
                            
                            <h3 class="text-sm font-bold text-merapiText-primary flex items-center gap-2">
                                <svg class="w-4 h-4 text-wukirsari-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                </svg>
                                <span>Formulir Konfirmasi Pengiriman Bukti</span>
                            </h3>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                                <!-- Nama Pemilik Rekening Pengirim -->
                                <div>
                                    <label class="block text-xs font-semibold text-merapiText-primary mb-1">
                                        Nama Pengirim (Sesuai Rekening / Struk) <span class="text-rose-600">*</span>
                                    </label>
                                    <input type="text" name="sender_name" value="{{ old('sender_name', $booking->customer_name) }}" required placeholder="Contoh: Budi Santoso" class="w-full px-3.5 py-2.5 rounded-xl border border-wukirsari-border text-xs text-merapiText-primary focus:outline-none focus:border-wukirsari-primary bg-white shadow-2xs">
                                </div>

                                <!-- Bank Asal Pengirim -->
                                <div>
                                    <label class="block text-xs font-semibold text-merapiText-primary mb-1">
                                        Bank Asal Transfer <span class="text-rose-600">*</span>
                                    </label>
                                    @php
                                        $bankList = [
                                            'Bank BCA',
                                            'Bank BRI',
                                            'Bank Mandiri',
                                            'Bank BNI',
                                            'Bank BSI',
                                            'Bank Jago',
                                            'Bank Seabank',
                                            'Bank BPD DIY',
                                        ];
                                    @endphp
                                    <select name="sender_bank" required class="w-full px-3.5 py-2.5 rounded-xl border border-wukirsari-border text-xs text-merapiText-primary focus:outline-none focus:border-wukirsari-primary bg-white shadow-2xs cursor-pointer">
                                        <option value="" disabled {{ old('sender_bank') ? '' : 'selected' }}>-- Pilih Bank Asal Transfer --</option>
                                        @foreach($bankList as $bank)
                                            <option value="{{ $bank }}" {{ old('sender_bank', 'Bank BPD DIY') === $bank ? 'selected' : '' }}>
                                                {{ $bank }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Input Berkas File Bukti Transfer -->
                            <div>
                                <label class="block text-xs font-semibold text-merapiText-primary mb-1">
                                    Unggah Foto Struk / Tangkapan Layar Mutasi <span class="text-rose-600">*</span>
                                </label>
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-dashed border-wukirsari-border rounded-2xl hover:border-wukirsari-primary bg-white transition-colors cursor-pointer relative" id="dropzoneArea">
                                    <div class="space-y-2 text-center">
                                        <svg class="mx-auto h-10 w-10 text-merapiText-muted" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="text-xs text-merapiText-secondary">
                                            <label for="proof_file" class="relative cursor-pointer font-bold text-wukirsari-primary hover:underline">
                                                <span>Pilih berkas dari perangkat</span>
                                                <input id="proof_file" name="proof_file" type="file" accept="image/jpeg,image/png,image/webp,application/pdf" class="sr-only" required>
                                            </label>
                                            <span class="pl-1">atau tarik berkas ke sini</span>
                                        </div>
                                        <p class="text-[11px] text-merapiText-muted" id="fileInfoText">
                                            Format: JPG, JPEG, PNG, WEBP, atau PDF (Maksimal 2 MB)
                                        </p>
                                    </div>
                                </div>

                                <!-- Pratinjau Gambar Langsung -->
                                <div id="previewContainer" class="hidden mt-3 p-3 bg-slate-50 rounded-xl border border-wukirsari-border flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <img id="imagePreview" src="" alt="Pratinjau Bukti" class="w-14 h-14 rounded-lg object-cover border border-wukirsari-border">
                                        <div>
                                            <span id="previewFilename" class="text-xs font-bold text-merapiText-primary block truncate max-w-xs">Nama file</span>
                                            <span id="previewFilesize" class="text-[10px] text-merapiText-muted">Ukuran file</span>
                                        </div>
                                    </div>
                                    <button type="button" id="btnRemoveFile" class="text-xs text-rose-600 hover:text-rose-800 font-semibold p-1">
                                        Ganti
                                    </button>
                                </div>
                            </div>

                            <!-- Tombol Kirim Bukti -->
                            <button type="submit" class="w-full py-3.5 px-6 rounded-full bg-wukirsari-primary hover:bg-wukirsari-hover text-white font-bold text-xs sm:text-sm flex items-center justify-center gap-2 shadow-md shadow-wukirsari-primary/25 active:scale-98 transition-all cursor-pointer">
                                <span>Kirim Bukti Pembayaran Sekarang →</span>
                            </button>
                        </form>
                    </div>

                    <!-- ============================================== -->
                    <!-- PANEL B: DANA (MIDTRANS DIGITAL GATEWAY)       -->
                    <!-- ============================================== -->
                    <div id="panelDanaDigital" class="hidden space-y-5 pt-2">
                        <div class="p-5 rounded-2xl bg-gradient-to-br from-blue-50/80 via-white to-blue-50/40 border border-blue-200 space-y-4 shadow-2xs">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full bg-blue-600"></span>
                                    <span class="text-xs font-bold text-blue-900 uppercase tracking-wider">
                                        E-Wallet DANA (Midtrans Payment Gateway)
                                    </span>
                                </div>
                                <span class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-900 border border-amber-300 text-[10px] font-bold">
                                    Belum Tersedia
                                </span>
                            </div>

                            <div class="p-4 bg-white rounded-xl border border-blue-100 space-y-2.5 shadow-2xs">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-xl bg-blue-600 text-white flex items-center justify-center font-extrabold text-sm shrink-0 shadow-sm">
                                        DANA
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-bold text-merapiText-primary">Pembayaran Otomatis Dompet Digital DANA</h3>
                                        <p class="text-[11px] text-merapiText-muted mt-0.5">Integrasi Midtrans Core API E-Wallet</p>
                                    </div>
                                </div>
                                <div class="pt-2 border-t border-slate-100 text-xs text-merapiText-secondary space-y-2 leading-relaxed">
                                    <p>
                                        Kanal pembayaran digital menggunakan <strong>DANA</strong> saat ini sedang dalam proses verifikasi dan aktivasi akun merchant Midtrans untuk Jeep Wukirsari Adventure Tour.
                                    </p>
                                    <div class="p-3 bg-amber-50 rounded-xl border border-amber-200 text-amber-900 text-xs flex items-start gap-2.5">
                                        <svg class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                        <div>
                                            <span class="font-bold block">Status Metode: Nonaktif Sementara</span>
                                            <span>Pilihan pembayaran DANA dinonaktifkan sementara hingga proses aktivasi selesai. Silakan gunakan metode <strong>Transfer Bank BPD DIY</strong> atau <strong>Bayar Tunai di Loket (Cash)</strong>.</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Opsi Nonaktif (Disabled) -->
                            <div class="p-4 rounded-xl border border-slate-200 bg-slate-50 flex items-center justify-between opacity-60 cursor-not-allowed">
                                <div class="flex items-center gap-3">
                                    <input type="radio" disabled class="w-4 h-4 text-slate-400">
                                    <div>
                                        <span class="block text-xs font-bold text-slate-700">Akun Saldo DANA Wisatawan</span>
                                        <span class="block text-[11px] text-slate-500">Scan QRIS DANA / Autodebit E-Wallet</span>
                                    </div>
                                </div>
                                <span class="px-2 py-0.5 rounded-md bg-slate-200 text-slate-600 text-[10px] font-bold">Nonaktif</span>
                            </div>

                            <!-- Tombol Alternatif -->
                            <div class="flex flex-col sm:flex-row gap-2 pt-1">
                                <button type="button" onclick="document.getElementById('tabManualBpd').click()" class="flex-1 py-2.5 px-4 rounded-xl bg-wukirsari-primary text-white text-xs font-bold hover:bg-wukirsari-hover transition-colors text-center cursor-pointer shadow-xs">
                                    Gunakan Transfer Bank BPD DIY →
                                </button>
                                <button type="button" onclick="document.getElementById('tabCashLoket').click()" class="flex-1 py-2.5 px-4 rounded-xl bg-wukirsari-sage text-wukirsari-primary border border-wukirsari-primary/30 text-xs font-bold hover:bg-wukirsari-sage/70 transition-colors text-center cursor-pointer">
                                    Pilih Bayar di Loket (Cash) →
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- ============================================== -->
                    <!-- PANEL C: CASH (LOKET JEEP WUKIRSARI)           -->
                    <!-- ============================================== -->
                    <div id="panelCashLoket" class="hidden space-y-5 pt-2">
                        
                        @if($isCashActive)
                        <!-- STATUS JIKA SUDAH MEMILIH BAYAR TUNAI (SIAGA LOKET) -->
                        <div class="p-5 rounded-2xl bg-gradient-to-br from-emerald-50/80 via-white to-emerald-50/40 border border-emerald-300 space-y-4 shadow-2xs">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full bg-emerald-600 animate-ping"></span>
                                    <span class="text-xs font-bold text-emerald-900 uppercase tracking-wider">
                                        Menunggu Pembayaran Tunai di Loket
                                    </span>
                                </div>
                                <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 border border-emerald-300 text-[10px] font-bold">
                                    Siaga Kasir
                                </span>
                            </div>

                            <div class="p-4 bg-white rounded-xl border border-emerald-200 space-y-3 shadow-2xs">
                                <div>
                                    <span class="text-[10px] text-merapiText-muted block font-medium">Tunjukkan Kode Booking Ini ke Petugas Kasir Loket:</span>
                                    <div class="flex items-center justify-between gap-3 mt-1">
                                        <span id="cashBookingCodeText" class="font-mono font-extrabold text-xl sm:text-2xl text-wukirsari-primary tracking-wider">
                                            {{ $booking->booking_code }}
                                        </span>
                                        <button type="button" id="btnCopyCashCode" class="px-3 py-1.5 rounded-lg bg-wukirsari-sage text-wukirsari-primary font-bold text-xs flex items-center gap-1 cursor-pointer">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                            <span id="btnCopyCashCodeText">Salin Kode</span>
                                        </button>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs pt-2 border-t border-slate-100">
                                    <div>
                                        <span class="text-merapiText-muted block text-[10px]">Nominal Tunai Wajib Diserahkan:</span>
                                        <span class="font-extrabold text-wukirsari-primary text-base font-mono">
                                            Rp {{ number_format($activeCashPayment->gross_amount, 0, ',', '.') }}
                                        </span>
                                    </div>
                                    <div>
                                        <span class="text-merapiText-muted block text-[10px]">Status Tiket Wisata:</span>
                                        <span class="font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-md inline-block mt-0.5">
                                            Belum Aktif (Menunggu Kasir)
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="p-4 rounded-xl bg-wukirsari-sage/40 border border-wukirsari-border flex items-start gap-3 text-xs text-merapiText-secondary">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-600 animate-ping mt-1 shrink-0"></span>
                                <p class="leading-relaxed">
                                    Setelah uang tunai diserahkan kepada kasir loket basecamp, petugas akan menekan tombol konfirmasi di sistem dan <strong>halaman ini akan otomatis dialihkan ke E-Tiket resmi</strong> tanpa perlu dimuat ulang.
                                </p>
                            </div>
                        </div>

                        @else
                        <!-- FORMULIR KONFIRMASI PILIHAN BAYAR TUNAI (CASH) -->
                        <div class="p-5 rounded-2xl bg-gradient-to-br from-emerald-50/60 via-white to-wukirsari-cream/40 border border-wukirsari-border space-y-4 shadow-2xs">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full bg-wukirsari-primary"></span>
                                    <span class="text-xs font-bold text-wukirsari-primary uppercase tracking-wider">
                                        Pembayaran Langsung di Loket Basecamp
                                    </span>
                                </div>
                                <span class="text-[10px] text-merapiText-muted font-medium">
                                    Basecamp Jeep Wukirsari
                                </span>
                            </div>

                            <div class="p-4 bg-white rounded-xl border border-wukirsari-border space-y-3 shadow-2xs">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="text-[10px] text-merapiText-muted block">Total Tagihan Tunai di Loket:</span>
                                        <span class="font-mono font-extrabold text-lg sm:text-xl text-wukirsari-primary">
                                            Rp {{ number_format($payableAmount, 0, ',', '.') }}
                                        </span>
                                    </div>
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-wukirsari-sage text-wukirsari-primary">
                                        {{ $booking->payment_mode === 'FULL' ? 'Lunas 100%' : 'DP 30%' }}
                                    </span>
                                </div>

                                <div class="text-xs text-merapiText-secondary space-y-1.5 pt-2 border-t border-slate-100">
                                    <div class="flex justify-between">
                                        <span>Kode Reservasi:</span>
                                        <span class="font-mono font-bold text-merapiText-primary">{{ $booking->booking_code }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Jadwal Keberangkatan:</span>
                                        <span class="font-semibold text-merapiText-primary">{{ $booking->trip_date->format('d M Y') }} ({{ $booking->slot->slot_label }})</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Alokasi Armada:</span>
                                        <span class="font-semibold text-merapiText-primary">{{ $booking->jeep_quantity }} Unit Jeep ({{ $booking->passenger_count }} Orang)</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Petunjuk Alur Kasir -->
                            <div class="p-4 rounded-xl bg-wukirsari-cream/80 border border-wukirsari-border space-y-2 text-xs">
                                <span class="font-bold text-merapiText-primary block">
                                    Alur Pembayaran Tunai di Loket Kasir:
                                </span>
                                <ol class="list-decimal list-inside space-y-1.5 text-merapiText-secondary leading-relaxed">
                                    <li>Klik tombol <strong>"Konfirmasi Bayar Tunai di Loket"</strong> di bawah untuk mengunci reservasi.</li>
                                    <li>Tiba di Basecamp Jeep Wukirsari minimal 15–30 menit sebelum jadwal sesi wisata Anda.</li>
                                    <li>Tunjukkan Kode Booking <strong>{{ $booking->booking_code }}</strong> kepada petugas kasir loket.</li>
                                    <li>Lakukan pembayaran tunai sebesar <strong>Rp {{ number_format($payableAmount, 0, ',', '.') }}</strong>.</li>
                                    <li>Petugas kasir akan memverifikasi di sistem dan E-Tiket resmi Anda langsung aktif seketika.</li>
                                </ol>
                            </div>

                            <!-- Form Konfirmasi Cash -->
                            <form action="{{ route('booking.cash', $booking->booking_code) }}" method="POST" class="pt-1">
                                @csrf
                                <button type="submit" class="w-full py-3.5 px-6 rounded-full bg-wukirsari-primary hover:bg-wukirsari-hover text-white font-bold text-xs sm:text-sm flex items-center justify-center gap-2 shadow-md shadow-wukirsari-primary/25 active:scale-98 transition-all cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    <span>Konfirmasi Bayar Tunai di Loket Kasir →</span>
                                </button>
                            </form>
                        </div>
                        @endif

                    </div>
                </div>
                @endif

            </div>

            <!-- ========================================== -->
            <!-- KOLOM KANAN (5 SPAN): DETAIL TRANSAKSI     -->
            <!-- ========================================== -->
            <div class="lg:col-span-5 space-y-6 lg:sticky lg:top-28">
                
                <!-- KARTU INFORMASI PROSES PEMBAYARAN -->
                <div class="bg-white rounded-3xl border border-wukirsari-border p-6 sm:p-7 shadow-xs space-y-5">
                    
                    <div class="flex items-center justify-between border-b border-wukirsari-border pb-3">
                        <h2 class="text-base font-bold text-merapiText-primary font-display flex items-center gap-2">
                            <svg class="w-5 h-5 text-wukirsari-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Informasi Transaksi</span>
                        </h2>
                        <span class="text-xs font-semibold text-wukirsari-primary bg-wukirsari-sage px-2.5 py-0.5 rounded-full flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-wukirsari-primary animate-pulse"></span>
                            <span>Sistem Siaga</span>
                        </span>
                    </div>

                    <!-- Alert Notifikasi Feedback Javascript -->
                    <div id="paymentAlertBox" class="hidden p-3.5 rounded-2xl text-xs font-medium border"></div>

                    @if($isWaiting)
                    <!-- KANAN: STATUS MENUNGGU VERIFIKASI -->
                    <div class="space-y-4">
                        <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 space-y-2">
                            <span class="text-xs font-bold text-amber-900 block uppercase tracking-wider">
                                Status Peninjauan:
                            </span>
                            <p class="text-xs text-amber-950 leading-relaxed">
                                Pesanan Anda tidak akan dibatalkan otomatis selama bukti pembayaran Anda sedang diperiksa oleh staf admin basecamp.
                            </p>
                        </div>

                        <div class="p-4 rounded-2xl bg-wukirsari-cream/60 border border-wukirsari-border space-y-2 text-xs">
                            <div class="flex justify-between">
                                <span class="text-merapiText-secondary">Nomor WhatsApp:</span>
                                <span class="font-bold text-merapiText-primary">{{ $booking->customer_phone }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-merapiText-secondary">Tanggal Wisata:</span>
                                <span class="font-bold text-merapiText-primary">{{ $booking->trip_date->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-merapiText-secondary">Alokasi Armada:</span>
                                <span class="font-bold text-merapiText-primary">{{ $booking->jeep_quantity }} Unit Jeep</span>
                            </div>
                        </div>

                        <!-- Indikator Polling Live -->
                        <div class="p-3.5 rounded-xl bg-emerald-50/90 border border-emerald-200 flex items-center gap-3">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-600 animate-ping shrink-0"></span>
                            <div class="text-[11px] text-emerald-900 leading-tight">
                                <span class="font-bold block">Pemeriksaan Otomatis Aktif</span>
                                <span>Layar Anda akan otomatis beralih saat admin menyetujui pembayaran.</span>
                            </div>
                        </div>
                    </div>

                    @else

                    <!-- KANAN: PETUNJUK TRANSFER BPD DIY (Aktif saat tab BPD dipilih) -->
                    <div id="panelBpdGuide" class="space-y-4">
                        <div class="p-4 rounded-2xl bg-wukirsari-cream/60 border border-wukirsari-border space-y-2.5 text-xs">
                            <span class="text-xs font-bold text-wukirsari-primary block uppercase tracking-wider">
                                Langkah Pembayaran Manual BPD DIY:
                            </span>
                            <ol class="list-decimal list-inside space-y-1.5 text-merapiText-secondary leading-relaxed">
                                <li>Buka aplikasi m-Banking atau kunjungi ATM bank Anda.</li>
                                <li>Pilih menu <strong>Transfer Antar Bank</strong> (Kode Bank BPD DIY: <code>112</code>).</li>
                                <li>Masukkan nomor rekening yang tertera di samping.</li>
                                <li>Kirim nominal tepat: <strong>Rp {{ number_format($payableAmount, 0, ',', '.') }}</strong>.</li>
                                <li>Simpan bukti transfer dan unggah melalui formulir di samping.</li>
                            </ol>
                        </div>
                    </div>

                    <!-- KANAN: INFORMASI KANAL DANA (Aktif saat tab DANA dipilih) -->
                    <div id="panelDanaGuide" class="hidden space-y-4">
                        <div class="p-4 rounded-2xl bg-blue-50/70 border border-blue-200 space-y-2 text-xs">
                            <span class="text-xs font-bold text-blue-900 block uppercase tracking-wider">
                                Kanal Dompet Digital DANA
                            </span>
                            <p class="text-merapiText-secondary leading-relaxed">
                                Pembayaran digital instan melalui DANA terintegrasi otomatis dengan Midtrans. Saat ini sedang dalam tahap akhir aktivasi akun merchant.
                            </p>
                            <p class="text-[11px] text-amber-800 font-medium">
                                Untuk kemudahan Anda, gunakan metode Transfer Bank BPD DIY atau Bayar Tunai di Loket Kasir.
                            </p>
                        </div>
                    </div>

                    <!-- KANAN: PANDUAN BAYAR TUNAI LOKET (Aktif saat tab Cash dipilih) -->
                    <div id="panelCashGuide" class="hidden space-y-4">
                        <div class="p-4 rounded-2xl bg-emerald-50/70 border border-emerald-200 space-y-2.5 text-xs">
                            <span class="text-xs font-bold text-emerald-900 block uppercase tracking-wider">
                                Pembayaran Kasir Basecamp Wukirsari:
                            </span>
                            <ul class="list-disc list-inside space-y-1.5 text-merapiText-secondary leading-relaxed">
                                <li>Lokasi: <strong>Loket Pendaftaran & Kasir Basecamp Jeep Wukirsari</strong>.</li>
                                <li>Siapkan uang tunai pas: <strong>Rp {{ number_format($payableAmount, 0, ',', '.') }}</strong>.</li>
                                <li>Tunjukkan <strong>Kode Booking: {{ $booking->booking_code }}</strong> kepada kasir loket.</li>
                                <li>E-Tiket resmi Anda akan otomatis terbit begitu kasir mengonfirmasi penerimaan uang tunai di sistem.</li>
                            </ul>
                        </div>
                    </div>

                    <!-- KANAN: MIDTRANS CHARGE PROMPT (Aktif saat tab Midtrans dipilih) -->
                    <div id="panelMidtransActions" class="hidden space-y-4">
                        <div class="p-4 rounded-2xl bg-wukirsari-cream/60 border border-wukirsari-border space-y-2">
                            <span class="text-xs font-bold text-wukirsari-primary block uppercase tracking-wider">
                                Konfirmasi Pilihan Metode Digital
                            </span>
                            <p class="text-xs text-merapiText-secondary leading-relaxed" id="selectedMethodSummaryText">
                                Anda memilih metode Virtual Account. Klik tombol di bawah ini untuk menerbitkan kode pembayaran resmi dari Midtrans.
                            </p>
                        </div>

                        <button type="button" id="btnInitiateCharge" class="w-full py-4 px-6 rounded-full bg-wukirsari-primary hover:bg-wukirsari-hover text-white font-bold text-sm flex items-center justify-center gap-2 shadow-lg shadow-wukirsari-primary/25 active:scale-98 transition-all cursor-pointer">
                            <span id="btnChargeText">Bayar Sekarang (Rp {{ number_format($payableAmount, 0, ',', '.') }}) →</span>
                            <svg id="btnChargeSpinner" class="hidden w-5 h-5 text-white animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- KANAN: SETELAH CHARGE MIDTRANS TERBIT -->
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

                            <div class="p-3.5 bg-white rounded-xl border border-wukirsari-border flex items-center justify-between gap-2 shadow-2xs">
                                <div>
                                    <span class="text-[10px] text-merapiText-muted block">Nomor Virtual Account:</span>
                                    <span id="vaNumberDisplay" class="font-mono font-extrabold text-base sm:text-lg text-merapiText-primary tracking-wider">
                                        ----------------
                                    </span>
                                </div>
                                <button type="button" id="btnCopyVa" class="px-3.5 py-1.5 rounded-lg bg-wukirsari-sage text-wukirsari-primary hover:bg-wukirsari-sage/80 font-bold text-xs flex items-center gap-1 transition-all cursor-pointer">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                    <span id="btnCopyVaText">Salin</span>
                                </button>
                            </div>

                            <div class="text-[11px] text-merapiText-secondary space-y-1 pt-1">
                                <div class="flex justify-between">
                                    <span>Atas Nama:</span>
                                    <span class="font-semibold text-merapiText-primary">Jeep Wisata Wukirsari</span>
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
                                    <button type="button" id="btnCopyBillerCode" class="px-3 py-1 rounded-md bg-wukirsari-sage text-wukirsari-primary text-xs font-bold cursor-pointer">Salin</button>
                                </div>

                                <div class="p-3 bg-white rounded-xl border border-wukirsari-border flex items-center justify-between">
                                    <div>
                                        <span class="text-[10px] text-merapiText-muted block">Nomor Tagihan (Bill Key):</span>
                                        <span id="mandiriBillKeyDisplay" class="font-mono font-bold text-sm text-merapiText-primary">----------</span>
                                    </div>
                                    <button type="button" id="btnCopyBillKey" class="px-3 py-1 rounded-md bg-wukirsari-sage text-wukirsari-primary text-xs font-bold cursor-pointer">Salin</button>
                                </div>
                            </div>
                        </div>

                        <!-- 2C: Tampilan QRIS Dinamis -->
                        <div id="boxQris" class="hidden text-center space-y-3 p-4 rounded-2xl bg-slate-50 border border-wukirsari-border">
                            <span class="text-xs text-merapiText-muted uppercase tracking-wider font-semibold block">
                                Pindai QRIS Dinamis
                            </span>
                            <div class="w-48 h-48 mx-auto bg-white p-2.5 rounded-2xl border border-wukirsari-border shadow-2xs flex items-center justify-center">
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
                    @endif

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
                        <span>Transaksi Terenkripsi & Terverifikasi Resmi</span>
                    </div>

                </div>

            </div>

        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const bookingCode = '{{ $booking->booking_code }}';
        const isWaitingState = {{ $isWaiting ? 'true' : 'false' }};
        const statusUrl = '{{ route('booking.status', $booking->booking_code) }}';
        const chargeUrl = '{{ route('booking.charge', $booking->booking_code) }}';
        const activePaymentUrl = '{{ route('booking.active_payment', $booking->booking_code) }}';
        const csrfToken = '{{ csrf_token() }}';

        // 1. Tab Switcher Kategori Metode (3 Pilihan: BPD DIY, DANA Midtrans, Cash Loket)
        const tabManualBpd = document.getElementById('tabManualBpd');
        const tabDanaMidtrans = document.getElementById('tabDanaMidtrans');
        const tabCashLoket = document.getElementById('tabCashLoket');

        const panelBpdManual = document.getElementById('panelBpdManual');
        const panelDanaDigital = document.getElementById('panelDanaDigital');
        const panelCashLoket = document.getElementById('panelCashLoket');

        const panelBpdGuide = document.getElementById('panelBpdGuide');
        const panelDanaGuide = document.getElementById('panelDanaGuide');
        const panelCashGuide = document.getElementById('panelCashGuide');
        const panelMidtransActions = document.getElementById('panelMidtransActions');

        function switchPaymentTab(activeTab) {
            // Reset tab styles
            [tabManualBpd, tabDanaMidtrans, tabCashLoket].forEach(tab => {
                if (!tab) return;
                tab.classList.remove('border-wukirsari-primary', 'bg-wukirsari-sage/35', 'ring-1', 'ring-wukirsari-primary');
                tab.classList.add('border-wukirsari-border');
            });

            // Sembunyikan semua panel
            [panelBpdManual, panelDanaDigital, panelCashLoket].forEach(panel => {
                if (panel) panel.classList.add('hidden');
            });
            [panelBpdGuide, panelDanaGuide, panelCashGuide, panelMidtransActions].forEach(guide => {
                if (guide) guide.classList.add('hidden');
            });

            if (activeTab === 'bpd') {
                if (tabManualBpd) {
                    tabManualBpd.classList.add('border-wukirsari-primary', 'bg-wukirsari-sage/35', 'ring-1', 'ring-wukirsari-primary');
                    tabManualBpd.classList.remove('border-wukirsari-border');
                }
                if (panelBpdManual) panelBpdManual.classList.remove('hidden');
                if (panelBpdGuide) panelBpdGuide.classList.remove('hidden');
            } else if (activeTab === 'dana') {
                if (tabDanaMidtrans) {
                    tabDanaMidtrans.classList.add('border-wukirsari-primary', 'bg-wukirsari-sage/35', 'ring-1', 'ring-wukirsari-primary');
                    tabDanaMidtrans.classList.remove('border-wukirsari-border');
                }
                if (panelDanaDigital) panelDanaDigital.classList.remove('hidden');
                if (panelDanaGuide) panelDanaGuide.classList.remove('hidden');
            } else if (activeTab === 'cash') {
                if (tabCashLoket) {
                    tabCashLoket.classList.add('border-wukirsari-primary', 'bg-wukirsari-sage/35', 'ring-1', 'ring-wukirsari-primary');
                    tabCashLoket.classList.remove('border-wukirsari-border');
                }
                if (panelCashLoket) panelCashLoket.classList.remove('hidden');
                if (panelCashGuide) panelCashGuide.classList.remove('hidden');
            }
        }

        if (tabManualBpd) tabManualBpd.addEventListener('click', () => switchPaymentTab('bpd'));
        if (tabDanaMidtrans) tabDanaMidtrans.addEventListener('click', () => switchPaymentTab('dana'));
        if (tabCashLoket) tabCashLoket.addEventListener('click', () => switchPaymentTab('cash'));

        const isCashActiveInitially = {{ $isCashActive ? 'true' : 'false' }};
        if (isCashActiveInitially) {
            switchPaymentTab('cash');
        }

        // 2. Client-side File Upload Preview
        const fileInput = document.getElementById('proof_file');
        const previewContainer = document.getElementById('previewContainer');
        const imagePreview = document.getElementById('imagePreview');
        const previewFilename = document.getElementById('previewFilename');
        const previewFilesize = document.getElementById('previewFilesize');
        const btnRemoveFile = document.getElementById('btnRemoveFile');

        if (fileInput) {
            fileInput.addEventListener('change', function () {
                const file = this.files[0];
                if (!file) return;

                previewFilename.innerText = file.name;
                previewFilesize.innerText = (file.size / 1024 / 1024).toFixed(2) + ' MB';

                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        imagePreview.src = e.target.result;
                        imagePreview.classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                } else {
                    imagePreview.classList.add('hidden');
                }

                previewContainer.classList.remove('hidden');
            });

            if (btnRemoveFile) {
                btnRemoveFile.addEventListener('click', function () {
                    fileInput.value = '';
                    previewContainer.classList.add('hidden');
                    imagePreview.src = '';
                });
            }
        }

        // 3. Tombol Salin Cepat Rekening & Nominal BPD DIY
        function setupInstantCopy(btnId, sourceTextId, textToCopyOverride = null) {
            const btn = document.getElementById(btnId);
            if (!btn) return;
            btn.addEventListener('click', function () {
                let text = textToCopyOverride;
                if (!text && sourceTextId) {
                    const el = document.getElementById(sourceTextId);
                    if (el) text = el.innerText.trim();
                }
                if (!text) return;

                // Hilangkan teks non-angka jika nomor rekening/rupiah
                const cleanText = text.replace(/^Rp\s*/i, '').replace(/\./g, '').trim();

                navigator.clipboard.writeText(cleanText).then(() => {
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

        setupInstantCopy('btnCopyBpdAcc', 'bpdAccountNumberText');
        setupInstantCopy('btnCopyNominal', 'bpdNominalText');
        setupInstantCopy('btnCopyCashCode', 'cashBookingCodeText');

        // 4. Polling Status Pembayaran Otomatis (Baik untuk BPD Manual maupun Midtrans)
        let pollingInterval = null;
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

        // Selalu jalankan polling agar saat admin menyetujui, customer langsung diarahkan ke e-tiket
        startPolling();

        // 5. Logika Midtrans Core API (Tetap 100% Utuh dan Berfungsi)
        const methodCards = document.querySelectorAll('.payment-method-card');
        const btnCharge = document.getElementById('btnInitiateCharge');
        const btnChargeText = document.getElementById('btnChargeText');
        const btnChargeSpinner = document.getElementById('btnChargeSpinner');
        const alertBox = document.getElementById('paymentAlertBox');
        const panelInitial = document.getElementById('panelMidtransActions');
        const panelActive = document.getElementById('panelActiveTransaction');
        const timerDisplay = document.getElementById('countdownTimer');

        const boxBankVa = document.getElementById('boxBankVa');
        const boxMandiriBill = document.getElementById('boxMandiriBill');
        const boxQris = document.getElementById('boxQris');
        const boxEwallet = document.getElementById('boxEwallet');

        let selectedMethod = 'bca_va';
        let countdownInterval = null;

        function showAlert(msg, isError = true) {
            if (!alertBox) return;
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

        function renderPaymentDetails(data) {
            if (panelInitial) panelInitial.classList.add('hidden');
            if (panelActive) panelActive.classList.remove('hidden');

            if (boxBankVa) boxBankVa.classList.add('hidden');
            if (boxMandiriBill) boxMandiriBill.classList.add('hidden');
            if (boxQris) boxQris.classList.add('hidden');
            if (boxEwallet) boxEwallet.classList.add('hidden');

            if (data.va_number && boxBankVa) {
                boxBankVa.classList.remove('hidden');
                document.getElementById('vaBankNameLabel').innerText = (data.payment_method || 'Virtual Account').toUpperCase().replace('_', ' ');
                document.getElementById('vaOrderIdLabel').innerText = data.order_id || '';
                document.getElementById('vaNumberDisplay').innerText = data.va_number;
                document.getElementById('vaGrossAmountText').innerText = formatCurrency(data.gross_amount);
            } else if (data.biller_code && data.bill_key && boxMandiriBill) {
                boxMandiriBill.classList.remove('hidden');
                document.getElementById('mandiriBillerCodeDisplay').innerText = data.biller_code;
                document.getElementById('mandiriBillKeyDisplay').innerText = data.bill_key;
            } else if ((data.qr_url || data.qr_string) && boxQris) {
                boxQris.classList.remove('hidden');
                if (data.qr_url) {
                    document.getElementById('qrisImageDisplay').src = data.qr_url;
                }
            } else if (data.deeplink_url && boxEwallet) {
                boxEwallet.classList.remove('hidden');
                document.getElementById('btnEwalletDeeplink').href = data.deeplink_url;
            }

            if (data.expires_at) {
                startCountdown(new Date(data.expires_at).getTime());
            }
        }

        function startCountdown(targetTime) {
            if (countdownInterval) clearInterval(countdownInterval);

            function updateTimer() {
                const now = new Date().getTime();
                const diff = targetTime - now;

                if (diff <= 0) {
                    if (timerDisplay) timerDisplay.innerText = 'Kedaluwarsa';
                    clearInterval(countdownInterval);
                    showAlert('Batas waktu pembayaran telah habis. Silakan muat ulang atau pilih metode lain.');
                    return;
                }

                const mins = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                const secs = Math.floor((diff % (1000 * 60)) / 1000);
                if (timerDisplay) timerDisplay.innerText = `${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
            }

            updateTimer();
            countdownInterval = setInterval(updateTimer, 1000);
        }

        if (btnCharge) {
            btnCharge.addEventListener('click', async function () {
                btnCharge.disabled = true;
                btnChargeText.innerText = 'Menghubungi Gateway...';
                btnChargeSpinner.classList.remove('hidden');
                if (alertBox) alertBox.classList.add('hidden');

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
        }

        setupInstantCopy('btnCopyVa', 'vaNumberDisplay');
        setupInstantCopy('btnCopyBillerCode', 'mandiriBillerCodeDisplay');
        setupInstantCopy('btnCopyBillKey', 'mandiriBillKeyDisplay');

        // Muat transaksi aktif jika customer sebelumnya sudah charge Midtrans
        if (!isWaitingState) {
            async function loadActivePaymentOnLoad() {
                try {
                    const res = await fetch(activePaymentUrl, {
                        headers: { 'Accept': 'application/json' }
                    });
                    const json = await res.json();
                    if (json.status === 'success' && json.data) {
                        // Switch ke tab digital jika ada active transaction
                        if (tabDigitalMidtrans) tabDigitalMidtrans.click();
                        renderPaymentDetails(json.data);
                    }
                } catch (e) {
                    // Abaikan jika tidak ada active payment
                }
            }
            loadActivePaymentOnLoad();
        }
    });
</script>
@endsection
