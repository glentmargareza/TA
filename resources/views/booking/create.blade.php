@extends('layouts.app')
 
@section('title', 'Formulir Pemesanan Jeep | Jeep Wukirsari Adventure')
 
@section('content')
<!-- Leaflet Map CSS & JS (Peta Gratis Tanpa Kunci API) -->
<link rel="stylesheet" href="{{ asset('vendor/leaflet/leaflet.css') }}">
<script src="{{ asset('vendor/leaflet/leaflet.js') }}"></script>

<div class="bg-wukirsari-cream min-h-screen py-8 sm:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 sm:space-y-8">
        
        <!-- Breadcrumb / Tombol Kembali -->
        <div class="flex items-center justify-between flex-wrap gap-4">
            <a href="{{ route('home') }}#paket" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white border border-wukirsari-border text-merapiText-secondary hover:text-wukirsari-primary hover:border-wukirsari-primary text-xs sm:text-sm font-semibold shadow-xs transition-all group">
                <svg class="w-4 h-4 text-wukirsari-primary group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Kembali ke Pilihan Paket</span>
            </a>
        </div>
 
        <!-- Judul Halaman -->
        <div class="space-y-1.5">
            <span class="text-xs font-bold uppercase tracking-wider text-wukirsari-primary font-display block">
                FORMULIR RESERVASI ONLINE
            </span>
            <h1 class="text-2xl sm:text-4xl font-extrabold text-merapiText-primary font-display tracking-tight">
                Pemesanan Jeep Wukirsari
            </h1>
            <p class="text-merapiText-secondary text-xs sm:text-sm max-w-2xl">
                Pilih paket wisata lereng Merapi, tentukan jadwal keberangkatan, dan nikmati opsi pembayaran instan (Lunas 100% atau Uang Muka DP 30%).
            </p>
        </div>
 
        <!-- Form Pengiriman Data ke BookingController@store -->
        <form id="bookForm" action="{{ route('booking.store') }}" method="POST" class="hidden">
            @csrf
            <input type="hidden" name="package_id">
            <input type="hidden" name="slot_id">
            <input type="hidden" name="trip_date">
            <input type="hidden" name="passenger_count">
            <input type="hidden" name="jeep_quantity">
            <input type="hidden" name="arrival_type" value="self_drive">
            <input type="hidden" name="pickup_address" value="">
            <input type="hidden" name="pickup_lat" value="">
            <input type="hidden" name="pickup_lng" value="">
            <input type="hidden" name="pickup_manual" value="0">
            <input type="hidden" name="customer_name">
            <input type="hidden" name="customer_phone">
            <input type="hidden" name="payment_mode">
            <input type="hidden" name="notes">
        </form>
 
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Kolom Kiri: Stepper & 4 Kartu Formulir (lg:col-span-8) -->
            <div class="lg:col-span-8 space-y-6">
                
                <!-- Stepper Progress Bar -->
                <div class="bg-white border border-wukirsari-border rounded-2xl p-2 shadow-xs flex items-center justify-between gap-1 sm:gap-2 overflow-x-auto scrollbar-none sticky top-20 z-30 backdrop-blur-md bg-white/95" id="stepper">
                    <button type="button" class="st on flex items-center gap-2 px-3 sm:px-4 py-2 rounded-xl text-xs sm:text-sm font-bold text-merapiText-secondary hover:text-merapiText-primary transition-all shrink-0 cursor-pointer" data-s="1">
                        <i class="w-6 h-6 rounded-lg bg-wukirsari-sage text-wukirsari-primary flex items-center justify-center text-xs font-bold not-italic">1</i>
                        <span>1. Paket</span>
                    </button>
                    <div class="w-4 h-0.5 bg-wukirsari-border shrink-0"></div>
                    <button type="button" class="st flex items-center gap-2 px-3 sm:px-4 py-2 rounded-xl text-xs sm:text-sm font-bold text-merapiText-secondary hover:text-merapiText-primary transition-all shrink-0 cursor-pointer" data-s="2">
                        <i class="w-6 h-6 rounded-lg bg-wukirsari-sage text-wukirsari-primary flex items-center justify-center text-xs font-bold not-italic">2</i>
                        <span>2. Jadwal</span>
                    </button>
                    <div class="w-4 h-0.5 bg-wukirsari-border shrink-0"></div>
                    <button type="button" class="st flex items-center gap-2 px-3 sm:px-4 py-2 rounded-xl text-xs sm:text-sm font-bold text-merapiText-secondary hover:text-merapiText-primary transition-all shrink-0 cursor-pointer" data-s="3">
                        <i class="w-6 h-6 rounded-lg bg-wukirsari-sage text-wukirsari-primary flex items-center justify-center text-xs font-bold not-italic">3</i>
                        <span>3. Kapasitas</span>
                    </button>
                    <div class="w-4 h-0.5 bg-wukirsari-border shrink-0"></div>
                    <button type="button" class="st flex items-center gap-2 px-3 sm:px-4 py-2 rounded-xl text-xs sm:text-sm font-bold text-merapiText-secondary hover:text-merapiText-primary transition-all shrink-0 cursor-pointer" data-s="4">
                        <i class="w-6 h-6 rounded-lg bg-wukirsari-sage text-wukirsari-primary flex items-center justify-center text-xs font-bold not-italic">4</i>
                        <span>4. Kontak</span>
                    </button>
                </div>
 
                <!-- 1. PILIH PAKET -->
                <section class="bg-white rounded-3xl border border-wukirsari-border shadow-xs p-6 sm:p-7 space-y-5" data-sec="1">
                    <div class="flex items-center gap-3 pb-3 border-b border-wukirsari-border/70">
                        <span class="w-8 h-8 rounded-xl bg-wukirsari-primary text-white text-sm font-bold flex items-center justify-center font-display shadow-xs shrink-0">1</span>
                        <div>
                            <h2 class="text-base sm:text-lg font-bold text-merapiText-primary font-display">Pilih Paket Wisata</h2>
                            <p class="text-xs text-merapiText-secondary">Tarif dihitung per unit armada jeep (kapasitas 1–4 orang/jeep).</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3" id="pkgs">
                        <!-- Dimuat otomatis via JavaScript dari database -->
                    </div>
                </section>
 
                <!-- 2. JADWAL & SESI TRIP -->
                <section class="bg-white rounded-3xl border border-wukirsari-border shadow-xs p-6 sm:p-7 space-y-5" data-sec="2">
                    <div class="flex items-center gap-3 pb-3 border-b border-wukirsari-border/70">
                        <span class="w-8 h-8 rounded-xl bg-wukirsari-primary text-white text-sm font-bold flex items-center justify-center font-display shadow-xs shrink-0">2</span>
                        <div>
                            <h2 class="text-base sm:text-lg font-bold text-merapiText-primary font-display">Pilih Tanggal & Jam Trip</h2>
                            <p class="text-xs text-merapiText-secondary">Tentukan tanggal wisata dan pilih sesi keberangkatan yang tersedia.</p>
                        </div>
                    </div>
 
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label for="date" class="block text-xs font-bold text-merapiText-primary">Tanggal Kunjungan *</label>
                            <input id="date" type="date" class="w-full px-4 py-3 rounded-2xl border border-wukirsari-border bg-white text-merapiText-primary text-sm font-semibold focus:outline-none focus:border-wukirsari-primary focus:ring-2 focus:ring-wukirsari-primary/15 transition-all">
                        </div>
                        <div class="space-y-1.5">
                            <label for="session" class="block text-xs font-bold text-merapiText-primary">Sesi Waktu Keberangkatan *</label>
                            <select id="session" class="w-full px-4 py-3 rounded-2xl border border-wukirsari-border bg-white text-merapiText-primary text-sm font-semibold focus:outline-none focus:border-wukirsari-primary focus:ring-2 focus:ring-wukirsari-primary/15 transition-all"></select>
                            <span class="block text-[11px] font-medium text-amber-600" id="sessHint"></span>
                        </div>
                    </div>
                </section>
 
                <!-- 3. KAPASITAS & JUMLAH JEEP -->
                <section class="bg-white rounded-3xl border border-wukirsari-border shadow-xs p-6 sm:p-7 space-y-5" data-sec="3">
                    <div class="flex items-center gap-3 pb-3 border-b border-wukirsari-border/70">
                        <span class="w-8 h-8 rounded-xl bg-wukirsari-primary text-white text-sm font-bold flex items-center justify-center font-display shadow-xs shrink-0">3</span>
                        <div>
                            <h2 class="text-base sm:text-lg font-bold text-merapiText-primary font-display">Kapasitas & Jumlah Jeep</h2>
                            <p class="text-xs text-merapiText-secondary">Standar operasional keselamatan maksimal 4 orang penumpang per unit jeep.</p>
                        </div>
                    </div>
 
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Penumpang Counter -->
                        <div class="p-4 rounded-2xl bg-wukirsari-cream/60 border border-wukirsari-border space-y-3">
                            <label class="block text-xs font-bold text-merapiText-primary">Jumlah Penumpang (Orang)</label>
                            <div class="flex items-center justify-between bg-white px-3 py-2 rounded-xl border border-wukirsari-border shadow-2xs">
                                <button type="button" id="paxMinus" class="w-9 h-9 rounded-lg bg-wukirsari-cream hover:bg-wukirsari-sage text-merapiText-primary font-bold flex items-center justify-center text-base transition-colors cursor-pointer active:scale-95">−</button>
                                <output id="pax" class="font-extrabold text-lg text-merapiText-primary font-display">4</output>
                                <button type="button" id="paxPlus" class="w-9 h-9 rounded-lg bg-wukirsari-cream hover:bg-wukirsari-sage text-merapiText-primary font-bold flex items-center justify-center text-base transition-colors cursor-pointer active:scale-95">+</button>
                            </div>
                            <span class="block text-[11px] text-merapiText-secondary">*Maksimal 4 penumpang per armada jeep.</span>
                        </div>
 
                        <!-- Jeep Counter -->
                        <div class="p-4 rounded-2xl bg-wukirsari-cream/60 border border-wukirsari-border space-y-3">
                            <label class="block text-xs font-bold text-merapiText-primary">Jumlah Armada Jeep</label>
                            <div class="flex items-center justify-between bg-white px-3 py-2 rounded-xl border border-wukirsari-border shadow-2xs">
                                <button type="button" id="minus" class="w-9 h-9 rounded-lg bg-wukirsari-cream hover:bg-wukirsari-sage text-merapiText-primary font-bold flex items-center justify-center text-base transition-colors cursor-pointer active:scale-95">−</button>
                                <output id="qty" class="font-extrabold text-lg text-wukirsari-primary font-display">1</output>
                                <button type="button" id="plus" class="w-9 h-9 rounded-lg bg-wukirsari-cream hover:bg-wukirsari-sage text-merapiText-primary font-bold flex items-center justify-center text-base transition-colors cursor-pointer active:scale-95">+</button>
                            </div>
                            <span class="block text-[11px] font-bold text-wukirsari-primary" id="jeepHint"></span>
                        </div>
                    </div>
                </section>
 
                <!-- 4. OPSI KEDATANGAN & KONTAK -->
                <section class="bg-white rounded-3xl border border-wukirsari-border shadow-xs p-6 sm:p-7 space-y-6" data-sec="4">
                    <div class="flex items-center gap-3 pb-3 border-b border-wukirsari-border/70">
                        <span class="w-8 h-8 rounded-xl bg-wukirsari-primary text-white text-sm font-bold flex items-center justify-center font-display shadow-xs shrink-0">4</span>
                        <div>
                            <h2 class="text-base sm:text-lg font-bold text-merapiText-primary font-display">Opsi Kedatangan & Kontak</h2>
                            <p class="text-xs text-merapiText-secondary">Pilih cara Anda tiba, lalu isi kontak untuk pengiriman tiket.</p>
                        </div>
                    </div>
 
                    <!-- OPSI KEDATANGAN -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <button type="button" class="ar sel py-4 px-4 rounded-2xl border border-wukirsari-border bg-white text-xs sm:text-sm font-bold text-merapiText-primary transition-all cursor-pointer" data-m="self">Datang Sendiri ke Basecamp</button>
                        <button type="button" class="ar py-4 px-4 rounded-2xl border border-wukirsari-border bg-white text-xs sm:text-sm font-bold text-merapiText-primary transition-all cursor-pointer" data-m="shuttle">Layanan Antar-Jemput (Shuttle)</button>
                    </div>
 
                    <div id="pickupBox" class="hidden space-y-5">
                        <!-- Nama hotel -->
                        <div class="space-y-2 relative">
                            <label for="addr" class="block text-xs font-bold text-merapiText-primary">Nama Hotel / Alamat Penjemputan *</label>
                            <div class="relative">
                                <input id="addr" autocomplete="off" placeholder="Ketik nama hotel, mis. Villa Padi, Marriott, Hyatt..."
                                       class="w-full px-4 py-3.5 pr-10 rounded-2xl border border-wukirsari-border bg-white text-merapiText-primary text-sm focus:outline-none focus:border-wukirsari-primary focus:ring-2 focus:ring-wukirsari-primary/15 transition-all">
                                <div id="addrSpin" class="hidden absolute right-3.5 top-3.5 text-wukirsari-primary pointer-events-none">
                                    <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                    </svg>
                                </div>
                                <div id="addrRes" class="hidden absolute left-0 right-0 top-full mt-1.5 z-30 rounded-2xl border border-wukirsari-border bg-white shadow-xl overflow-hidden"></div>
                            </div>
                            <span class="block text-[11px] text-merapiText-muted">Ketik nama hotel, lalu pilih lokasi yang sesuai dari daftar. Pastikan pin di peta sudah tepat.</span>
                        </div>
 
                        <!-- Titik lokasi (GPS) -->
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-merapiText-primary">Titik Lokasi Penjemputan *</label>
                            <button type="button" id="btnGps" class="w-full py-4 rounded-2xl bg-[#1E3F20] hover:bg-[#255231] text-white text-sm font-bold transition-all cursor-pointer active:scale-[0.99]">Bagikan Lokasi Saya Sekarang</button>
                            <span class="block text-[11px] text-merapiText-muted">Browser akan meminta izin akses lokasi, pilih "Izinkan". Tekan saat Anda berada di lokasi jemput.</span>
                        </div>
 
                        <!-- Link / koordinat -->
                        <div class="space-y-2">
                            <label for="link" class="block text-xs font-bold text-merapiText-primary">Atau tempel link Google Maps / koordinat</label>
                            <div class="flex gap-3">
                                <input id="link" placeholder="https://www.google.com/maps/... atau -7.7956, 110.3695"
                                       class="flex-1 min-w-0 px-4 py-3.5 rounded-2xl border border-wukirsari-border bg-white text-merapiText-primary text-sm focus:outline-none focus:border-wukirsari-primary focus:ring-2 focus:ring-wukirsari-primary/15 transition-all">
                                <button type="button" id="btnLink" class="px-6 rounded-2xl bg-[#1E3F20] hover:bg-[#255231] text-white text-sm font-bold cursor-pointer transition-all">Hitung</button>
                            </div>
                            <span class="block text-[11px] text-merapiText-muted">Di Google Maps: tahan lama pada lokasi hotel, lalu salin angka koordinat yang muncul di bagian atas.</span>
                        </div>
 
                        <!-- Peta: pastikan titik jemput benar -->
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-merapiText-primary">Pastikan titik jemput sudah benar</label>
                            <div id="map" class="w-full rounded-2xl border border-wukirsari-border overflow-hidden" style="height:290px;z-index:10"></div>
                            <span class="block text-[11px] text-merapiText-muted">Geser pin atau klik peta untuk memperbaiki titik jemput, jarak dihitung ulang otomatis.</span>
                        </div>
 
                        <!-- Kotak jarak -->
                        <div id="dist" class="dist flex items-center justify-between gap-3 px-5 py-4 rounded-2xl border border-[#cfe0d2] bg-[#f1f6f2]">
                            <div>
                                <div class="k text-2xl font-extrabold text-[#1E3F20] font-display leading-tight" id="distKm">—</div>
                                <span class="block text-[11px] text-merapiText-secondary mt-1" id="distTxt">Ketik nama hotel untuk menghitung jarak otomatis.</span>
                            </div>
                            <span class="tag shrink-0 px-3.5 py-1.5 rounded-full bg-[#1E3F20] text-white text-[11px] font-bold" id="distTag">Belum dihitung</span>
                        </div>
 
                        <!-- Info GPS -->
                        <div id="gpsInfo" class="hidden grid grid-cols-1 sm:grid-cols-2 gap-3 p-4 rounded-2xl border border-wukirsari-border bg-white">
                            <div><span class="block text-[11px] text-merapiText-muted">Lokasi yang dipakai</span><strong class="block text-xs text-merapiText-primary break-words" id="gpsName">-</strong></div>
                            <div><span class="block text-[11px] text-merapiText-muted">Koordinat GPS</span><strong class="block text-xs text-merapiText-primary" id="gpsCoord">-</strong></div>
                            <a id="gpsLink" href="#" target="_blank" rel="noopener" class="sm:col-span-2 text-xs font-bold text-[#1E3F20] underline">Cek di Google Maps ↗</a>
                        </div>
 
                        <!-- Manual via admin -->
                        <button type="button" id="btnManual" class="px-4 py-3 rounded-2xl border border-wukirsari-border bg-white text-xs font-bold text-merapiText-primary hover:bg-wukirsari-cream cursor-pointer transition-all">Lokasi tidak terdeteksi? Biaya dikonfirmasi admin via WhatsApp</button>
                    </div>
 
                    <!-- Customer Contact Info -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label for="name" class="block text-xs font-bold text-merapiText-primary">Nama Lengkap Pemesan *</label>
                            <input id="name" class="w-full px-4 py-3 rounded-2xl border border-wukirsari-border bg-white text-merapiText-primary text-sm focus:outline-none focus:border-wukirsari-primary focus:ring-2 focus:ring-wukirsari-primary/15 transition-all" placeholder="Contoh: Budi Santoso" autocomplete="name">
                        </div>
                        <div class="space-y-1.5">
                            <label for="wa" class="block text-xs font-bold text-merapiText-primary">Nomor WhatsApp Aktif *</label>
                            <input id="wa" class="w-full px-4 py-3 rounded-2xl border border-wukirsari-border bg-white text-merapiText-primary text-sm focus:outline-none focus:border-wukirsari-primary focus:ring-2 focus:ring-wukirsari-primary/15 transition-all" inputmode="tel" placeholder="Contoh: 081234567890" autocomplete="tel">
                            <span class="block text-[11px] text-merapiText-muted">*E-Tiket resmi & rincian dikirim ke nomor ini.</span>
                        </div>
                    </div>
 
                    <!-- Customer Notes -->
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label for="notes" class="block text-xs font-bold text-merapiText-primary">Pesan / Catatan Khusus (Opsional)</label>
                            <span class="text-[11px] text-merapiText-muted"><span id="notesCount">0</span>/500 karakter</span>
                        </div>
                        <textarea id="notes" rows="3" maxlength="500" class="w-full px-4 py-3 rounded-2xl border border-wukirsari-border bg-white text-merapiText-primary text-sm focus:outline-none focus:border-wukirsari-primary focus:ring-2 focus:ring-wukirsari-primary/15 transition-all" placeholder="Contoh: Request driver ramah untuk lansia atau anak-anak, minta helm bersih."></textarea>
                    </div>
                </section>
            </div>
 
            <!-- Kolom Kanan: Sticky Sidebar Tagihan (lg:col-span-4) -->
            <div class="lg:col-span-4 lg:sticky lg:top-24 space-y-6" id="pay">
                <div class="bg-white rounded-3xl border border-wukirsari-border shadow-sm p-6 sm:p-7 space-y-5">
                    <h3 class="text-base sm:text-lg font-bold text-merapiText-primary font-display pb-3 border-b border-wukirsari-border/70">
                        Ringkasan Pemesanan
                    </h3>
 
                    <!-- Ticket Pill Preview -->
                    <div class="bg-wukirsari-cream/80 border border-dashed border-wukirsari-border rounded-2xl p-4 space-y-1">
                        <strong class="block text-sm font-bold text-merapiText-primary font-display" id="tPkg">Paket</strong>
                        <span class="block text-xs text-merapiText-secondary" id="tDate">Belum pilih tanggal</span>
                    </div>
 
                    <!-- Breakdown Rows -->
                    <div class="space-y-2.5 text-xs sm:text-sm">
                        <div class="flex justify-between items-center text-merapiText-secondary">
                            <span id="lJeep">Sewa Jeep</span>
                            <strong class="text-merapiText-primary font-bold" id="vJeep">Rp 0</strong>
                        </div>
                        <div class="flex justify-between items-center text-merapiText-secondary">
                            <span>Layanan Penjemputan</span>
                            <strong class="text-merapiText-primary font-bold" id="vPick">Tidak dipilih</strong>
                        </div>
                        <div id="rowExtra" class="hidden justify-between items-start gap-3 rounded-xl bg-amber-50 border border-amber-200 px-3 py-2 text-amber-800">
                            <span id="lExtra">Biaya Tambahan Jemput</span>
                            <strong class="text-amber-700 font-bold whitespace-nowrap" id="vExtra">Rp 0</strong>
                        </div>
                        <div class="flex justify-between items-center text-merapiText-secondary">
                            <span>BBM, Driver & Asuransi</span>
                            <strong class="text-emerald-700 font-bold">Gratis (Termasuk)</strong>
                        </div>
                    </div>
 
                    <!-- Total Amount -->
                    <div class="pt-4 border-t border-wukirsari-border flex justify-between items-center">
                        <span class="text-xs font-bold text-merapiText-secondary uppercase tracking-wider">Total Biaya</span>
                        <strong class="text-2xl sm:text-3xl font-extrabold text-wukirsari-primary font-display" id="vTotal">Rp 0</strong>
                    </div>
 
                    <!-- Skema Pembayaran -->
                    <div class="space-y-2 pt-2">
                        <label class="block text-xs font-bold text-merapiText-primary">Pilihan Skema Pembayaran</label>
                        <div class="grid grid-cols-2 gap-2.5 pay">
                            <button type="button" class="po sel p-3 rounded-2xl border text-left transition-all cursor-pointer" data-p="full">
                                <strong class="block text-xs font-bold text-merapiText-primary">Lunas (100%)</strong>
                                <span class="block text-[10px] text-merapiText-secondary mt-0.5">Langsung beres</span>
                            </button>
                            <button type="button" class="po p-3 rounded-2xl border text-left transition-all cursor-pointer" data-p="dp">
                                <strong class="block text-xs font-bold text-merapiText-primary">Uang Muka (DP 30%)</strong>
                                <span class="block text-[10px] text-merapiText-secondary mt-0.5">Sisa di basecamp</span>
                            </button>
                        </div>
                    </div>
 
                    <!-- Box Tagihan Sekarang -->
                    <div class="bg-wukirsari-dark text-white rounded-2xl p-4 flex items-center justify-between shadow-xs">
                        <div>
                            <span class="block text-[11px] text-wukirsari-sage font-medium" id="dueLbl">Wajib Dibayar Sekarang</span>
                            <strong class="text-xl sm:text-2xl font-extrabold font-display" id="vDue">Rp 0</strong>
                        </div>
                        <span class="text-xs text-wukirsari-sage font-semibold" id="dueSub">Lunas</span>
                    </div>
 
                    <!-- Tombol Lanjutkan -->
                    <button id="submit" type="button" class="w-full py-4 rounded-full bg-wukirsari-primary hover:bg-wukirsari-hover text-white font-bold text-sm sm:text-base shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2 cursor-pointer active:scale-[0.99]">
                        <span>Konfirmasi & Terbitkan E-Tiket</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
 
                    <!-- Trust Badges -->
                    <div class="flex items-center justify-center gap-3 text-[11px] text-merapiText-muted font-medium pt-1">
                        <span>🛡️ Pembayaran Resmi</span>
                        <span>•</span>
                        <span>🔄 Reschedule H-2</span>
                        <span>•</span>
                        <span>⚡ Konfirmasi Instan</span>
                    </div>
                </div>
            </div>
 
        </div>
    </div>
