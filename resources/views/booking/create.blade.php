@extends('layouts.app')

@section('title', 'Form Booking Paket Wisata Jeep Wukirsari | Adventure Tour')

@section('hide_floating_cta', true)

@section('content')
<div class="min-h-screen bg-wukirsari-cream text-merapiText-primary py-8 sm:py-12 font-sans relative">

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- 1. INDIKATOR TAHAP PEMESANAN (HORIZONTAL & TERPUSAT) -->
        <div class="max-w-3xl mx-auto mb-10 px-2">
            <div class="flex items-center justify-between">
                
                <!-- Tahap 1: Form Booking (Aktif) -->
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-wukirsari-primary text-white flex items-center justify-center shadow-sm shrink-0">
                        <!-- Icon file-text -->
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9z"/>
                        </svg>
                    </div>
                    <span class="text-xs sm:text-sm font-bold text-wukirsari-primary font-display whitespace-nowrap">
                        Form Booking
                    </span>
                </div>

                <!-- Garis Penghubung 1-2 -->
                <div class="flex-1 h-[1.5px] bg-wukirsari-border mx-2 sm:mx-4"></div>

                <!-- Tahap 2: Detail Pesanan -->
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-white border border-wukirsari-border text-merapiText-muted flex items-center justify-center shrink-0">
                        <!-- Icon user-round -->
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0zM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                        </svg>
                    </div>
                    <span class="text-xs sm:text-sm font-medium text-merapiText-muted hidden sm:inline whitespace-nowrap font-display">
                        Detail Pesanan
                    </span>
                </div>

                <!-- Garis Penghubung 2-3 -->
                <div class="flex-1 h-[1.5px] bg-wukirsari-border mx-2 sm:mx-4"></div>

                <!-- Tahap 3: Konfirmasi -->
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-white border border-wukirsari-border text-merapiText-muted flex items-center justify-center shrink-0">
                        <!-- Icon shield-check -->
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                        </svg>
                    </div>
                    <span class="text-xs sm:text-sm font-medium text-merapiText-muted hidden sm:inline whitespace-nowrap font-display">
                        Konfirmasi
                    </span>
                </div>

                <!-- Garis Penghubung 3-4 -->
                <div class="flex-1 h-[1.5px] bg-wukirsari-border mx-2 sm:mx-4"></div>

                <!-- Tahap 4: Pembayaran -->
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-white border border-wukirsari-border text-merapiText-muted flex items-center justify-center shrink-0">
                        <!-- Icon credit-card -->
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 2.25 19.5z"/>
                        </svg>
                    </div>
                    <span class="text-xs sm:text-sm font-medium text-merapiText-muted hidden sm:inline whitespace-nowrap font-display">
                        Pembayaran
                    </span>
                </div>

            </div>
        </div>

        <!-- 2. LAYOUT UTAMA DUA KOLOM (~60:40 | ~730px & ~390px) -->
        <div class="flex flex-col lg:flex-row items-start justify-center gap-6 lg:gap-8">
            
            <!-- KOLOM KIRI: FORM BOOKING (SEKITAR 730px) -->
            <div class="w-full lg:flex-1 lg:max-w-[730px]">
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-wukirsari-border shadow-xs">
                    
                    <!-- Header Form Booking -->
                    <div class="flex items-start gap-4 mb-6 pb-5 border-b border-wukirsari-border">
                        <div class="w-12 h-12 rounded-2xl bg-wukirsari-sage text-wukirsari-primary flex items-center justify-center shrink-0 shadow-xs">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9z"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-extrabold text-merapiText-primary font-display tracking-tight">
                                Form Booking
                            </h1>
                            <p class="text-xs sm:text-sm text-merapiText-secondary mt-1">
                                Lengkapi data untuk melanjutkan pemesanan paket wisata Jeep Wukirsari.
                            </p>
                        </div>
                    </div>

                    <!-- Alert Validasi Backend -->
                    @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-2xl text-xs text-red-700">
                        <div class="font-bold mb-1 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Periksa kembali isian formulir Anda:</span>
                        </div>
                        <ul class="list-disc pl-5 space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <!-- Subjudul Informasi Pemesanan -->
                    <h2 class="text-base font-bold text-merapiText-primary font-display mb-5 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-wukirsari-primary"></span>
                        <span>Informasi Pemesanan</span>
                    </h2>

                    <form action="{{ route('booking.store') }}" method="POST" id="bookingForm" class="space-y-5">
                        @csrf

                        <!-- Hidden fields untuk kalkulasi kapasitas dan penjemputan -->
                        <input type="hidden" name="jeep_quantity" id="input_jeep_quantity" value="{{ old('jeep_quantity', 1) }}">
                        <input type="hidden" name="arrival_type" id="input_arrival_type" value="self_drive">
                        <input type="hidden" name="companion_count" value="1">
                        <input type="hidden" name="payment_mode" id="input_payment_mode" value="FULL">

                        <!-- A. NAMA LENGKAP (LEBAR PENUH) -->
                        <div>
                            <label for="customer_name" class="block text-xs font-bold text-merapiText-primary mb-1.5">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-merapiText-muted">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0zM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                                    </svg>
                                </div>
                                <input type="text" name="customer_name" id="customer_name" required value="{{ old('customer_name') }}" placeholder="Contoh: Andi Pratama" class="w-full pl-10 pr-4 py-2.5 sm:py-3 rounded-xl border border-wukirsari-border bg-white text-xs sm:text-sm text-merapiText-primary placeholder-slate-400 focus:outline-none focus:border-wukirsari-primary focus:ring-1 focus:ring-wukirsari-primary transition-colors">
                            </div>
                            @error('customer_name')
                                <p class="text-[11px] text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- BARIS 2: B. NO. WHATSAPP & C. TANGGAL BOOKING -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            
                            <!-- B. Nomor WhatsApp -->
                            <div>
                                <label for="customer_phone" class="block text-xs font-bold text-merapiText-primary mb-1.5">
                                    No. WhatsApp <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-merapiText-muted">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25z"/>
                                        </svg>
                                    </div>
                                    <input type="tel" name="customer_phone" id="customer_phone" required value="{{ old('customer_phone') }}" placeholder="Contoh: 081234567890" class="w-full pl-10 pr-4 py-2.5 sm:py-3 rounded-xl border border-wukirsari-border bg-white text-xs sm:text-sm text-merapiText-primary placeholder-slate-400 focus:outline-none focus:border-wukirsari-primary focus:ring-1 focus:ring-wukirsari-primary transition-colors">
                                </div>
                                @error('customer_phone')
                                    <p class="text-[11px] text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- C. Tanggal Booking -->
                            <div>
                                <label for="trip_date" class="block text-xs font-bold text-merapiText-primary mb-1.5">
                                    Tanggal Booking <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-merapiText-muted">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/>
                                        </svg>
                                    </div>
                                    <input type="date" name="trip_date" id="trip_date" required min="{{ date('Y-m-d') }}" value="{{ old('trip_date', date('Y-m-d', strtotime('+1 day'))) }}" class="w-full pl-10 pr-4 py-2.5 sm:py-3 rounded-xl border border-wukirsari-border bg-white text-xs sm:text-sm text-merapiText-primary focus:outline-none focus:border-wukirsari-primary focus:ring-1 focus:ring-wukirsari-primary transition-colors cursor-pointer">
                                </div>
                                @error('trip_date')
                                    <p class="text-[11px] text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>

                        <!-- BARIS 3: D. WAKTU KEBERANGKATAN & E. JUMLAH ORANG (MANUAL INPUT) -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            
                            <!-- D. Waktu Keberangkatan -->
                            <div>
                                <label for="slot_id" class="block text-xs font-bold text-merapiText-primary mb-1.5">
                                    Waktu Keberangkatan <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-merapiText-muted">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/>
                                        </svg>
                                    </div>
                                    <select name="slot_id" id="slot_id" required class="appearance-none w-full pl-10 pr-10 py-2.5 sm:py-3 rounded-xl border border-wukirsari-border bg-white text-xs sm:text-sm text-merapiText-primary focus:outline-none focus:border-wukirsari-primary focus:ring-1 focus:ring-wukirsari-primary transition-colors cursor-pointer">
                                        @foreach($slots as $slot)
                                            @php
                                                $shortTime = date('H:i', strtotime($slot->start_time)) . ' - ' . date('H:i', strtotime($slot->end_time)) . ' WIB';
                                            @endphp
                                            <option value="{{ $slot->id }}" data-time="{{ $shortTime }}" {{ (old('slot_id', $loop->iteration == 2 ? $slot->id : null) == $slot->id) ? 'selected' : '' }}>
                                                {{ $slot->slot_label }} ({{ $shortTime }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-merapiText-muted">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- E. Jumlah Orang (Manual Input Angka Sesuai Aturan) -->
                            <div>
                                <div class="flex items-center justify-between mb-1.5">
                                    <label for="passenger_count" class="block text-xs font-bold text-merapiText-primary">
                                        Jumlah Orang <span class="text-red-500">*</span>
                                    </label>
                                    <span class="text-[11px] text-merapiText-muted font-medium">Input Manual Angka</span>
                                </div>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-merapiText-muted">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0z"/>
                                        </svg>
                                    </div>
                                    <input type="number" name="passenger_count" id="passenger_count" min="1" max="50" required value="{{ old('passenger_count', 4) }}" placeholder="Ketik jumlah orang (contoh: 4)" class="w-full pl-10 pr-4 py-2.5 sm:py-3 rounded-xl border border-wukirsari-border bg-white text-xs sm:text-sm text-merapiText-primary font-bold placeholder-slate-400 focus:outline-none focus:border-wukirsari-primary focus:ring-1 focus:ring-wukirsari-primary transition-colors">
                                </div>
                                @error('passenger_count')
                                    <p class="text-[11px] text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>

                        <!-- 6. KETENTUAN KAPASITAS JEEP & OPSI ANAK-ANAK -->
                        <div class="p-3.5 rounded-2xl bg-wukirsari-sage/40 border border-wukirsari-border space-y-2">
                            <div class="flex items-start gap-2 text-xs text-merapiText-secondary">
                                <svg class="w-4 h-4 text-wukirsari-primary shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>
                                    <strong>Ketentuan Kapasitas Jeep:</strong> Kapasitas normal maksimal <strong>4 orang dewasa</strong> per armada. Jika peserta berjumlah <strong>5 orang</strong>, hanya diperbolehkan jika 1 peserta merupakan <strong>anak-anak</strong> demi kenyamanan dan keselamatan di jalur offroad.
                                </span>
                            </div>

                            <!-- Field Jumlah Anak (Opsional untuk komposisi 5 orang) -->
                            <div class="pt-2 border-t border-wukirsari-border/60 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                <label for="child_count" class="text-xs font-semibold text-merapiText-primary flex items-center gap-1.5">
                                    <span>Dari total peserta, berapa jumlah anak-anak? (Opsional):</span>
                                </label>
                                <div class="w-24">
                                    <input type="number" name="child_count" id="child_count" min="0" max="10" value="{{ old('child_count', 0) }}" class="w-full px-3 py-1.5 rounded-lg border border-wukirsari-border bg-white text-xs font-bold text-center text-wukirsari-primary focus:outline-none focus:border-wukirsari-primary">
                                </div>
                            </div>

                            <!-- Alert Dinamis Kapasitas -->
                            <div id="capacityWarning" class="hidden p-2.5 rounded-xl bg-amber-50 border border-amber-200 text-[11px] text-amber-800 font-medium">
                                <span id="capacityWarningText"></span>
                            </div>
                        </div>

                        <!-- F. PILIH PAKET WISATA (NAMA SAMA PERSIS DENGAN BERANDA) -->
                        <div>
                            <label for="package_id" class="block text-xs font-bold text-merapiText-primary mb-1.5">
                                Paket Wisata <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-merapiText-muted">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m8 3 4 8 5-5 5 15H2L8 3z"/>
                                    </svg>
                                </div>
                                <select name="package_id" id="package_id" required class="appearance-none w-full pl-10 pr-10 py-2.5 sm:py-3 rounded-xl border border-wukirsari-border bg-white text-xs sm:text-sm text-merapiText-primary font-bold focus:outline-none focus:border-wukirsari-primary focus:ring-1 focus:ring-wukirsari-primary transition-colors cursor-pointer">
                                    @foreach($packages as $pkg)
                                        <option value="{{ $pkg->id }}" 
                                            data-name="{{ $pkg->name }}" 
                                            data-price="{{ $pkg->price_per_jeep }}" 
                                            data-duration="{{ $pkg->duration_minutes }}"
                                            data-image="{{ $pkg->cover_image }}"
                                            {{ (old('package_id', $selectedPackage->id ?? null) == $pkg->id) ? 'selected' : '' }}>
                                            {{ $pkg->name }} — Rp {{ number_format($pkg->price_per_jeep, 0, ',', '.') }} / Jeep ({{ $pkg->duration_minutes }} menit)
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-merapiText-muted">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- BARIS 4: G. TIPE KENDARAAN & I. DURASI WISATA (OTOMATIS SESUAI PAKET) -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            
                            <!-- G. Tipe Kendaraan -->
                            <div>
                                <label for="vehicle_type" class="block text-xs font-bold text-merapiText-primary mb-1.5">
                                    Tipe Kendaraan
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-merapiText-muted">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H6.108c-.62 0-1.196.326-1.52.863A17.907 17.907 0 0 0 1.5 13.5v4.125c0 .621.504 1.125 1.125 1.125H3m18-4.5H3"/>
                                        </svg>
                                    </div>
                                    <select name="vehicle_type" id="vehicle_type" class="appearance-none w-full pl-10 pr-10 py-2.5 sm:py-3 rounded-xl border border-wukirsari-border bg-white text-xs sm:text-sm text-merapiText-primary focus:outline-none focus:border-wukirsari-primary focus:ring-1 focus:ring-wukirsari-primary transition-colors cursor-pointer">
                                        <option value="Jeep 4x4 (Armada Standar)" selected>Jeep 4x4 (Armada Standar)</option>
                                        <option value="Toyota Hardtop FJ40">Toyota Hardtop FJ40</option>
                                        <option value="Suzuki Jimny Katana">Suzuki Jimny Katana</option>
                                        <option value="Mitsubishi Willys 4x4">Mitsubishi Willys 4x4</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-merapiText-muted">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- I. Durasi Wisata (Otomatis Mengikuti Paket) -->
                            <div>
                                <label class="block text-xs font-bold text-merapiText-primary mb-1.5">
                                    Durasi Wisata <span class="text-xs font-normal text-merapiText-muted">(Otomatis Sesuai Paket)</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-wukirsari-primary">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/>
                                        </svg>
                                    </div>
                                    <input type="text" id="display_duration" readonly value="{{ $selectedPackage->duration_minutes ?? 105 }} menit" class="w-full pl-10 pr-4 py-2.5 sm:py-3 rounded-xl border border-wukirsari-border bg-slate-50 text-xs sm:text-sm font-bold text-wukirsari-primary cursor-not-allowed select-none">
                                    <input type="hidden" name="duration" id="input_duration" value="{{ $selectedPackage->duration_minutes ?? 105 }} menit">
                                </div>
                            </div>

                        </div>

                        <!-- H. LOKASI PENJEMPUTAN (INPUT ALAMAT MANUAL + INFORMASI RADIUS 5 KM) -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="pickup_address" class="block text-xs font-bold text-merapiText-primary">
                                    Lokasi / Alamat Penjemputan <span class="text-red-500">*</span>
                                </label>
                                <button type="button" id="btnUseBasecamp" class="text-[11px] font-bold text-wukirsari-primary hover:underline">
                                    Pilih Basecamp Wukirsari
                                </button>
                            </div>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-merapiText-muted">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0z"/>
                                    </svg>
                                </div>
                                <input type="text" name="pickup_address" id="pickup_address" required value="{{ old('pickup_address', 'Basecamp Jeep Wisata Wukirsari (Titik Kumpul)') }}" placeholder="Ketik nama hotel, stasiun, villa, atau alamat penjemputan Anda..." class="w-full pl-10 pr-4 py-2.5 sm:py-3 rounded-xl border border-wukirsari-border bg-white text-xs sm:text-sm text-merapiText-primary focus:outline-none focus:border-wukirsari-primary focus:ring-1 focus:ring-wukirsari-primary transition-colors">
                            </div>
                            <!-- PEMBERITAHUAN RADIUS 5 KM (TANPA MENAMPILKAN NOMINAL BIAYA) -->
                            <div class="mt-2 p-3 rounded-xl bg-slate-50 border border-wukirsari-border text-[11px] text-merapiText-secondary flex items-start gap-2">
                                <svg class="w-4 h-4 text-wukirsari-primary shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <div>
                                    <p class="font-semibold text-merapiText-primary">Area Penjemputan Resmi:</p>
                                    <p class="mt-0.5 leading-relaxed">
                                        Titik acuan utama adalah <strong>Basecamp Jeep Wisata Wukirsari</strong>. Area penjemputan normal mencakup sekitar <strong>radius 5 km dari basecamp</strong>. Apabila lokasi Anda berada di luar radius tersebut, admin loket akan menghubungi untuk konfirmasi ketersediaan armada dan penyesuaian operasional.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- BARIS 5: J. JUMLAH PENDAMPING (TETAP 1 ORANG) & K. METODE PEMBAYARAN -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            
                            <!-- J. Jumlah Pendamping (Ditetapkan 1 Orang, Tanpa Dropdown) -->
                            <div>
                                <label class="block text-xs font-bold text-merapiText-primary mb-1.5">
                                    Jumlah Pendamping <span class="text-xs font-normal text-merapiText-muted">(Standar Paguyuban)</span>
                                </label>
                                <div class="px-3.5 py-2.5 sm:py-3 rounded-xl border border-wukirsari-border bg-slate-50 flex items-center justify-between">
                                    <div class="flex items-center gap-2 text-xs sm:text-sm font-bold text-merapiText-primary">
                                        <svg class="w-4 h-4 text-wukirsari-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0zM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                                        </svg>
                                        <span>1 Orang Pendamping</span>
                                    </div>
                                    <span class="text-[11px] font-bold text-wukirsari-primary bg-wukirsari-sage px-2 py-0.5 rounded-full">
                                        Driver & Guide
                                    </span>
                                </div>
                            </div>

                            <!-- K. Metode Pembayaran -->
                            <div>
                                <label for="payment_type" class="block text-xs font-bold text-merapiText-primary mb-1.5">
                                    Metode Pembayaran
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-merapiText-muted">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 2.25 19.5z"/>
                                        </svg>
                                    </div>
                                    <select name="payment_type" id="payment_type" class="appearance-none w-full pl-10 pr-10 py-2.5 sm:py-3 rounded-xl border border-wukirsari-border bg-white text-xs sm:text-sm text-merapiText-primary focus:outline-none focus:border-wukirsari-primary focus:ring-1 focus:ring-wukirsari-primary transition-colors cursor-pointer">
                                        <option value="Transfer Bank / Virtual Account" selected>Transfer Bank / Virtual Account</option>
                                        <option value="Uang Muka (DP 30%)">Uang Muka (DP 30% Sisa di Loket)</option>
                                        <option value="QRIS / E-Wallet (Midtrans)">QRIS / E-Wallet (Midtrans)</option>
                                        <option value="Bayar di Loket Basecamp">Bayar di Loket Basecamp</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-merapiText-muted">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- L. CATATAN / PERMINTAAN KHUSUS (OPSIONAL) -->
                        <div>
                            <label for="notes" class="block text-xs font-bold text-merapiText-primary mb-1.5">
                                Catatan / Permintaan Khusus (Opsional)
                            </label>
                            <div class="relative">
                                <div class="absolute top-3 left-3.5 pointer-events-none text-merapiText-muted">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v5.993z"/>
                                    </svg>
                                </div>
                                <textarea name="notes" id="notes" rows="3" placeholder="Contoh: request driver tertentu, membawa perlengkapan khusus, dll" class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-wukirsari-border bg-white text-xs sm:text-sm text-merapiText-primary placeholder-slate-400 focus:outline-none focus:border-wukirsari-primary focus:ring-1 focus:ring-wukirsari-primary transition-colors resize-none h-[75px]">{{ old('notes') }}</textarea>
                            </div>
                        </div>

                    </form>

                </div>
            </div>

            <!-- KOLOM KANAN: RINGKASAN PESANAN (SEKITAR 390px PADA DESKTOP) -->
            <div class="w-full lg:w-[390px] shrink-0 mt-2 lg:mt-6">
                <div class="bg-white rounded-3xl p-6 sm:p-7 border border-wukirsari-border shadow-xs sticky top-28">
                    
                    <!-- Judul Ringkasan Pesanan -->
                    <div class="flex items-center gap-2.5 mb-5 pb-3 border-b border-wukirsari-border">
                        <div class="w-7 h-7 rounded-lg bg-wukirsari-sage text-wukirsari-primary flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9z"/>
                            </svg>
                        </div>
                        <h2 class="text-base font-bold text-merapiText-primary font-display">
                            Ringkasan Pesanan
                        </h2>
                    </div>

                    <!-- Cuplikan Foto Paket & Informasi Paket -->
                    <div class="p-3 rounded-2xl bg-wukirsari-sage/30 border border-wukirsari-border flex items-center gap-3.5 mb-5">
                        <div class="w-16 h-14 rounded-xl overflow-hidden shrink-0 bg-slate-200 border border-wukirsari-border">
                            <img src="{{ $selectedPackage->cover_image ?? asset('images/jeep-thumb.jpg') }}" alt="Paket Wisata" id="summary_pkg_img" class="w-full h-full object-cover">
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="block font-bold text-sm text-merapiText-primary font-display truncate" id="summary_pkg_title">
                                {{ $selectedPackage->name ?? 'Adventure' }}
                            </span>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-wukirsari-primary">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span id="summary_pkg_duration">{{ $selectedPackage->duration_minutes ?? 105 }} menit</span>
                                </span>
                                <span class="text-[11px] text-merapiText-muted">•</span>
                                <span class="text-[11px] text-merapiText-secondary font-medium" id="summary_jeep_count">1 Armada Jeep</span>
                            </div>
                        </div>
                    </div>

                    <!-- Rincian Informasi Pesanan -->
                    <div class="space-y-3 text-xs">
                        
                        <div class="flex justify-between items-center">
                            <span class="text-merapiText-secondary">Tanggal</span>
                            <span class="font-bold text-merapiText-primary" id="summary_date">12 Maret 2025</span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-merapiText-secondary">Waktu Keberangkatan</span>
                            <span class="font-bold text-merapiText-primary" id="summary_time">08:00 - 10:30 WIB</span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-merapiText-secondary">Jumlah Orang</span>
                            <span class="font-bold text-merapiText-primary" id="summary_pax">4 Orang</span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-merapiText-secondary">Durasi Paket</span>
                            <span class="font-bold text-wukirsari-primary" id="summary_duration_val">105 Menit</span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-merapiText-secondary">Jumlah Pendamping</span>
                            <span class="font-bold text-merapiText-primary">1 Orang (Termasuk)</span>
                        </div>

                        <div class="pt-1 flex flex-col gap-1">
                            <div class="flex justify-between items-start">
                                <span class="text-merapiText-secondary">Lokasi Penjemputan</span>
                                <span class="font-bold text-merapiText-primary text-right max-w-[200px] truncate" id="summary_pickup">
                                    Basecamp Wukirsari
                                </span>
                            </div>
                            <span class="text-[10px] text-merapiText-muted italic">
                                *Radius ~5 km dari Basecamp Wukirsari
                            </span>
                        </div>

                    </div>

                    <!-- Garis Pemisah Tipis -->
                    <div class="h-[1px] bg-wukirsari-border my-5"></div>

                    <!-- Bagian Total Harga Resmi Sistem -->
                    <div class="mb-5">
                        <div class="flex justify-between items-baseline">
                            <div>
                                <span class="text-sm font-bold text-merapiText-primary font-display block">
                                    Total Harga
                                </span>
                                <span class="text-[11px] text-merapiText-muted font-normal" id="summary_price_calc_detail">
                                    1 Jeep × Rp 450.000
                                </span>
                            </div>
                            <span class="text-xl font-extrabold text-wukirsari-primary font-display" id="summary_total_price">
                                Rp 450.000
                            </span>
                        </div>
                    </div>

                    <!-- Tombol Utama Lanjut ke Jadwal -->
                    <button type="submit" form="bookingForm" class="w-full py-3.5 px-6 rounded-full bg-wukirsari-primary hover:bg-wukirsari-hover text-white text-xs sm:text-sm font-bold flex items-center justify-center gap-2 shadow-md shadow-wukirsari-primary/20 hover:scale-[1.01] active:scale-95 transition-all">
                        <span>Lanjut ke Jadwal</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                        </svg>
                    </button>

                    <!-- Jaminan Keamanan Resmi -->
                    <div class="mt-4 text-center">
                        <p class="text-[11px] text-merapiText-muted">
                            Layanan Resmi Paguyuban Jeep Wisata Wukirsari Merapi
                        </p>
                    </div>

                </div>
            </div>

        </div>

    </div>
</div>

<!-- JAVASCRIPT LOGIKA BISNIS & REAKTIVITAS FORM SECARA REALTIME -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('bookingForm');
    const inputTripDate = document.getElementById('trip_date');
    const selectSlot = document.getElementById('slot_id');
    const inputPax = document.getElementById('passenger_count');
    const inputChild = document.getElementById('child_count');
    const selectPackage = document.getElementById('package_id');
    const inputPickup = document.getElementById('pickup_address');
    const btnUseBasecamp = document.getElementById('btnUseBasecamp');
    const selectPayment = document.getElementById('payment_type');
    
    const displayDuration = document.getElementById('display_duration');
    const inputDuration = document.getElementById('input_duration');
    const inputJeepQuantity = document.getElementById('input_jeep_quantity');
    const inputPaymentMode = document.getElementById('input_payment_mode');

    // Element Ringkasan
    const summaryPkgTitle = document.getElementById('summary_pkg_title');
    const summaryPkgImg = document.getElementById('summary_pkg_img');
    const summaryPkgDuration = document.getElementById('summary_pkg_duration');
    const summaryJeepCount = document.getElementById('summary_jeep_count');
    const summaryDate = document.getElementById('summary_date');
    const summaryTime = document.getElementById('summary_time');
    const summaryPax = document.getElementById('summary_pax');
    const summaryDurationVal = document.getElementById('summary_duration_val');
    const summaryPickup = document.getElementById('summary_pickup');
    const summaryTotalPrice = document.getElementById('summary_total_price');
    const summaryPriceCalcDetail = document.getElementById('summary_price_calc_detail');

    // Alert Kapasitas
    const capacityWarning = document.getElementById('capacityWarning');
    const capacityWarningText = document.getElementById('capacityWarningText');

    function formatRupiah(amount) {
        return 'Rp ' + Number(amount).toLocaleString('id-ID');
    }

    function formatIndonesianDate(dateString) {
        if (!dateString) return '-';
        try {
            const parts = dateString.split('-');
            if (parts.length === 3) {
                const year = parseInt(parts[0], 10);
                const monthIndex = parseInt(parts[1], 10) - 1;
                const day = parseInt(parts[2], 10);

                const months = [
                    'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                ];

                if (monthIndex >= 0 && monthIndex < 12) {
                    return `${day} ${months[monthIndex]} ${year}`;
                }
            }
        } catch (e) {
            console.error('Date error', e);
        }
        return dateString;
    }

    function updateCalculations() {
        // 1. Tanggal
        if (inputTripDate && inputTripDate.value) {
            summaryDate.textContent = formatIndonesianDate(inputTripDate.value);
        }

        // 2. Waktu
        if (selectSlot) {
            const opt = selectSlot.options[selectSlot.selectedIndex];
            if (opt) {
                summaryTime.textContent = opt.getAttribute('data-time') || opt.textContent.trim();
            }
        }

        // 3. Paket & Durasi (Otomatis)
        let pricePerJeep = 450000;
        let durationMinutes = 105;
        let pkgName = 'Adventure';
        let pkgImage = '';

        if (selectPackage) {
            const opt = selectPackage.options[selectPackage.selectedIndex];
            if (opt) {
                pkgName = opt.getAttribute('data-name') || opt.textContent.trim();
                pricePerJeep = parseFloat(opt.getAttribute('data-price')) || 450000;
                durationMinutes = opt.getAttribute('data-duration') || 105;
                pkgImage = opt.getAttribute('data-image') || '';

                summaryPkgTitle.textContent = pkgName;
                if (pkgImage && summaryPkgImg) {
                    summaryPkgImg.src = pkgImage;
                }
                summaryPkgDuration.textContent = durationMinutes + ' menit';
                summaryDurationVal.textContent = durationMinutes + ' Menit';

                if (displayDuration) displayDuration.value = durationMinutes + ' menit';
                if (inputDuration) inputDuration.value = durationMinutes + ' menit';
            }
        }

        // 4. Jumlah Orang (Manual Input) & Aturan Kapasitas Jeep Lereng Merapi
        let pax = parseInt(inputPax.value, 10);
        if (isNaN(pax) || pax < 1) {
            pax = 1;
        }

        let childCount = parseInt(inputChild.value, 10);
        if (isNaN(childCount) || childCount < 0) {
            childCount = 0;
        }

        let jeepsNeeded = 1;
        let warningMsg = '';

        if (pax <= 4) {
            jeepsNeeded = 1;
            warningMsg = '';
        } else if (pax === 5) {
            if (childCount >= 1) {
                jeepsNeeded = 1;
                warningMsg = 'Komposisi 5 orang diterima dalam 1 Jeep karena menyertakan peserta anak-anak sesuai aturan paguyuban.';
            } else {
                jeepsNeeded = 2;
                warningMsg = 'Maaf, kapasitas normal Jeep maksimal 4 orang dewasa. Rombongan 5 orang dewasa membutuhkan 2 armada Jeep.';
            }
        } else {
            jeepsNeeded = Math.ceil(pax / 4);
            warningMsg = `Rombongan ${pax} orang membutuhkan ${jeepsNeeded} armada Jeep (maksimal 4 penumpang per jeep).`;
        }

        if (inputJeepQuantity) {
            inputJeepQuantity.value = jeepsNeeded;
        }

        summaryPax.textContent = `${pax} Orang (${jeepsNeeded} Jeep)`;
        summaryJeepCount.textContent = `${jeepsNeeded} Armada Jeep`;

        if (warningMsg) {
            capacityWarning.classList.remove('hidden');
            capacityWarningText.textContent = warningMsg;
        } else {
            capacityWarning.classList.add('hidden');
        }

        // 5. Lokasi Penjemputan
        if (inputPickup) {
            const loc = inputPickup.value.trim();
            summaryPickup.textContent = loc || 'Basecamp Wukirsari';
        }

        // 6. Skema Pembayaran
        if (selectPayment) {
            const val = selectPayment.value;
            if (val.includes('DP')) {
                inputPaymentMode.value = 'DP';
            } else {
                inputPaymentMode.value = 'FULL';
            }
        }

        // 7. Total Harga Resmi (Tanpa Tambahan Fiktif)
        const subtotal = pricePerJeep * jeepsNeeded;
        summaryTotalPrice.textContent = formatRupiah(subtotal);
        summaryPriceCalcDetail.textContent = `${jeepsNeeded} Jeep × ${formatRupiah(pricePerJeep)}`;
    }

    // Tombol Cepat Basecamp
    if (btnUseBasecamp) {
        btnUseBasecamp.addEventListener('click', function () {
            inputPickup.value = 'Basecamp Jeep Wisata Wukirsari (Titik Kumpul)';
            updateCalculations();
        });
    }

    // Event listeners
    if (inputTripDate) inputTripDate.addEventListener('change', updateCalculations);
    if (selectSlot) selectSlot.addEventListener('change', updateCalculations);
    if (inputPax) {
        inputPax.addEventListener('input', updateCalculations);
        inputPax.addEventListener('change', updateCalculations);
    }
    if (inputChild) {
        inputChild.addEventListener('input', updateCalculations);
        inputChild.addEventListener('change', updateCalculations);
    }
    if (selectPackage) selectPackage.addEventListener('change', updateCalculations);
    if (inputPickup) inputPickup.addEventListener('input', updateCalculations);
    if (selectPayment) selectPayment.addEventListener('change', updateCalculations);

    // Jalankan kalkulasi saat halaman dibuka
    updateCalculations();

    // Validasi saat form disubmit
    form.addEventListener('submit', function (e) {
        const name = document.getElementById('customer_name').value.trim();
        const phone = document.getElementById('customer_phone').value.trim();
        const paxVal = parseInt(inputPax.value, 10);

        if (!name) {
            alert('Mohon masukkan nama lengkap pemesan.');
            e.preventDefault();
            return;
        }

        if (!phone || phone.length < 8) {
            alert('Mohon masukkan nomor WhatsApp aktif yang valid.');
            e.preventDefault();
            return;
        }

        if (isNaN(paxVal) || paxVal < 1) {
            alert('Jumlah orang harus berupa angka positif minimal 1.');
            e.preventDefault();
            return;
        }
    });
});
</script>
@endsection