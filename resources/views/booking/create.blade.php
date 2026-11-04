@extends('layouts.app')
 
@section('title', 'Formulir Pemesanan Jeep | Jeep Wukirsari Adventure')
 
@section('content')
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
                            <input id="addr" autocomplete="off" placeholder="Ketik nama hotel, mis. Villa Padi"
                                   class="w-full px-4 py-3.5 rounded-2xl border border-wukirsari-border bg-white text-merapiText-primary text-sm focus:outline-none focus:border-wukirsari-primary focus:ring-2 focus:ring-wukirsari-primary/15 transition-all">
                            <div id="addrRes" class="hidden absolute left-0 right-0 top-full mt-1 z-20 rounded-2xl border border-wukirsari-border bg-white shadow-lg max-h-60 overflow-y-auto"></div>
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
                            <div id="map" class="w-full rounded-2xl border border-wukirsari-border overflow-hidden" style="height:290px;z-index:1"></div>
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
let pkMap = null, pkPin = null, pkGP = null, pkToken = null;
window.gm_authFailure = () => toast('Kunci Google Maps ditolak. Cek pembatasan domain dan API di Google Cloud.');
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
async function pkInitMap(){
  const el = document.getElementById('map');
  if(!el) return;
  if(!GKEY){ el.classList.add('hidden'); return; }
  try{ await pkLoadGoogle(); }catch(e){ el.classList.add('hidden'); return; }
  if(!pkMap){
    const {Map} = await google.maps.importLibrary('maps');
    const b = (PK_BASE && typeof PK_BASE.lat === 'number') ? {lat: PK_BASE.lat, lng: PK_BASE.lng} : {lat: -7.6, lng: 110.4};
    pkMap = new Map(el, {center: b, zoom: 12, mapTypeControl: false, streetViewControl: false, fullscreenControl: false, clickableIcons: false});
    new google.maps.Marker({position: b, map: pkMap, title: 'Basecamp',
      icon: {path: google.maps.SymbolPath.CIRCLE, scale: 9, fillColor: '#e8a33d', fillOpacity: 1, strokeColor: '#14301b', strokeWeight: 3}});
    pkMap.addListener('click', e => pkUse(e.latLng.lat(), e.latLng.lng(), 'Titik yang ditandai di peta', false));
  }
}
async function pkSetPin(lat, lng, zoom){
  await pkInitMap();
  if(!pkMap) return;
  const pos = {lat, lng};
  if(!pkPin){
    pkPin = new google.maps.Marker({position: pos, map: pkMap, draggable: true, title: 'Titik jemput (geser jika perlu)'});
    pkPin.addListener('dragend', () => { const q = pkPin.getPosition(); pkUse(q.lat(), q.lng(), 'Titik yang ditandai di peta', false); });
  }else{
    pkPin.setPosition(pos);
  }
  if(zoom){ pkMap.setCenter(pos); pkMap.setZoom(17); }
}
/* Satu pintu: tandai pin di peta lalu hitung jarak rute */
function pkUse(lat, lng, label, zoom){
  pkSetPin(lat, lng, zoom);
  return pkCompute(lat, lng, label);
}

async function pkCompute(lat, lng, label){
  const id = ++pkCalcId;
  Object.assign(pk, {lat, lng, label: label || 'Titik koordinat', manual: false, km: null, calc: true, failed: false, approx: false});
  update();
  if(!PK_BASE || typeof PK_BASE.lat !== 'number' || typeof PK_BASE.lng !== 'number'){
    pk.calc = false; pk.failed = true; update();
    toast('Koordinat basecamp belum diatur di controller.');
    return;
  }
  // Jarak rute jalan dari server (Google Routes API). Dicoba 2x; kalau gagal TIDAK ada angka tebakan.
  let km = null, approx = false;
  for(let i = 0; i < 2 && km === null; i++){
    try{
      const ctl = new AbortController(); const to = setTimeout(() => ctl.abort(), 12000);
      const r = await fetch(D.distanceUrl + '?lat=' + encodeURIComponent(lat) + '&lng=' + encodeURIComponent(lng), {signal: ctl.signal, headers: {'Accept': 'application/json'}});
      clearTimeout(to);
      const j = await r.json();
      if(r.ok && typeof j.km === 'number'){ km = j.km; approx = !!j.approx; }
    }catch(e){}
  }
  if(id !== pkCalcId) return;
  pk.calc = false;
  if(km === null){
    pk.km = null; pk.failed = true;
    update();
    toast('Jarak rute belum bisa dihitung. Coba lagi, atau pilih "Biaya dikonfirmasi admin".');
    return;
  }
  pk.km = Math.round(km * 10) / 10; pk.approx = approx;
  update();
}
 
function pkParse(t){
  try{ t = decodeURIComponent(t.trim()); }catch(e){ t = t.trim(); }
  const m = t.match(/\x40(-?\d+\.\d+),\s*(-?\d+\.\d+)/)
         || t.match(/!3d(-?\d+\.\d+)!4d(-?\d+\.\d+)/)
         || t.match(/[?&](?:q|query|ll|destination)=(-?\d+\.\d+)[,\s+]+(-?\d+\.\d+)/)
         || t.match(/^(-?\d{1,2}\.\d+)[,\s]+(-?\d{2,3}\.\d+)$/);
  if(!m) return null;
  const lat = parseFloat(m[1]), lng = parseFloat(m[2]);
  if(lat < -11.5 || lat > 6.5 || lng < 94 || lng > 141.5) return null;
  return {lat, lng};
}
 