</div>
 
<!-- Mobile Bottom Floating Bar -->
<div class="lg:hidden fixed bottom-0 left-0 right-0 bg-white/95 backdrop-blur-md border-t border-wukirsari-border px-5 py-3.5 flex items-center justify-between z-40 shadow-2xl">
    <div>
        <span class="block text-[10px] font-bold uppercase tracking-wider text-merapiText-muted">Total Tagihan</span>
        <strong class="text-lg font-extrabold text-wukirsari-primary font-display" id="mTotal">Rp 0</strong>
    </div>
    <a href="#pay" class="px-5 py-2.5 rounded-full bg-wukirsari-primary text-white text-xs font-bold shadow-md">
        Rincian Pembayaran
    </a>
</div>
 
<!-- Toast Popup Notification -->
<div id="toast" class="fixed left-1/2 bottom-20 -translate-x-1/2 bg-merapiText-primary text-white text-xs font-semibold px-5 py-3 rounded-2xl shadow-2xl z-50 transition-all duration-300 opacity-0 pointer-events-none translate-y-6"></div>
 
<style>
/* Active State Helpers for Stepper & Selectors */
.pkg.sel {
    border-color: #1E3F20 !important;
    background-color: #E8EFE9 !important;
    box-shadow: 0 0 0 2px rgba(30, 63, 32, 0.2) !important;
}
.po.sel {
    border-color: #1E3F20 !important;
    background-color: #E8EFE9 !important;
    box-shadow: 0 0 0 2px rgba(30, 63, 32, 0.2) !important;
}
.st.on {
    background-color: #1E3F20 !important;
    color: #ffffff !important;
}
.st.on i {
    background-color: #ffffff !important;
    color: #1E3F20 !important;
}
.st.done {
    color: #1E3F20 !important;
}
.st.done i {
    background-color: #1E3F20 !important;
    color: #ffffff !important;
}
.err {
    border-color: #ef4444 !important;
    box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.2) !important;
}
#toast.show {
    opacity: 1 !important;
    transform: translate(-50%, 0) !important;
}
 
