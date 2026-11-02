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
            <div class="flex items-center gap-2 text-xs font-semibold text-merapiText-muted">
                <span>Titik Kumpul Utama:</span>
                <span class="inline-flex items-center gap-1 text-wukirsari-primary font-bold bg-white px-3 py-1 rounded-full border border-wukirsari-border">
                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                    Basecamp Jeep Wukirsari, Cangkringan
                </span>
            </div>
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
            <input type="hidden" name="arrival_type">
            <input type="hidden" name="pickup_address">
            <input type="hidden" name="pickup_lat">
            <input type="hidden" name="pickup_lng">
            <input type="hidden" name="pickup_manual">
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

                <!-- 4. OPSI KEDATANGAN & KONTAK PEMESAN -->
                <section class="bg-white rounded-3xl border border-wukirsari-border shadow-xs p-6 sm:p-7 space-y-6" data-sec="4">
                    <div class="flex items-center gap-3 pb-3 border-b border-wukirsari-border/70">
                        <span class="w-8 h-8 rounded-xl bg-wukirsari-primary text-white text-sm font-bold flex items-center justify-center font-display shadow-xs shrink-0">4</span>
                        <div>
                            <h2 class="text-base sm:text-lg font-bold text-merapiText-primary font-display">Opsi Kedatangan & Kontak</h2>
                            <p class="text-xs text-merapiText-secondary">Pilih cara tiba di basecamp dan lengkapi kontak untuk penerbitan tiket.</p>
                        </div>
                    </div>

                    <!-- Pilihan Kedatangan Tabs -->
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-merapiText-primary">Cara Kedatangan Anda</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 arrive">
                            <button type="button" class="ar sel p-4 rounded-2xl border text-left font-bold text-xs sm:text-sm transition-all flex items-center gap-3 cursor-pointer" data-m="self">
                                <span class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center text-base shrink-0">📍</span>
                                <div>
                                    <span class="block text-sm font-bold">Datang Sendiri ke Basecamp</span>
                                    <span class="block text-[11px] opacity-80 font-normal">Parkir luas & aman tersedia gratis</span>
                                </div>
                            </button>
                            <button type="button" class="ar p-4 rounded-2xl border text-left font-bold text-xs sm:text-sm transition-all flex items-center gap-3 cursor-pointer" data-m="shuttle">
                                <span class="w-9 h-9 rounded-xl bg-wukirsari-sage flex items-center justify-center text-base shrink-0">🚐</span>
                                <div>
                                    <span class="block text-sm font-bold">Layanan Antar-Jemput</span>
                                    <span class="block text-[11px] text-merapiText-secondary font-normal">Dijemput di Hotel / Penginapan</span>
                                </div>
                            </button>
                        </div>
                    </div>

                    <!-- Pickup Details Box -->
                    <div id="pickupBox" hidden class="p-5 rounded-2xl bg-wukirsari-cream/60 border border-wukirsari-border space-y-4">
                        <div class="space-y-1.5 relative">
                            <label for="addr" class="block text-xs font-bold text-merapiText-primary">Nama Hotel / Alamat Penjemputan *</label>
                            <input id="addr" class="w-full px-4 py-3 rounded-2xl border border-wukirsari-border bg-white text-merapiText-primary text-sm focus:outline-none focus:border-wukirsari-primary focus:ring-2 focus:ring-wukirsari-primary/15 transition-all" autocomplete="off" placeholder="Ketik nama hotel, mis. Artotel Yogyakarta">
                            <div class="results hidden absolute left-0 right-0 top-full mt-1 bg-white border border-wukirsari-border rounded-2xl shadow-xl z-20 max-h-60 overflow-y-auto" id="addrRes"></div>
                            <span class="block text-[11px] text-merapiText-muted">Ketik minimal 4 huruf, jarak rute jalan dari basecamp akan dihitung otomatis.</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                            <button type="button" id="btnGps" class="px-4 py-3 rounded-xl bg-white hover:bg-wukirsari-sage border border-wukirsari-border text-merapiText-primary text-xs font-bold transition-all flex items-center justify-center gap-2 cursor-pointer shadow-2xs">
                                <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span>Bagikan Lokasi Saya Sekarang</span>
                            </button>
                            <div class="flex gap-2">
                                <input id="link" class="flex-1 px-3 py-2 rounded-xl border border-wukirsari-border bg-white text-xs text-merapiText-primary focus:outline-none focus:border-wukirsari-primary" placeholder="Koordinat: -7.7956, 110.3695">
                                <button type="button" id="btnLink" class="px-4 py-2 rounded-xl bg-wukirsari-primary hover:bg-wukirsari-hover text-white text-xs font-bold cursor-pointer shrink-0">Hitung</button>
                            </div>
                        </div>

                        <!-- Distance Calculation Result -->
                        <div class="p-4 rounded-2xl bg-white border border-wukirsari-border flex items-center justify-between gap-4 transition-all" id="dist">
                            <div>
                                <div class="text-xl sm:text-2xl font-extrabold text-wukirsari-primary font-display" id="distKm">—</div>
                                <p class="text-xs text-merapiText-secondary mt-0.5" id="distTxt">Ketik nama hotel untuk menghitung jarak otomatis.</p>
                            </div>
                            <span class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-wukirsari-primary text-white shadow-2xs shrink-0 whitespace-nowrap" id="distTag">Belum dihitung</span>
                        </div>

                        <button type="button" id="btnManual" class="w-full text-center py-2 text-xs font-semibold text-merapiText-secondary hover:text-wukirsari-primary underline cursor-pointer">
                            Lokasi tidak terdeteksi? Biaya jemput dikonfirmasi admin via WhatsApp
                        </button>
                    </div>

                    <!-- Customer Contact Info -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
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
                        <div class="flex justify-between items-center text-amber-900 bg-amber-50 p-2.5 rounded-xl border border-amber-200/80" id="rowExtra" style="display:none">
                            <div class="text-xs">
                                <span class="font-bold block" id="lExtra">Biaya Tambahan Jemput</span>
                            </div>
                            <strong class="font-bold shrink-0" id="vExtra">Rp 0</strong>
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
.ar.sel {
    border-color: #1E3F20 !important;
    background-color: #1E3F20 !important;
    color: #ffffff !important;
}
.ar.sel span {
    color: #ffffff !important;
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
.results-item {
    display: block;
    width: 100%;
    text-align: left;
    padding: 10px 14px;
    font-size: 12px;
    border-bottom: 1px solid #E2EAE3;
    color: #19231A;
    transition: background 0.15s;
}
.results-item:hover {
    background-color: #F7F7F2;
}
.results-item:last-child {
    border-bottom: none;
}
</style>

<script type="application/json" id="booking-data">@json($bookingData)</script>

<script>
/* ====== PENGATURAN (diambil dari server) ====== */
const bookingDataEl = document.getElementById('booking-data');
const D              = bookingDataEl ? JSON.parse(bookingDataEl.textContent || '{}') : (window.BOOKING_DATA || {});
window.BOOKING_DATA  = D;
const BASECAMP       = D.basecamp;
const FREE_KM        = D.freeKm;
const EXTRA_PER_JEEP = D.feePerJeep;
const PER_JEEP       = 4;
const PACKAGES       = D.packages;
const SESSIONS       = D.slots;

const $ = s => document.querySelector(s);
const rp = n => 'Rp ' + Number(n).toLocaleString('id-ID');
const esc = s => String(s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
const state = {
  pkg: D.selectedId ?? (PACKAGES[0] ? PACKAGES[0].id : null),
  qty:1, pax:4, session:null, pickup:false, pay:'full',
  km:null, lat:null, lng:null, calc:false, manual:false, approx:false
};
const curPkg = () => PACKAGES.find(x => String(x.id) === String(state.pkg)) || PACKAGES[0];

/* ====== 1. PAKET ====== */
const pkgsEl = $('#pkgs');
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

/* ====== 4. OPSI KEDATANGAN ====== */
document.querySelectorAll('.ar').forEach(b => b.onclick = () => {
  state.pickup = b.dataset.m === 'shuttle';
  document.querySelectorAll('.ar').forEach(x => x.classList.toggle('sel', x === b));
  $('#pickupBox').hidden = !state.pickup;
  update(); setStep(4);
});

/* ====== HITUNG JARAK PENJEMPUTAN ====== */
let calcId = 0;
function haversine(a, b, c, d){
  const R = 6371, r = x => x * Math.PI / 180;
  const dLat = r(c - a), dLng = r(d - b);
  const h = Math.sin(dLat / 2) ** 2 + Math.cos(r(a)) * Math.cos(r(c)) * Math.sin(dLng / 2) ** 2;
  return 2 * R * Math.asin(Math.sqrt(h));
}

async function computeDistance(lat, lng){
  const id = ++calcId;
  state.lat = lat; state.lng = lng; state.manual = false;
  state.km = null; state.calc = true; update();
  let km, approx = false;
  try{
    const ctl = new AbortController();
    const to = setTimeout(() => ctl.abort(), 8000);
    const r = await fetch(`https://router.project-osrm.org/route/v1/driving/${BASECAMP.lng},${BASECAMP.lat};${lng},${lat}?overview=false`, { signal: ctl.signal });
    clearTimeout(to);
    const j = await r.json();
    if(j.code !== 'Ok') throw new Error('route');
    km = j.routes[0].distance / 1000;
  }catch(e){
    km = haversine(BASECAMP.lat, BASECAMP.lng, lat, lng) * 1.3;
    approx = true;
  }
  if(id !== calcId) return;
  state.km = Math.round(km * 10) / 10;
  state.approx = approx;
  state.calc = false;
  update();
}

function parseLocation(t){
  try{ t = decodeURIComponent(t.trim()); }catch(e){ t = t.trim(); }
  const m = t.match(/@(-?\d+\.\d+),\s*(-?\d+\.\d+)/)
         || t.match(/!3d(-?\d+\.\d+)!4d(-?\d+\.\d+)/)
         || t.match(/[?&](?:q|query|ll|destination)=(-?\d+\.\d+)[,\s+]+(-?\d+\.\d+)/)
         || t.match(/^(-?\d{1,2}\.\d+)[,\s]+(-?\d{2,3}\.\d+)$/);
  if(!m) return null;
  const lat = parseFloat(m[1]), lng = parseFloat(m[2]);
  if(lat < -11.5 || lat > 6.5 || lng < 94 || lng > 141.5) return null;
  return {lat, lng};
}

$('#btnLink').onclick = () => {
  const v = $('#link').value.trim();
  if(!v){ toast('Tempel link Google Maps atau koordinat dulu.'); return; }
  const pt = parseLocation(v);
  if(!pt){
    toast(/goo\.gl|maps\.app/i.test(v)
      ? 'Link pendek tidak bisa dibaca otomatis. Salin angka koordinat (mis. -7.7956, 110.3695) dari Google Maps.'
      : 'Format belum dikenali. Gunakan link lengkap Google Maps atau angka koordinat.');
    return;
  }
  computeDistance(pt.lat, pt.lng);
};

$('#link').addEventListener('keydown', e => {
  if(e.key === 'Enter'){ e.preventDefault(); $('#btnLink').click(); }
});

$('#btnGps').onclick = () => {
  if(!navigator.geolocation){
    toast('Browser tidak mendukung pendeteksian lokasi GPS.');
    return;
  }
  toast('Sedang mengambil koordinat GPS Anda…');
  navigator.geolocation.getCurrentPosition(
    p => computeDistance(p.coords.latitude, p.coords.longitude),
    err => {
      toast(err.code === 1 ? 'Izin akses lokasi GPS ditolak oleh browser Anda.'
        : err.code === 2 ? 'Sensor GPS tidak terdeteksi. Silakan ketik nama hotel.'
        : 'Waktu pencarian GPS habis. Silakan coba lagi.');
    },
    { enableHighAccuracy: true, timeout: 10000 }
  );
};

$('#btnManual').onclick = () => {
  ++calcId;
  state.manual = true; state.km = null; state.lat = null; state.lng = null; state.calc = false;
  update();
  toast('Biaya penjemputan akan dikonfirmasi langsung oleh admin melalui WhatsApp.');
};

/* ====== CARI NAMA HOTEL / TEMPAT ====== */
let searchT, searchId = 0;
$('#addr').addEventListener('input', () => {
  clearTimeout(searchT);
  const q = $('#addr').value.trim();
  if(q.length < 4){ $('#addrRes').classList.add('hidden'); return; }
  searchT = setTimeout(() => searchPlace(q), 700);
});

async function searchPlace(q){
  const box = $('#addrRes'), id = ++searchId;
  const msg = t => {
    box.innerHTML = '';
    const n = document.createElement('div');
    n.className = 'results-item text-merapiText-muted font-medium';
    n.textContent = t;
    box.appendChild(n);
    box.classList.remove('hidden');
  };
  msg('Mencari data lokasi…');

  let items = [];
  try{
    const r = await fetch('https://photon.komoot.io/api/?limit=5&lat=' + BASECAMP.lat + '&lon=' + BASECAMP.lng + '&q=' + encodeURIComponent(q));
    const j = await r.json();
    items = j.features.map(f => ({
      lat: f.geometry.coordinates[1],
      lng: f.geometry.coordinates[0],
      label: [f.properties.name, f.properties.street, f.properties.district, f.properties.city || f.properties.county, f.properties.state].filter(Boolean).join(', ')
    }));
  }catch(e){}

  if(!items.length){
    try{
      const r = await fetch('https://nominatim.openstreetmap.org/search?format=json&limit=5&countrycodes=id&viewbox=109.8,-7.3,111.0,-8.4&q=' + encodeURIComponent(q));
      const j = await r.json();
      items = j.map(x => ({ lat: +x.lat, lng: +x.lon, label: x.display_name }));
    }catch(e){}
  }
  if(id !== searchId) return;

  if(!items.length){
    msg('Lokasi tidak ditemukan. Coba tambahkan kata "Yogyakarta" atau bagikan koordinat lokasi.');
    return;
  }

  computeDistance(items[0].lat, items[0].lng);

  box.innerHTML = '';
  const head = document.createElement('div');
  head.className = 'results-item font-bold text-wukirsari-primary bg-wukirsari-sage/40';
  head.textContent = '✓ Dipakai: ' + items[0].label;
  box.appendChild(head);

  if(items.length > 1){
    const alt = document.createElement('div');
    alt.className = 'results-item text-[11px] text-merapiText-muted bg-slate-50 font-semibold';
    alt.textContent = 'Bukan ini? Pilih opsi alternatif lain:';
    box.appendChild(alt);
    items.slice(1).forEach(it => {
      const b = document.createElement('button');
      b.type = 'button';
      b.className = 'results-item cursor-pointer';
      b.textContent = it.label;
      b.onclick = () => {
        $('#addr').value = it.label;
        box.classList.add('hidden');
        computeDistance(it.lat, it.lng);
      };
      box.appendChild(b);
    });
  }
  box.classList.remove('hidden');
}

document.addEventListener('click', e => {
  if(!e.target.closest('#addr, #addrRes')) $('#addrRes').classList.add('hidden');
});

/* ====== BIAYA JEMPUT ====== */
function pickupFee(){
  if(!state.pickup || state.km === null) return 0;
  return state.km > FREE_KM ? EXTRA_PER_JEEP * state.qty : 0;
}

/* ====== SKEMA PEMBAYARAN ====== */
document.querySelectorAll('.po').forEach(b => b.onclick = () => {
  state.pay = b.dataset.p;
  document.querySelectorAll('.po').forEach(x => x.classList.toggle('sel', x === b));
  update();
});

/* ====== UPDATE RINGKASAN TAGIHAN ====== */
function update(){
  const p = curPkg();
  const fee = pickupFee();
  const total = p.price * state.qty + fee;
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

  const vp = $('#vPick');
  const rowExtra = $('#rowExtra');
  rowExtra.style.display = 'none';

  if(!state.pickup){
    vp.textContent = 'Datang Sendiri';
    vp.className = 'text-merapiText-secondary font-semibold';
  } else if(state.manual){
    vp.textContent = 'Dikonfirmasi Admin';
    vp.className = 'text-amber-700 font-bold';
  } else if(state.calc){
    vp.textContent = 'Menghitung…';
    vp.className = 'text-slate-500 font-semibold';
  } else if(state.km === null){
    vp.textContent = 'Tentukan Lokasi';
    vp.className = 'text-slate-500 font-semibold';
  } else if(fee === 0){
    vp.textContent = 'Gratis (' + state.km + ' km)';
    vp.className = 'text-emerald-700 font-bold';
  } else {
    vp.textContent = 'Jarak ' + state.km + ' km';
    vp.className = 'text-amber-800 font-bold';
    rowExtra.style.display = 'flex';
    $('#lExtra').innerHTML = 'Biaya Tambahan Jemput<small class="block text-[10px] opacity-80 font-normal">Gratis s/d ' + FREE_KM + ' km, selebihnya ' + rp(EXTRA_PER_JEEP) + ' × ' + state.qty + ' jeep</small>';
    $('#vExtra').textContent = '+ ' + rp(fee);
  }

  const dist = $('#dist');
  if(state.manual){
    $('#distKm').textContent = '—';
    $('#distTxt').textContent = 'Biaya penjemputan akan dikonfirmasi admin lewat WhatsApp.';
    $('#distTag').textContent = 'Via Admin';
    dist.className = 'p-4 rounded-2xl bg-amber-50 border border-amber-200 flex items-center justify-between gap-4 transition-all';
    $('#distTag').className = 'px-3.5 py-1.5 rounded-full text-xs font-bold bg-amber-600 text-white shadow-2xs shrink-0 whitespace-nowrap';
  } else if(state.calc){
    $('#distKm').textContent = '…';
    $('#distTxt').textContent = 'Menghitung jarak rute jalan dari basecamp…';
    $('#distTag').textContent = 'Menghitung';
    dist.className = 'p-4 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-between gap-4 transition-all';
    $('#distTag').className = 'px-3.5 py-1.5 rounded-full text-xs font-bold bg-slate-500 text-white shadow-2xs shrink-0 whitespace-nowrap';
  } else if(state.km === null){
    $('#distKm').textContent = '—';
    $('#distTxt').textContent = 'Ketik nama hotel untuk menghitung jarak otomatis.';
    $('#distTag').textContent = 'Belum Dihitung';
    dist.className = 'p-4 rounded-2xl bg-white border border-wukirsari-border flex items-center justify-between gap-4 transition-all';
    $('#distTag').className = 'px-3.5 py-1.5 rounded-full text-xs font-bold bg-wukirsari-primary text-white shadow-2xs shrink-0 whitespace-nowrap';
  } else if(state.km <= FREE_KM){
    $('#distKm').textContent = state.km + ' km';
    $('#distTxt').textContent = 'Jarak ' + (state.approx ? 'perkiraan' : 'rute jalan') + ' dari basecamp. Masih dalam jangkauan jemput gratis!';
    $('#distTag').textContent = 'GRATIS';
    dist.className = 'p-4 rounded-2xl bg-emerald-50 border border-emerald-200 flex items-center justify-between gap-4 transition-all';
    $('#distTag').className = 'px-3.5 py-1.5 rounded-full text-xs font-bold bg-emerald-700 text-white shadow-2xs shrink-0 whitespace-nowrap';
  } else {
    $('#distKm').textContent = state.km + ' km';
    $('#distTxt').textContent = 'Lebih dari batas gratis ' + FREE_KM + ' km: ' + rp(EXTRA_PER_JEEP) + ' × ' + state.qty + ' jeep = ' + rp(fee) + (state.approx ? ' (perkiraan)' : '');
    $('#distTag').textContent = '+ ' + rp(fee);
    dist.className = 'p-4 rounded-2xl bg-amber-50 border border-amber-200 flex items-center justify-between gap-4 transition-all';
    $('#distTag').className = 'px-3.5 py-1.5 rounded-full text-xs font-bold bg-amber-600 text-white shadow-2xs shrink-0 whitespace-nowrap';
  }

  $('#vTotal').textContent = rp(total);
  $('#mTotal').textContent = rp(total);
  $('#vDue').textContent = rp(due);
  $('#dueLbl').textContent = state.pay === 'dp' ? 'Wajib Dibayar Sekarang (DP 30%)' : 'Wajib Dibayar Sekarang (Lunas)';
  $('#dueSub').textContent = state.pay === 'dp' ? 'Sisa ' + rp(total - due) : 'Lunas (100%)';
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
  if(state.pickup){
    if(addr.value.trim().length < 3){
      addr.classList.add('err');
      toast('Isi nama hotel / alamat penjemputan.');
      return addr.focus();
    }
    if(state.calc){
      toast('Jarak penjemputan masih dalam proses perhitungan, tunggu sebentar.');
      return;
    }
    if(state.km === null && !state.manual){
      toast('Jarak belum terhitung. Pilih dari daftar hotel atau klik bagikan lokasi.');
      return addr.focus();
    }
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
  set('arrival_type', state.pickup ? 'pickup' : 'self_drive');
  set('pickup_address', state.pickup ? addr.value.trim() : '');
  set('pickup_lat', state.pickup && !state.manual && state.lat !== null ? state.lat : '');
  set('pickup_lng', state.pickup && !state.manual && state.lng !== null ? state.lng : '');
  set('pickup_manual', state.pickup && state.manual ? 1 : 0);
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