async function pkSearch(q){
  const box = $('#addrRes'), id = ++pkSearchId;
  const ROW = 'block w-full text-left px-4 py-2.5 text-xs border-b border-wukirsari-border text-merapiText-primary';
  const msg = t => { box.innerHTML = ''; const n = document.createElement('div'); n.className = ROW; n.textContent = t; box.appendChild(n); box.classList.remove('hidden'); };
  if(!GKEY){ msg('Pencarian nama hotel belum aktif (kunci Google belum diatur). Gunakan Bagikan Lokasi atau tempel koordinat.'); return; }
  msg('Mencari lokasi…');
  let suggestions = [];
  try{
    await pkLoadGoogle();
    const {AutocompleteSuggestion, AutocompleteSessionToken} = await google.maps.importLibrary('places');
    pkToken = pkToken || new AutocompleteSessionToken();
    const res = await AutocompleteSuggestion.fetchAutocompleteSuggestions({
      input: q, sessionToken: pkToken, includedRegionCodes: ['id'], language: 'id', region: 'id',
      locationBias: {center: {lat: PK_BASE.lat, lng: PK_BASE.lng}, radius: 50000}
    });
    suggestions = (res.suggestions || []).filter(s => s.placePrediction);
  }catch(e){
    msg('Pencarian Google gagal. Pastikan "Places API (New)" aktif untuk kunci ini, atau pakai Bagikan Lokasi / koordinat.');
    return;
  }
  if(id !== pkSearchId) return;
  if(!suggestions.length){ msg('Lokasi tidak ditemukan. Coba nama lain, atau klik peta di bawah pada lokasi hotel.'); return; }
 
  box.innerHTML = '';
  const head = document.createElement('div');
  head.className = ROW + ' bg-wukirsari-cream text-merapiText-muted';
  head.textContent = 'Pilih lokasi yang sesuai:';
  box.appendChild(head);
  suggestions.forEach(sg => {
    const pred = sg.placePrediction;
    const b = document.createElement('button');
    b.type = 'button';
    b.className = ROW + ' hover:bg-wukirsari-cream';
    b.textContent = pred.text.text;
    b.onclick = async () => {
      box.classList.add('hidden');
      try{
        const place = pred.toPlace();
        await place.fetchFields({fields: ['location', 'displayName', 'formattedAddress']});
        pkToken = null; // sesi pencarian selesai
        const label = [place.displayName, place.formattedAddress].filter(Boolean).join(', ');
        $('#addr').value = place.displayName || pred.text.text;
        pkUse(place.location.lat(), place.location.lng(), label || pred.text.text, true);
      }catch(e){
        toast('Gagal mengambil detail lokasi. Coba pilih lagi atau klik peta.');
      }
    };
    box.appendChild(b);
  });
  box.classList.remove('hidden');
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
    if(pk.on) pkInitMap();
    update();
    return;
  }
 
  if(t.closest('#btnGps')){
    if(!navigator.geolocation){ toast('Browser tidak mendukung lokasi. Gunakan nama hotel atau koordinat.'); return; }
    if(window.isSecureContext === false){ toast('Fitur lokasi GPS hanya berfungsi di HTTPS. Gunakan nama hotel atau koordinat.'); return; }
    toast('Mengambil lokasi Anda… pilih "Izinkan" jika browser meminta izin.');
    navigator.geolocation.getCurrentPosition(
      p => {
        const acc = Math.round(p.coords.accuracy || 0);
        pkUse(p.coords.latitude, p.coords.longitude, 'Lokasi GPS perangkat Anda (akurasi ±' + acc + ' m)', true);
        toast(acc > 100 ? 'Akurasi lokasi ±' + acc + ' m. Geser pin di peta agar tepat di hotel.' : 'Lokasi terdeteksi (akurasi ±' + acc + ' m).');
      },
      err => toast(err.code === 1 ? 'Izin lokasi diblokir. Klik ikon gembok di address bar, izinkan Lokasi, atau ketik nama hotel.'
        : err.code === 2 ? 'Lokasi tidak terdeteksi. Ketik nama hotel atau tempel koordinat.'
        : 'Waktu habis. Coba lagi atau ketik nama hotel.'),
      {enableHighAccuracy: true, timeout: 10000, maximumAge: 0});
    return;
  }
 
  if(t.closest('#btnLink')){
    const v = (document.getElementById('link').value || '').trim();
    if(!v){ toast('Tempel link Google Maps atau koordinat dulu.'); return; }
    const pt = pkParse(v);
    if(!pt){
      toast(/goo\.gl|maps\.app/i.test(v) ? 'Link pendek tidak bisa dibaca. Salin koordinat dari Google Maps.' : 'Format belum dikenali. Contoh: -7.7956, 110.3695');
      return;
    }
    pkUse(pt.lat, pt.lng, 'Lokasi dari link/koordinat', true);
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
  }
});
 
document.addEventListener('input', e => {
  if(e.target.id !== 'addr') return;
  clearTimeout(pkSearchT);
  const q = e.target.value.trim();
  if(q.length < 4){ document.getElementById('addrRes').classList.add('hidden'); return; }
  pkSearchT = setTimeout(() => pkSearch(q), 700);
});
 
document.addEventListener('keydown', e => {
  if(e.target.id === 'link' && e.key === 'Enter'){
    e.preventDefault();
    document.getElementById('btnLink').click();
  }
});
 
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