/* ===== PENJEMPUTAN ===== */
.ar.sel{border-color:#1E3F20!important;background-color:#E8EFE9!important;box-shadow:0 0 0 2px rgba(30,63,32,.2)!important}
.dist.over{background-color:#fffbeb!important;border-color:#fcd34d!important}
.dist.over .k{color:#b45309!important}
.dist.over .tag{background-color:#f59e0b!important}
#rowExtra.show{display:flex}
</style>
 
<script type="application/json" id="booking-data">@json($bookingData)</script>
 
<script>
/* ====== PENGATURAN (diambil dari server) ====== */
const bookingDataEl = document.getElementById('booking-data');
const D              = bookingDataEl ? JSON.parse(bookingDataEl.textContent || '{}') : (window.BOOKING_DATA || {});
window.BOOKING_DATA  = D;
const PER_JEEP       = 4;
const PACKAGES       = D.packages || [];
const SESSIONS       = D.slots || [];
 
const $ = s => document.querySelector(s);
const rp = n => 'Rp ' + Number(n).toLocaleString('id-ID');
const esc = s => String(s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
const state = {
  pkg: D.selectedId ?? (PACKAGES[0] ? PACKAGES[0].id : null),
  qty: 1, pax: 4, session: null, pay: 'full'
};
const curPkg = () => PACKAGES.find(x => String(x.id) === String(state.pkg)) || PACKAGES[0];
 
/* ====== PENGATURAN PENJEMPUTAN (dari controller) ====== */
const PK_BASE    = D.basecamp;   // titik basecamp WAJIB dari controller (tidak ada nilai tebakan)
const PK_FREE_KM = (D.freeKm     !== undefined) ? Number(D.freeKm)     : 5;
const PK_FEE     = (D.feePerJeep !== undefined) ? Number(D.feePerJeep) : 50000;
const pk = {on:false, km:null, lat:null, lng:null, label:'', calc:false, manual:false, approx:false, failed:false};
let pkCalcId = 0, pkSearchT, pkSearchId = 0;
 
/* ====== 1. PAKET ====== */
const pkgsEl = $('#pkgs');
if (pkgsEl && PACKAGES.length) {
  pkgsEl.innerHTML = PACKAGES.map(p => `
    <button type="button" class="pkg ${String(p.id)===String(state.pkg)?'sel':''} p-4 rounded-2xl border border-wukirsari-border bg-white text-left transition-all flex flex-col justify-between gap-3 cursor-pointer hover:border-wukirsari-primary/50 relative group" data-id="${p.id}">
      <div>
        <h3 class="font-bold text-sm text-merapiText-primary font-display group-hover:text-wukirsari-primary transition-colors">${esc(p.name)}</h3>
        <div class="text-xs font-extrabold text-wukirsari-primary font-display mt-0.5">${rp(p.price)} <span class="text-[10px] font-normal text-merapiText-muted">/ jeep</span></div>
      </div>
      ${p.dur ? `<div class="text-[11px] text-merapiText-secondary flex items-center gap-1"><svg class="w-3.5 h-3.5 text-merapiText-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><span>${esc(p.dur)}</span></div>` : ''}
      <span class="absolute top-3 right-3 w-5 h-5 rounded-full bg-wukirsari-primary text-white text-[10px] font-bold flex items-center justify-center opacity-0 group-[.sel]:opacity-100 transition-opacity">✓</span>
    </button>`).join('');
 
  pkgsEl.addEventListener('click', e => {
    const b = e.target.closest('.pkg'); if(!b) return;
    state.pkg = b.dataset.id;
    pkgsEl.querySelectorAll('.pkg').forEach(x => x.classList.toggle('sel', x === b));
    update(); setStep(2);
  });
}
 
/* ====== 2. TANGGAL & SESI ====== */
const dateEl = $('#date');
const iso = d => new Date(d.getTime() - d.getTimezoneOffset() * 60000).toISOString().slice(0, 10);
dateEl.min = iso(new Date());
const def = new Date(); def.setDate(def.getDate() + 1);
dateEl.value = iso(def);
dateEl.addEventListener('change', () => { renderSessions(); update(); setStep(2); });
 
function renderSessions(){
  const sel = $('#session');
  const isToday = dateEl.value === iso(new Date());
  const now = new Date(); const nowMin = now.getHours() * 60 + now.getMinutes();
  const prev = state.session;
  sel.innerHTML = SESSIONS.map(s => {
    let passed = false;
    if(isToday && s.time){ const [h,m] = s.time.split(':').map(Number); passed = (h * 60 + m) <= nowMin; }
    return `<option value="${s.id}" ${passed?'disabled':''}>${esc(s.label)}</option>`;
  }).join('');
  const firstOk = [...sel.options].find(o => !o.disabled);
  const keep = [...sel.options].find(o => o.value === String(prev) && !o.disabled);
  sel.value = keep ? keep.value : (firstOk ? firstOk.value : '');
  state.session = sel.value || null;
  $('#sessHint').textContent = firstOk ? '' : 'Semua sesi jadwal hari ini sudah lewat, silakan pilih tanggal esok atau lainnya.';
}
$('#session').addEventListener('change', e => { state.session = e.target.value; update(); setStep(3); });
 
/* ====== 3. KAPASITAS & ARMADA ====== */
$('#paxPlus').onclick = () => {
  if(state.pax < 40){
    state.pax++;
    state.qty = Math.max(state.qty, Math.ceil(state.pax / PER_JEEP));
    update(); setStep(3);
  }
};
$('#paxMinus').onclick = () => {
  if(state.pax > 1){ state.pax--; update(); setStep(3); }
};
$('#plus').onclick = () => {
  if(state.qty < 10){ state.qty++; update(); setStep(3); }
};
$('#minus').onclick = () => {
  const minJeep = Math.ceil(state.pax / PER_JEEP);
  if(state.qty > minJeep){ state.qty--; update(); }
  else toast('Minimal butuh ' + minJeep + ' jeep untuk ' + state.pax + ' orang penumpang (maksimal 4 orang per jeep).');
};
 
/* ====== SKEMA PEMBAYARAN ====== */
document.querySelectorAll('.po').forEach(b => b.onclick = () => {
  state.pay = b.dataset.p;
  document.querySelectorAll('.po').forEach(x => x.classList.toggle('sel', x === b));
  update();
});
 
/* ====== PENJEMPUTAN OTOMATIS ====== */
const pkKm = n => n.toLocaleString('id-ID', {minimumFractionDigits:1, maximumFractionDigits:1});
function pkFee(){ return (pk.on && !pk.manual && pk.km !== null && pk.km > PK_FREE_KM) ? PK_FEE * state.qty : 0; }
 
function pkHaversine(a, b, c, d){
  const R = 6371, r = x => x * Math.PI / 180, dLat = r(c - a), dLng = r(d - b);
  const h = Math.sin(dLat/2)**2 + Math.cos(r(a)) * Math.cos(r(c)) * Math.sin(dLng/2)**2;
  return 2 * R * Math.asin(Math.sqrt(h));
}
 
/* ====== PETA GOOGLE MAPS (dimuat otomatis saat dibutuhkan) ====== */
const GKEY = D.gmapsKey || '';
let pkMap = null;
let pkPin = null;
let pkMapEngine = null; // 'leaflet' atau 'google'
let pkGP = null;
let pkToken = null;
let pkSearchTimer = null;
let pkSearchAbort = null;
let pkRevAbort = null;
let pkSuggestions = [];
let pkActiveIndex = -1;

// Konfigurasi icon default Leaflet lokal & CDN fallback
if (window.L && L.Icon && L.Icon.Default) {
  delete L.Icon.Default.prototype._getIconUrl;
  L.Icon.Default.mergeOptions({
    iconRetinaUrl: '{{ asset("vendor/leaflet/marker-icon-2x.png") }}',
    iconUrl: '{{ asset("vendor/leaflet/marker-icon.png") }}',
    shadowUrl: '{{ asset("vendor/leaflet/marker-shadow.png") }}',
  });
}

function pkLoadGoogle(){
  if(window.google && google.maps && google.maps.importLibrary) return Promise.resolve();
  if(pkGP) return pkGP;
  pkGP = new Promise((resolve, reject) => {
    window.__gmReady = resolve;
    const s = document.createElement('script');
    s.src = 'https://maps.googleapis.com/maps/api/js?key=' + encodeURIComponent(GKEY) + '&v=weekly&loading=async&language=id&region=ID&callback=__gmReady';
    s.async = true; s.onerror = reject;
    document.head.appendChild(s);
  });
  return pkGP;
}

window.gm_authFailure = () => {
  // Jika Google Maps gagal auth (REQUEST_DENIED/kuota), otomatis alihkan diam-diam ke Leaflet
  pkMapEngine = null;
  pkMap = null;
  pkPin = null;
  pkInitLeaflet();
};

function pkInitLeaflet(){
  const el = document.getElementById('map');
  if(!el || !window.L || pkMapEngine === 'leaflet') return;
  el.innerHTML = '';
  el.classList.remove('hidden');

  const b = (PK_BASE && typeof PK_BASE.lat === 'number')
    ? [PK_BASE.lat, PK_BASE.lng]
    : [-7.6437244, 110.4338597];

  pkMapEngine = 'leaflet';
  pkMap = L.map(el, { center: b, zoom: 12, scrollWheelZoom: true });

  L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener">OpenStreetMap</a> contributors'
  }).addTo(pkMap);

  // Pin Basecamp (lingkaran kuning/emas dengan border gelap)
  L.circleMarker(b, {
    radius: 9,
    fillColor: '#e8a33d',
    color: '#14301b',
    weight: 3,
    opacity: 1,
    fillOpacity: 1
  }).addTo(pkMap).bindTooltip('Basecamp Jeep Wukirsari', { permanent: false, direction: 'top' });

  // Klik di peta untuk memindahkan titik jemput
  pkMap.on('click', e => {
    pkUse(e.latlng.lat, e.latlng.lng, null, false, true);
  });

  setTimeout(() => { if(pkMap && pkMap.invalidateSize) pkMap.invalidateSize(); }, 250);
}

async function pkInitGoogle(){
  const el = document.getElementById('map');
  if(!el) return;
  try{
    await pkLoadGoogle();
    const {Map} = await google.maps.importLibrary('maps');
    const b = (PK_BASE && typeof PK_BASE.lat === 'number') ? {lat: PK_BASE.lat, lng: PK_BASE.lng} : {lat: -7.6437244, lng: 110.4338597};
    pkMapEngine = 'google';
    pkMap = new Map(el, {
      center: b,
      zoom: 12,
      mapTypeControl: false,
      streetViewControl: false,
      fullscreenControl: false,
      clickableIcons: false
    });

    new google.maps.Marker({
      position: b,
      map: pkMap,
      title: 'Basecamp Jeep Wukirsari',
      icon: {
        path: google.maps.SymbolPath.CIRCLE,
        scale: 9,
        fillColor: '#e8a33d',
        fillOpacity: 1,
        strokeColor: '#14301b',
        strokeWeight: 3
      }
    });

    pkMap.addListener('click', e => {
      pkUse(e.latLng.lat(), e.latLng.lng(), null, false, true);
    });
  }catch(e){
    // Google gagal -> gunakan Leaflet
    pkInitLeaflet();
  }
}

function pkInitMap(){
  if(pkMapEngine){
    if(pkMapEngine === 'leaflet' && pkMap && pkMap.invalidateSize){
      setTimeout(() => pkMap.invalidateSize(), 200);
    }
    return;
  }
  if(GKEY && GKEY.trim() !== ''){
    pkInitGoogle().catch(() => pkInitLeaflet());
  }else{
    pkInitLeaflet();
  }
}

function pkSetPin(lat, lng, zoom){
  pkInitMap();
  if(!pkMap) return;

  if(pkMapEngine === 'leaflet'){
    if(!pkPin){
      pkPin = L.marker([lat, lng], { draggable: true }).addTo(pkMap);
      pkPin.bindTooltip('Titik Jemput (geser jika perlu)', { direction: 'top', offset: [0, -20] });
      pkPin.on('dragend', () => {
        const q = pkPin.getLatLng();
        pkUse(q.lat, q.lng, null, false, true);
      });
    }else{
      pkPin.setLatLng([lat, lng]);
    }
    if(zoom){
      pkMap.setView([lat, lng], 16);
    }
  }else if(pkMapEngine === 'google'){
    const pos = {lat, lng};
    if(!pkPin){
      pkPin = new google.maps.Marker({
        position: pos,
        map: pkMap,
        draggable: true,
        title: 'Titik jemput (geser jika perlu)'
      });
      pkPin.addListener('dragend', () => {
        const q = pkPin.getPosition();
        pkUse(q.lat(), q.lng(), null, false, true);
      });
    }else{
      pkPin.setPosition(pos);
    }
    if(zoom){
      pkMap.setCenter(pos);
      pkMap.setZoom(16);
    }
  }
}

/* Satu pintu pemakaian titik: tandai pin di peta, hitung jarak, dan opsional reverse geocode */
function pkUse(lat, lng, label, zoom, doReverse){
  pkSetPin(lat, lng, zoom);
  pkCompute(lat, lng, label);
  if(doReverse){
    pkDoReverse(lat, lng);
  }
}

async function pkDoReverse(lat, lng){
  if(pkRevAbort) pkRevAbort.abort();
  pkRevAbort = new AbortController();

  try{
    const url = (D.reverseUrl || '/lokasi-jemput/reverse') + '?lat=' + encodeURIComponent(lat) + '&lng=' + encodeURIComponent(lng);
    const r = await fetch(url, { signal: pkRevAbort.signal, headers: { 'Accept': 'application/json' } });
    if(r.ok){
      const j = await r.json();
      if(j && j.ok && (j.name || j.address)){
        const title = j.name || j.address;
        const sub = j.address || title;
        // Teks ketikan user tidak boleh ditimpa jika ia hanya menggeser pin
        const addrInput = $('#addr');
        if(addrInput && !addrInput.value.trim()){
          addrInput.value = title;
        }
        pk.label = (addrInput && addrInput.value.trim()) ? addrInput.value.trim() : sub;
        const gpsEl = $('#gpsName');
        if(gpsEl) gpsEl.textContent = pk.label;
      }
    }
  }catch(e){
    // Aborted or network glitch, silent ignore
  }
}

async function pkCompute(lat, lng, label){
  const id = ++pkCalcId;
  Object.assign(pk, {
    lat,
    lng,
    label: label || pk.label || 'Titik penjemputan',
    manual: false,
    km: null,
    calc: true,
    failed: false,
    approx: false
  });
  update();

  if(!PK_BASE || typeof PK_BASE.lat !== 'number' || typeof PK_BASE.lng !== 'number'){
    pk.calc = false; pk.failed = true; update();
    toast('Koordinat basecamp belum diatur di controller.');
    return;
  }

  let km = null, approx = false;

  // 1. Coba hitung via server (Google Routes -> OSRM gratis -> Haversine x 1.3)
  for(let i = 0; i < 2 && km === null; i++){
    try{
      const ctl = new AbortController();
      const to = setTimeout(() => ctl.abort(), 9000);
      const r = await fetch(D.distanceUrl + '?lat=' + encodeURIComponent(lat) + '&lng=' + encodeURIComponent(lng), {
        signal: ctl.signal,
        headers: {'Accept': 'application/json'}
      });
      clearTimeout(to);
      const j = await r.json();
      if(r.ok && typeof j.km === 'number'){
        km = j.km;
        approx = !!j.approx;
      }
    }catch(e){}
  }

  // 2. Fallback instan client-side jika server offline/terkendala
  if(km === null){
    const hKm = pkHaversine(PK_BASE.lat, PK_BASE.lng, lat, lng) * 1.3;
    km = Math.round(hKm * 10) / 10;
    approx = true;
  }

  if(id !== pkCalcId) return;
  pk.calc = false;
  pk.km = Math.round(km * 10) / 10;
  pk.approx = approx;
  update();
}

function pkParse(t){
  if(!t) return null;
  try{ t = decodeURIComponent(t.trim()); }catch(e){ t = t.trim(); }
  
  // Format pin Google Maps: !3d-7.7956!4d110.3695
  let m = t.match(/!3d(-?\d+\.\d+)!4d(-?\d+\.\d+)/);
  if(m) return checkBounds(parseFloat(m[1]), parseFloat(m[2]));

  // Query parameter: ?q=-7.7956,110.3695 atau ll atau destination
  m = t.match(/[?&](?:q|query|ll|destination)=(-?\d+\.\d+)[,\s+]+(-?\d+\.\d+)/);
  if(m) return checkBounds(parseFloat(m[1]), parseFloat(m[2]));

  // Format tengah peta Google Maps: @-7.7956,110.3695
  m = t.match(/@(-?\d+\.\d+),\s*(-?\d+\.\d+)/);
  if(m) return checkBounds(parseFloat(m[1]), parseFloat(m[2]));

  // Koordinat murni: -7.7956, 110.3695
  m = t.match(/^(-?\d{1,2}\.\d+)[,\s]+(-?\d{2,3}\.\d+)$/);
  if(m) return checkBounds(parseFloat(m[1]), parseFloat(m[2]));

  // Angka koordinat dalam teks bebas
  m = t.match(/(-?\d{1,2}\.\d{3,})[,\s]+(1\d{2}\.\d{3,})/);
  if(m) return checkBounds(parseFloat(m[1]), parseFloat(m[2]));

  return null;
}

function checkBounds(lat, lng){
  if(isNaN(lat) || isNaN(lng)) return null;
  if(lat < -11.5 || lat > 6.5 || lng < 94 || lng > 141.5) return null;
  return {lat, lng};
}

/* ====== PENCARIAN NAMA HOTEL / TEMPAT PENJEMPUTAN (BACKEND PROXY) ====== */
let pkSessionToken = null;

function pkGetSessionToken(){
  if(!pkSessionToken){
    pkSessionToken = (window.crypto && crypto.randomUUID)
      ? crypto.randomUUID()
      : 's_' + Date.now() + '_' + Math.random().toString(36).substring(2, 9);
  }
  return pkSessionToken;
}

function pkResetSessionToken(){
  pkSessionToken = null;
}

async function pkSearch(q){
  const box = $('#addrRes');
  const spin = $('#addrSpin');
  if(!box) return;

  if(pkSearchAbort){
    pkSearchAbort.abort();
  }
  pkSearchAbort = new AbortController();

  pkActiveIndex = -1;
  pkSuggestions = [];

  if(spin) spin.classList.remove('hidden');

  box.innerHTML = `
    <div class="p-3.5 text-xs text-merapiText-secondary flex items-center justify-center gap-2">
      <svg class="animate-spin h-4 w-4 text-wukirsari-primary shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
      </svg>
      <span>Mencari lokasi penjemputan…</span>
    </div>`;
  box.classList.remove('hidden');

  let items = [];

  try{
    const searchUrl = (D.searchUrl || '/api/places-autocomplete')
      + '?q=' + encodeURIComponent(q);

    const r = await fetch(searchUrl, {
      signal: pkSearchAbort.signal,
      headers: {'Accept': 'application/json'}
    });

    const data = await r.json().catch(() => null);

    if(r.ok && data && Array.isArray(data.results)){
      items = data.results.slice(0, 5);
    }
  }catch(e){
    if(e.name === 'AbortError') return;
  }

  if(spin) spin.classList.add('hidden');
  pkSuggestions = items;
  pkRenderSuggestions(items);
}

function formatHotelDisplay(name, address){
  name = (name || '').trim();
  address = (address || '').trim();
  if(!name && !address) return '';
  if(!address) return name;
  if(!name) return address;

  if(name.toLowerCase() === address.toLowerCase()) return name;

  // Format: "Nama Hotel – alamat singkat"
  const parts = address.split(',').map(s => s.trim()).filter(Boolean);
  const shortAddr = parts.slice(0, 2).join(', ');

  if(shortAddr && name.toLowerCase().includes(parts[0].toLowerCase())){
    return name;
  }
  return shortAddr ? `${name} – ${shortAddr}` : name;
}

function pkRenderSuggestions(items){
  const box = $('#addrRes');
  if(!box) return;

  if(!items || items.length === 0){
    box.innerHTML = `
      <div class="p-4 text-center text-xs text-merapiText-secondary space-y-1.5">
        <div class="font-bold text-merapiText-primary">Hotel tidak ditemukan</div>
        <div class="text-[11px] text-merapiText-muted leading-relaxed">
          Geser pin di peta atau tempel link Google Maps.
        </div>
      </div>`;
    box.classList.remove('hidden');
    return;
  }

  const src = items[0]?.source || 'none';
  let attrText = 'Pilih lokasi yang tepat';
  if(src === 'local_json'){
    attrText = 'Daftar Hotel Tersimpan';
  } else if(src === 'photon'){
    attrText = 'Ditenagai oleh Photon (OpenStreetMap)';
  } else if(src === 'google'){
    attrText = 'Ditenagai oleh Google Places';
  }

  let html = `<div class="max-h-64 overflow-y-auto divide-y divide-wukirsari-border/60" id="suggestList">`;
  items.forEach((item, idx) => {
    html += `
      <button type="button" class="sug-item w-full text-left px-4 py-2.5 hover:bg-wukirsari-sage/40 transition-colors flex items-start gap-3 cursor-pointer group select-none" data-idx="${idx}">
        <div class="w-6 h-6 rounded-lg bg-wukirsari-sage text-wukirsari-primary flex items-center justify-center shrink-0 mt-0.5 group-hover:bg-wukirsari-primary group-hover:text-white transition-colors">
          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </div>
        <div class="flex-1 min-w-0">
          <div class="font-bold text-xs sm:text-sm text-merapiText-primary group-hover:text-wukirsari-primary transition-colors truncate">${esc(item.name)}</div>
          ${item.address ? `<div class="text-[11px] text-merapiText-muted truncate mt-0.5">${esc(item.address)}</div>` : ''}
        </div>
      </button>`;
  });
  html += `</div>
    <div class="px-4 py-1.5 bg-[#fbfbf8] text-[10px] text-merapiText-muted border-t border-wukirsari-border/60 flex items-center justify-between">
      <span>Pilih lokasi yang tepat</span>
      <span class="font-medium">${attrText}</span>
    </div>`;

  box.innerHTML = html;
  box.classList.remove('hidden');

  box.querySelectorAll('.sug-item').forEach(btn => {
    btn.onclick = (e) => {
      e.preventDefault();
      pkSelectSuggestion(+btn.dataset.idx);
    };
  });
}

async function pkSelectSuggestion(idx){
  const box = $('#addrRes');
  if(box) box.classList.add('hidden');
  const item = pkSuggestions[idx];
  if(!item) return;

  const hotelText = formatHotelDisplay(item.name, item.address);
  const addrInput = $('#addr');
  if(addrInput) addrInput.value = hotelText;

  // Jika item sudah punya koordinat (langsung dari hotels.json / Photon / Google Places)
  if(typeof item.lat === 'number' && typeof item.lng === 'number' && item.lat !== 0 && item.lng !== 0){
    pkUse(item.lat, item.lng, hotelText, true, false);
    return;
  }

  // Jika perlu detail dari server (fallback ID)
  if(item.id){
    const spin = $('#addrSpin');
    if(spin) spin.classList.remove('hidden');
    try{
      const detailUrl = (D.detailUrl || '/api/place-details') + '?id=' + encodeURIComponent(item.id);
      const res = await fetch(detailUrl, { headers: {'Accept': 'application/json'} });
      if(spin) spin.classList.add('hidden');
      if(res.ok){
        const detail = await res.json();
        if(detail.ok && typeof detail.lat === 'number'){
          const detailText = formatHotelDisplay(detail.name || item.name, detail.address || item.address);
          if(addrInput) addrInput.value = detailText;
          pkUse(detail.lat, detail.lng, detail.name || detailText, true, false);
          return;
        }
      }
    }catch(e){
      if(spin) spin.classList.add('hidden');
    }
  }
}

/* ====== B. TEMPEL LINK GOOGLE MAPS / KOORDINAT ====== */

let pkLinkTimer = null;
async function pkProcessLink(raw){
  const v = (raw || '').trim();
  if(!v) return;

  const btn = document.getElementById('btnLink');
  const origText = btn ? btn.textContent : 'Hitung';
  if(btn){
    btn.disabled = true;
    btn.textContent = 'Membaca…';
  }

  try{
    const resolveUrl = (D.resolveUrl || '/api/resolve-place') + '?input=' + encodeURIComponent(v);
    const res = await fetch(resolveUrl, {
      headers: {
        'Accept': 'application/json'
      }
    });
    const data = await res.json();

    if(btn){
      btn.disabled = false;
      btn.textContent = origText;
    }

    if(data && data.ok && typeof data.lat === 'number'){
      const hotelText = formatHotelDisplay(data.name, data.address);
      if(hotelText){
        $('#addr').value = hotelText;
      }
      // Pindahkan pin Leaflet, zoom ke titik, dan hitung jarak (doReverse = false agar nama hotel tidak tertimpa)
      pkUse(data.lat, data.lng, data.name || hotelText, true, false);
      toast('Lokasi penjemputan berhasil dibaca!');
      return;
    }else{
      toast('Nama hotel tidak terbaca otomatis, silakan ketik manual.');
    }
  }catch(e){
    if(btn){
      btn.disabled = false;
      btn.textContent = origText;
    }
    toast('Nama hotel tidak terbaca otomatis, silakan ketik manual.');
  }
}
 
function pkRender(){
  if(!$('#vPick') || !$('#rowExtra') || !$('#dist') || !$('#gpsInfo')) return;
  const fee = pkFee(), vp = $('#vPick'), extra = $('#rowExtra'), dist = $('#dist'), gps = $('#gpsInfo');
  vp.classList.remove('text-emerald-700'); extra.classList.remove('show');
  if(!pk.on) vp.textContent = 'Tidak dipilih';
  else if(pk.manual) vp.textContent = 'Dikonfirmasi admin';
  else if(pk.calc) vp.textContent = 'Menghitung…';
  else if(pk.km === null) vp.textContent = 'Tentukan lokasi';
  else if(fee === 0){ vp.textContent = 'Gratis (' + pkKm(pk.km) + ' km)'; vp.classList.add('text-emerald-700'); }
  else{
    vp.textContent = 'Jarak ' + pkKm(pk.km) + ' km';
    extra.classList.add('show');
    $('#lExtra').textContent = 'Biaya Tambahan Jemput (' + rp(PK_FEE) + ' × ' + state.qty + ' jeep)';
    $('#vExtra').textContent = '+ ' + rp(fee);
  }
 
  dist.classList.toggle('over', pk.km !== null && pk.km > PK_FREE_KM);
  if(pk.manual){ $('#distKm').textContent = '—'; $('#distTxt').textContent = 'Biaya penjemputan akan dikonfirmasi admin lewat WhatsApp.'; $('#distTag').textContent = 'Via admin'; }
  else if(pk.calc){ $('#distKm').textContent = '…'; $('#distTxt').textContent = 'Menghitung jarak rute dari basecamp…'; $('#distTag').textContent = 'Menghitung'; }
  else if(pk.failed){ $('#distKm').textContent = '—'; $('#distTxt').textContent = 'Jarak rute belum bisa dihitung. Coba lagi atau pilih "Biaya dikonfirmasi admin".'; $('#distTag').textContent = 'Gagal'; }
  else if(pk.km === null){ $('#distKm').textContent = '—'; $('#distTxt').textContent = 'Ketik nama hotel untuk menghitung jarak otomatis.'; $('#distTag').textContent = 'Belum dihitung'; }
  else if(pk.km <= PK_FREE_KM){
    $('#distKm').textContent = pkKm(pk.km) + ' km';
    $('#distTxt').textContent = 'Jarak ' + (pk.approx ? 'perkiraan' : 'rute jalan') + ' dari basecamp. Masih dalam jangkauan jemput gratis.';
    $('#distTag').textContent = 'GRATIS';
  }else{
    $('#distKm').textContent = pkKm(pk.km) + ' km';
    $('#distTxt').textContent = 'Lebih dari batas gratis ' + PK_FREE_KM + ' km: ' + rp(PK_FEE) + ' × ' + state.qty + ' jeep = ' + rp(fee) + (pk.approx ? ' (jarak perkiraan)' : '');
    $('#distTag').textContent = '+ ' + rp(fee);
  }
 
  const has = pk.on && !pk.manual && pk.lat !== null;
  gps.classList.toggle('hidden', !has);
  if(has){
    $('#gpsName').textContent = pk.label;
    $('#gpsCoord').textContent = pk.lat.toFixed(5) + ', ' + pk.lng.toFixed(5);
    $('#gpsLink').href = 'https://www.google.com/maps?q=' + pk.lat + ',' + pk.lng;
  }
}
 
/* ====== HANDLER PENJEMPUTAN (delegation) ====== */
document.addEventListener('click', e => {
  const t = e.target;

  const ar = t.closest('.ar');
  if(ar){
    pk.on = ar.dataset.m === 'shuttle';
    document.querySelectorAll('.ar').forEach(x => x.classList.toggle('sel', x === ar));
    const box = document.getElementById('pickupBox');
    if(box) box.classList.toggle('hidden', !pk.on);
    if(pk.on){
      pkInitMap();
      setTimeout(() => {
        if(pkMapEngine === 'leaflet' && pkMap && pkMap.invalidateSize){
          pkMap.invalidateSize();
        }
      }, 200);
    }
    update();
    return;
  }

  if(t.closest('#btnGps')){
    const isHttpsOrLocal = window.isSecureContext || location.hostname === 'localhost' || location.hostname === '127.0.0.1';
    if(!isHttpsOrLocal){
      toast('Fitur Bagikan Lokasi memerlukan koneksi HTTPS. Silakan cari nama hotel di atas atau tempel link Google Maps di bawah.');
      return;
    }

    const btn = document.getElementById('btnGps');
    const origHtml = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = `<svg class="animate-spin h-4 w-4 inline-block -mt-0.5 mr-2" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg><span>Sedang mencari lokasi...</span>`;

    const restoreBtn = () => {
      btn.disabled = false;
      btn.innerHTML = origHtml;
    };

    const handleGeoError = (err) => {
      restoreBtn();
      if(err.code === 1){ // PERMISSION_DENIED
        toast('Izin lokasi ditolak. Aktifkan izin lokasi di pengaturan browser (ikon gembok di samping alamat situs), lalu coba lagi. Atau cari nama hotel / tempel link Google Maps.');
      }else if(err.code === 2){ // POSITION_UNAVAILABLE
        toast('Lokasi tidak terdeteksi. Pastikan GPS/Lokasi HP menyala, atau cari nama hotel.');
      }else if(err.code === 3){ // TIMEOUT
        toast('Mencari lokasi terlalu lama. Coba lagi di tempat terbuka atau cari nama hotel.');
      }else{
        toast('Gagal mendeteksi lokasi (' + (err.message || 'Error') + '). Silakan cari nama hotel atau tempel link Google Maps.');
      }
    };

    // Tahap 1: Coba akurasi standar (lebih cepat dan minim timeout)
    navigator.geolocation.getCurrentPosition(
      pos => {
        restoreBtn();
        pkUse(pos.coords.latitude, pos.coords.longitude, 'Lokasi GPS Anda', true, true);
        toast('Lokasi berhasil terdeteksi!');
      },
      err1 => {
        if(err1.code === 3){ // TIMEOUT -> Coba sekali lagi dengan akurasi tinggi
          btn.innerHTML = `<svg class="animate-spin h-4 w-4 inline-block -mt-0.5 mr-2" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg><span>Mencoba kembali dengan akurasi GPS tinggi...</span>`;
          navigator.geolocation.getCurrentPosition(
            pos => {
              restoreBtn();
              pkUse(pos.coords.latitude, pos.coords.longitude, 'Lokasi GPS Anda', true, true);
              toast('Lokasi berhasil terdeteksi!');
            },
            err2 => handleGeoError(err2),
            {enableHighAccuracy: true, timeout: 20000, maximumAge: 0}
          );
        }else{
          handleGeoError(err1);
        }
      },
      {enableHighAccuracy: false, timeout: 15000, maximumAge: 60000}
    );
    return;
  }

  if(t.closest('#btnLink')){
    const linkInput = document.getElementById('link');
    const v = (linkInput ? linkInput.value : '').trim();
    if(!v){ toast('Tempel link Google Maps atau koordinat dulu.'); return; }
    pkProcessLink(v);
    return;
  }

  if(t.closest('#btnManual')){
    ++pkCalcId;
    Object.assign(pk, {manual: true, km: null, lat: null, lng: null, calc: false, label: ''});
    update();
    toast('Baik, biaya jemput akan dikonfirmasi admin lewat WhatsApp.');
    return;
  }

  if(!t.closest('#addr, #addrRes')){
    const r = document.getElementById('addrRes');
    if(r) r.classList.add('hidden');
    pkActiveIndex = -1;
  }
});

document.addEventListener('input', e => {
  if(e.target.id === 'addr'){
    clearTimeout(pkSearchTimer);
    const q = e.target.value.trim();
    if(q.length < 3){
      const box = document.getElementById('addrRes');
      if(box) box.classList.add('hidden');
      const spin = document.getElementById('addrSpin');
      if(spin) spin.classList.add('hidden');
      return;
    }
    pkSearchTimer = setTimeout(() => pkSearch(q), 400);
  } else if(e.target.id === 'link'){
    clearTimeout(pkLinkTimer);
    const v = e.target.value.trim();
    if(v.length > 10 && (/goo\.gl|maps\.app|google\.com\/maps/i.test(v) || /^-?\d{1,2}\.\d+/.test(v))){
      pkLinkTimer = setTimeout(() => pkProcessLink(v), 500);
    }
  }
});

document.addEventListener('paste', e => {
  if(e.target.id === 'link'){
    setTimeout(() => {
      const v = e.target.value.trim();
      if(v) pkProcessLink(v);
    }, 50);
  }
});

document.addEventListener('keydown', e => {
  if(e.target.id === 'addr'){
    const box = document.getElementById('addrRes');
    const items = box ? box.querySelectorAll('.sug-item') : [];
    const isBoxOpen = box && !box.classList.contains('hidden') && items.length > 0;

    if(e.key === 'Enter'){
      e.preventDefault();
      if(isBoxOpen && pkActiveIndex >= 0 && pkActiveIndex < items.length){
        pkSelectSuggestion(pkActiveIndex);
      } else if(isBoxOpen && items.length > 0){
        pkSelectSuggestion(0);
      } else {
        // Jalankan pencarian langsung saat user menekan Enter
        clearTimeout(pkSearchTimer);
        const q = e.target.value.trim();
        if(q.length >= 3){
          pkSearch(q);
        }
      }
      return;
    }

    if(e.key === 'Escape'){
      e.preventDefault();
      if(box) box.classList.add('hidden');
      pkActiveIndex = -1;
      return;
    }

    if(!isBoxOpen) return;

    if(e.key === 'ArrowDown'){
      e.preventDefault();
      pkActiveIndex = (pkActiveIndex + 1) % items.length;
      pkHighlightSuggestion(items);
    }else if(e.key === 'ArrowUp'){
      e.preventDefault();
      pkActiveIndex = (pkActiveIndex - 1 + items.length) % items.length;
      pkHighlightSuggestion(items);
    }
  }

  if(e.target.id === 'link' && e.key === 'Enter'){
    e.preventDefault();
    document.getElementById('btnLink').click();
  }
});

function pkHighlightSuggestion(items){
  items.forEach((it, i) => {
    const isActive = (i === pkActiveIndex);
    it.classList.toggle('bg-wukirsari-sage/70', isActive);
    if(isActive){
      it.scrollIntoView({ block: 'nearest' });
    }
  });
}
 
/* ====== UPDATE RINGKASAN TAGIHAN ====== */
function update(){
  const p = curPkg();
  if(!p) return;
  const total = p.price * state.qty + pkFee();
  const due = state.pay === 'dp' ? Math.round(total * 0.3) : total;
 
  $('#qty').textContent = state.qty;
  $('#pax').textContent = state.pax;
  $('#jeepHint').textContent = state.qty + ' Jeep × ' + rp(p.price);
  $('#tPkg').textContent = p.name + ' (' + state.qty + ' Jeep · ' + state.pax + ' Orang)';
 
  const d = dateEl.value ? new Date(dateEl.value + 'T00:00:00').toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' }) : 'Belum pilih tanggal';
  const sObj = SESSIONS.find(s => String(s.id) === String(state.session));
  $('#tDate').textContent = d + (sObj ? ' · ' + sObj.label : '');
 
  $('#lJeep').textContent = 'Sewa Jeep' + (state.qty > 1 ? ' (' + state.qty + '×)' : '');
  $('#vJeep').textContent = rp(p.price * state.qty);
 
  $('#vTotal').textContent = rp(total);
  $('#mTotal').textContent = rp(total);
  $('#vDue').textContent = rp(due);
  $('#dueLbl').textContent = state.pay === 'dp' ? 'Wajib Dibayar Sekarang (DP 30%)' : 'Wajib Dibayar Sekarang (Lunas)';
  $('#dueSub').textContent = state.pay === 'dp' ? 'Sisa ' + rp(total - due) : 'Lunas (100%)';
  pkRender();
}
 
/* ====== STEPPER INTERACTION ====== */
function setStep(n){
  document.querySelectorAll('.st').forEach(s => {
    const k = +s.dataset.s;
    s.classList.toggle('on', k === n);
    s.classList.toggle('done', k < n);
    s.querySelector('i').textContent = k < n ? '✓' : k;
  });
}
 
document.querySelectorAll('.st').forEach(s => s.onclick = () => {
  const n = +s.dataset.s;
  const sec = document.querySelector(`[data-sec="${n}"]`);
  if (sec) {
    sec.scrollIntoView({ behavior: 'smooth', block: 'center' });
    setStep(n);
  }
});
 
/* ====== TOAST ====== */
let tt;
function toast(msg){
  const t = $('#toast');
  t.textContent = msg;
  t.classList.add('show');
  clearTimeout(tt);
  tt = setTimeout(() => t.classList.remove('show'), 3200);
}
 
/* ====== SUBMIT FORM ====== */
$('#submit').onclick = () => {
  const name = $('#name'), wa = $('#wa'), addr = $('#addr');
  [name, wa, addr].forEach(x => x.classList.remove('err'));
 
  if(!dateEl.value){
    toast('Pilih tanggal kunjungan trip terlebih dahulu.');
    return dateEl.focus();
  }
  if(!state.session){
    toast('Pilih sesi waktu keberangkatan yang tersedia.');
    return $('#session').focus();
  }
  if(pk.on){
    if(addr.value.trim().length < 3){ addr.classList.add('err'); toast('Isi nama hotel / alamat penjemputan.'); return addr.focus(); }
    if(pk.calc){ toast('Jarak penjemputan masih dihitung, tunggu sebentar.'); return; }
    if(pk.km === null && !pk.manual){ toast('Jarak belum terhitung. Pilih hotel dari daftar, tandai di peta, bagikan lokasi, atau tempel koordinat.'); return addr.focus(); }
  }
  if(name.value.trim().length < 3){
    name.classList.add('err');
    toast('Isi nama lengkap pemesan.');
    return name.focus();
  }
  if(!/^(\+?62|0)8\d{7,12}$/.test(wa.value.replace(/[\s-]/g, ''))){
    wa.classList.add('err');
    toast('Nomor WhatsApp belum valid (gunakan format 08xx / 628xx).');
    return wa.focus();
  }
 
  const f = $('#bookForm');
  const set = (n, v) => { f.elements[n].value = v; };
 
  set('package_id', curPkg().id);
  set('slot_id', state.session);
  set('trip_date', dateEl.value);
  set('passenger_count', state.pax);
  set('jeep_quantity', state.qty);
  set('arrival_type', pk.on ? 'pickup' : 'self_drive');
  set('pickup_address', pk.on ? addr.value.trim() : '');
  set('pickup_lat', pk.on && !pk.manual && pk.lat !== null ? pk.lat : '');
  set('pickup_lng', pk.on && !pk.manual && pk.lng !== null ? pk.lng : '');
  set('pickup_manual', pk.on && pk.manual ? 1 : 0);
  set('customer_name', name.value.trim());
  set('customer_phone', wa.value.trim());
  set('payment_mode', state.pay === 'dp' ? 'DP' : 'FULL');
  set('notes', $('#notes').value.trim());
 
  const btn = $('#submit');
  btn.disabled = true;
  btn.innerHTML = `<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg> Memproses E-Tiket…`;
  f.submit();
};
 
$('#notes').addEventListener('input', e => {
  $('#notesCount').textContent = e.target.value.length;
});
 
renderSessions();
update();
</script>
@endsection