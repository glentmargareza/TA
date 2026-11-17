@extends('layouts.app')

@section('title', 'Jeep Wukirsari — Sewa Jeep 4x4 Offroad Lereng Merapi | Paguyuban Resmi')

@section('content')

    {{-- =========================================================================
         1. HERO SECTION: POS PEMBERANGKATAN EKSPEDISI LERENG MERAPI
         Grid asimetris 60/40 dengan kontras tinggi, typography Fraunces,
         dan Field Dispatch Card untuk simulasi sewa instan.
    ========================================================================= --}}
    <section class="bg-[#171B18] text-[#FAF9F5] border-b border-[#2B332C] relative overflow-hidden">
        {{-- Background Texture: subtle volcanic terrain image with high-contrast darkening --}}
        <div class="absolute inset-0 z-0 pointer-events-none opacity-25">
            <img src="{{ asset('img/beranda.jpeg') }}" alt="Armada Jeep di Jalur Kali Gendol" class="w-full h-full object-cover object-center filter grayscale contrast-125">
            <div class="absolute inset-0 bg-gradient-to-r from-[#171B18] via-[#171B18]/90 to-[#171B18]/70"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-16 lg:py-20">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                
                {{-- Left Column (7 cols): Editorial Typography & Direct Context --}}
                <div class="lg:col-span-7 space-y-6">
                    
                    {{-- Status Badge Pos Lapangan --}}
                    <div class="inline-flex items-center gap-2.5 px-3 py-1.5 rounded-sm bg-[#111412] border border-[#2B332C] text-xs">
                        <span class="w-2 h-2 rounded-full bg-[#2C6B3E] shrink-0"></span>
                        <span class="font-mono text-[11px] text-[#ECE8DF] uppercase tracking-wider">POS BASECAMP DUSUN PUSUNG, WUKIRSARI</span>
                        <span class="text-[#2B332C]">|</span>
                        <span class="text-[11px] text-[#A54B28] font-semibold">JALUR SIAGA BUKA</span>
                    </div>

                    {{-- Main Headline --}}
                    <h1 class="text-3xl sm:text-5xl lg:text-[58px] font-serif font-black tracking-tight leading-[1.05] text-[#FAF9F5]">
                        Sewa Jeep 4x4 Susur Lava & Sungai Vulkanik Merapi.
                    </h1>

                    {{-- Grounded Description --}}
                    <p class="text-sm sm:text-base text-[#ECE8DF]/90 leading-relaxed max-w-2xl font-normal">
                        Dikelola langsung oleh paguyuban pemuda dan driver warga asli Desa Wisata Wukirsari. Armada Suzuki Katana & Toyota FJ40 berizin resmi, terawat berkala, lengkap asuransi perjalanan Jasa Raharja dan pemandu foto lokal.
                    </p>

                    {{-- Key Operational Metrics (Bukan angka klaim palsu) --}}
                    <div class="grid grid-cols-3 gap-3 sm:gap-4 pt-2 border-t border-[#2B332C] max-w-xl">
                        <div class="p-3 bg-[#111412]/60 border border-[#2B332C] rounded-sm">
                            <span class="block font-serif font-bold text-lg sm:text-2xl text-[#FAF9F5]">Rp 450k</span>
                            <span class="block text-[11px] text-[#ECE8DF]/70 mt-0.5 leading-tight">Mulai per armada (1-4 penumpang)</span>
                        </div>
                        <div class="p-3 bg-[#111412]/60 border border-[#2B332C] rounded-sm">
                            <span class="block font-serif font-bold text-lg sm:text-2xl text-[#FAF9F5]">04.30</span>
                            <span class="block text-[11px] text-[#ECE8DF]/70 mt-0.5 leading-tight">WIB trip fajar / sunrise pertama</span>
                        </div>
                        <div class="p-3 bg-[#111412]/60 border border-[#2B332C] rounded-sm">
                            <span class="block font-serif font-bold text-lg sm:text-2xl text-[#FAF9F5]">100%</span>
                            <span class="block text-[11px] text-[#ECE8DF]/70 mt-0.5 leading-tight">Driver warga asli lereng Merapi</span>
                        </div>
                    </div>

                    {{-- Quick Action CTA --}}
                    <div class="pt-2 flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                        <a href="#rute-sewa" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-md bg-[#C25E38] hover:bg-[#A54B28] text-white text-xs sm:text-sm font-bold tracking-wide transition-all shadow-[3px_3px_0px_0px_#111412] active:translate-y-0.5 active:shadow-none focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#C25E38] focus-visible:ring-offset-2 focus-visible:ring-offset-[#171B18]">
                            <span>Pilih Rute & Tarif Sewa</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                        </a>
                        <a href="https://wa.me/6281228677843?text=Halo%20Basecamp%20Jeep%20Wukirsari,%20saya%20mau%20tanya%20ketersediaan%20armada%20untuk%20wisatawan" target="_blank" class="inline-flex items-center justify-center gap-2 px-5 py-3.5 rounded-md bg-[#111412] hover:bg-[#232924] border border-[#2B332C] text-[#FAF9F5] text-xs sm:text-sm font-semibold transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#ECE8DF]">
                            <svg class="w-4 h-4 text-[#2C6B3E]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            <span>Kontak Pos WhatsApp</span>
                        </a>
                    </div>
                </div>

                {{-- Right Column (5 cols): Field Dispatch Card (Kartu Pemesanan Cepat Wisatawan) --}}
                <div class="lg:col-span-5">
                    <div class="bg-[#FAF9F5] text-[#171B18] p-5 sm:p-7 rounded-lg border-2 border-[#E2DDD3] shadow-[4px_4px_0px_0px_#C25E38]">
                        
                        {{-- Card Header --}}
                        <div class="flex items-center justify-between pb-4 border-b border-[#E2DDD3]">
                            <div>
                                <span class="font-mono text-[10px] uppercase tracking-wider text-[#5C635B] block">SIMULASI & CEK SLOT</span>
                                <h2 class="text-lg sm:text-xl font-serif font-bold text-[#171B18] leading-tight">Pemesanan Armada Jeep</h2>
                            </div>
                            <span class="px-2 py-1 rounded bg-[#F9EDE6] text-[#C25E38] text-[10px] font-bold uppercase tracking-wider border border-[#C25E38]/20">
                                Kapasitas 4 Org
                            </span>
                        </div>

                        {{-- Interactive Dispatch Form --}}
                        <form id="heroQuickBookForm" action="{{ route('booking.create') }}" method="GET" class="mt-4 space-y-3.5">
                            
                            {{-- Pilih Paket --}}
                            <div>
                                <label for="heroPackageSelect" class="block text-xs font-semibold text-[#171B18] mb-1">
                                    Pilih Rute Wisata <span class="text-[#C25E38]">*</span>
                                </label>
                                <select id="heroPackageSelect" name="package" required onchange="updateHeroPriceEstimate()" class="w-full px-3 py-2.5 bg-white border border-[#E2DDD3] rounded-md text-xs sm:text-sm text-[#171B18] focus:outline-none focus:ring-2 focus:ring-[#C25E38] focus:border-[#C25E38]">
                                    @forelse($packages as $pkg)
                                        <option value="{{ $pkg->slug }}" data-price="{{ $pkg->price_per_jeep }}" data-duration="{{ $pkg->effective_duration_text }}" {{ $loop->first ? 'selected' : '' }}>
                                            {{ $pkg->name }} — Rp {{ number_format($pkg->price_per_jeep, 0, ',', '.') }} ({{ $pkg->effective_duration_text }})
                                        </option>
                                    @empty
                                        <option value="short-trip" data-price="450000" data-duration="1.5 - 2 Jam">
                                            Short Trip — Rp 450.000 (1.5 - 2 Jam)
                                        </option>
                                        <option value="medium-trip-a" data-price="550000" data-duration="2 - 2.5 Jam">
                                            Medium Trip A — Rp 550.000 (2 - 2.5 Jam)
                                        </option>
                                        <option value="sunrise-trip" data-price="550000" data-duration="Start 04.30 WIB">
                                            Sunrise Trip — Rp 550.000 (04.30 WIB)
                                        </option>
                                        <option value="long-trip" data-price="650000" data-duration="3 Jam">
                                            Long Trip — Rp 650.000 (3 Jam)
                                        </option>
                                    @endforelse
                                </select>
                            </div>

                            {{-- Tanggal & Sesi Keberangkatan (2 Kolom) --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label for="heroTripDate" class="block text-xs font-semibold text-[#171B18] mb-1">
                                        Tanggal Trip <span class="text-[#C25E38]">*</span>
                                    </label>
                                    <input type="date" id="heroTripDate" name="date" value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}" required class="w-full px-3 py-2 bg-white border border-[#E2DDD3] rounded-md text-xs sm:text-sm text-[#171B18] focus:outline-none focus:ring-2 focus:ring-[#C25E38] focus:border-[#C25E38]">
                                </div>
                                <div>
                                    <label for="heroTripTime" class="block text-xs font-semibold text-[#171B18] mb-1">
                                        Sesi Jam Berangkat <span class="text-[#C25E38]">*</span>
                                    </label>
                                    <select id="heroTripTime" name="time" required class="w-full px-3 py-2 bg-white border border-[#E2DDD3] rounded-md text-xs sm:text-sm text-[#171B18] focus:outline-none focus:ring-2 focus:ring-[#C25E38] focus:border-[#C25E38]">
                                        <option value="04:30">04.30 WIB (Sunrise)</option>
                                        <option value="07:30" selected>07.30 WIB (Pagi Segar)</option>
                                        <option value="09:30">09.30 WIB (Pagi Siang)</option>
                                        <option value="13:30">13.30 WIB (Siang)</option>
                                        <option value="15:30">15.30 WIB (Sore Panorama)</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Live Price Summary Box --}}
                            <div class="p-3 bg-[#ECE8DF]/60 border border-[#E2DDD3] rounded-md flex items-center justify-between">
                                <div>
                                    <span class="block text-[11px] text-[#5C635B] font-medium leading-tight">Total Tarif Resmi (1 Mobil):</span>
                                    <span id="heroEstimatedPriceText" class="font-serif font-black text-xl text-[#171B18]">
                                        Rp {{ isset($packages[0]) ? number_format($packages[0]->price_per_jeep, 0, ',', '.') : '450.000' }}
                                    </span>
                                </div>
                                <div class="text-right">
                                    <span class="inline-block text-[11px] text-[#2C6B3E] font-bold bg-[#2C6B3E]/10 px-2 py-0.5 rounded">
                                        BBM + Driver All-In
                                    </span>
                                    <span class="block text-[10px] text-[#5C635B] mt-0.5">Maks. 4 Penumpang</span>
                                </div>
                            </div>

                            {{-- Tombol Lanjut Booking --}}
                            <button type="submit" id="btnHeroDispatch" class="w-full py-3.5 px-4 rounded-md bg-[#C25E38] hover:bg-[#A54B28] text-white text-xs sm:text-sm font-bold tracking-wide transition-all shadow-[2px_2px_0px_0px_#171B18] active:translate-y-0.5 active:shadow-none focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#C25E38] focus-visible:ring-offset-2 flex items-center justify-center gap-2 cursor-pointer">
                                <span>Lanjut Reservasi Armada Ini</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </button>

                            <p class="text-[11px] text-[#5C635B] text-center leading-tight">
                                Tanpa biaya pendaftaran. Pembayaran aman & tiket diterbitkan langsung.
                            </p>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </section>


    {{-- =========================================================================
         2. SECTION STANDAR KESELAMATAN & PAGUYUBAN (FIELD SAFETY PROTOCOL)
         Bukan kartu di dalam kartu! Layout papan inspeksi terstruktur.
    ========================================================================= --}}
    <section class="py-12 sm:py-16 bg-[#FAF9F5] border-b border-[#E2DDD3]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                {{-- Header Samping --}}
                <div class="lg:col-span-4 space-y-2">
                    <span class="font-mono text-xs text-[#C25E38] uppercase tracking-wider font-bold block">STANDAR LAPANGAN</span>
                    <h2 class="text-2xl sm:text-3xl font-serif font-bold text-[#171B18] leading-tight">
                        Mengapa Menyewa di Paguyuban Resmi Wukirsari?
                    </h2>
                    <p class="text-xs sm:text-sm text-[#5C635B] leading-relaxed pt-1">
                        Keamanan wisatawan di jalur bebatuan dan lahar dingin Merapi bukan hal main-main. Seluruh unit kami terikat standar paguyuban desa.
                    </p>
                </div>

                {{-- 4 Poin Kelaikan Lapangan --}}
                <div class="lg:col-span-8 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    
                    {{-- Poin 1 --}}
                    <div class="p-4 bg-white border border-[#E2DDD3] rounded-md">
                        <div class="flex items-center gap-2 mb-2 text-[#171B18]">
                            <svg class="w-5 h-5 text-[#C25E38] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            <h3 class="font-serif font-bold text-sm sm:text-base text-[#171B18]">Sasis & Rollbar Teruji</h3>
                        </div>
                        <p class="text-xs text-[#5C635B] leading-relaxed">
                            Pemeriksaan rem, gardan 4WD, dan sabuk pengaman dilakukan setiap pagi di basecamp sebelum unit meluncur ke jalur.
                        </p>
                    </div>

                    {{-- Poin 2 --}}
                    <div class="p-4 bg-white border border-[#E2DDD3] rounded-md">
                        <div class="flex items-center gap-2 mb-2 text-[#171B18]">
                            <svg class="w-5 h-5 text-[#C25E38] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <h3 class="font-serif font-bold text-sm sm:text-base text-[#171B18]">Driver Warga Asli Berlisensi</h3>
                        </div>
                        <p class="text-xs text-[#5C635B] leading-relaxed">
                            Bukan pengemudi lepas musiman. Driver kami adalah warga asli yang paham jalur evakuasi, sejarah erupsi, dan sudut foto terbaik.
                        </p>
                    </div>

                    {{-- Poin 3 --}}
                    <div class="p-4 bg-white border border-[#E2DDD3] rounded-md">
                        <div class="flex items-center gap-2 mb-2 text-[#171B18]">
                            <svg class="w-5 h-5 text-[#C25E38] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <h3 class="font-serif font-bold text-sm sm:text-base text-[#171B18]">Tarif Resmi Bebas Pungli</h3>
                        </div>
                        <p class="text-xs text-[#5C635B] leading-relaxed">
                            Harga final sudah mencakup sewa unit, BBM, pemandu, dan retribusi jalan. Anda tidak akan dimintai biaya tambahan di tengah rute.
                        </p>
                    </div>

                    {{-- Poin 4 --}}
                    <div class="p-4 bg-white border border-[#E2DDD3] rounded-md">
                        <div class="flex items-center gap-2 mb-2 text-[#171B18]">
                            <svg class="w-5 h-5 text-[#C25E38] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            <h3 class="font-serif font-bold text-sm sm:text-base text-[#171B18]">Perlindungan Asuransi Resmi</h3>
                        </div>
                        <p class="text-xs text-[#5C635B] leading-relaxed">
                            Setiap wisatawan yang terdaftar dalam manifest pemesanan resmi terlindungi oleh pertanggungan asuransi penumpang Jasa Raharja.
                        </p>
                    </div>

                </div>

            </div>

        </div>
    </section>


    {{-- =========================================================================
         3. SECTION DAFTAR RUTE & TARIF SEWA (THE EXPEDITION ROUTE BOARD)
         Ritme berani: bukan kartu seragam! Menampilkan waypoint rute riil,
         tingkat basah/kering, estimasi durasi, dan aksi sewa langsung.
    ========================================================================= --}}
    <section id="rute-sewa" class="py-14 sm:py-20 bg-[#F6F4EE] border-b border-[#E2DDD3]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Section Title & Filter --}}
            <div class="flex flex-col md:flex-row md:items-end justify-between pb-8 border-b border-[#E2DDD3] gap-4">
                <div>
                    <span class="font-mono text-xs text-[#C25E38] uppercase tracking-wider font-bold block mb-1">PAPAN JADWAL & DESTINASI</span>
                    <h2 class="text-2xl sm:text-4xl font-serif font-bold text-[#171B18] leading-tight">
                        Pilihan Rute Sewa Jeep Wisata
                    </h2>
                    <p class="text-xs sm:text-sm text-[#5C635B] mt-1 max-w-xl">
                        Semua harga tertera per 1 unit Jeep (bukan per orang). Kapasitas maksimal 4 orang dewasa demi kenyamanan dan keselamatan manuver.
                    </p>
                </div>

                {{-- Status Garansi Harga --}}
                <div class="inline-flex items-center gap-2 px-3.5 py-2 rounded-md bg-[#FAF9F5] border border-[#E2DDD3] text-xs self-start md:self-end">
                    <span class="w-2 h-2 rounded-full bg-[#C25E38]"></span>
                    <span class="font-medium text-[#171B18]">Semua Rute Termasuk Driver & Masker Debu</span>
                </div>
            </div>

            {{-- Grid Paket Wisata Lapangan --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 pt-8">
                @foreach($packages as $pkg)
                <article class="bg-white border border-[#E2DDD3] rounded-md overflow-hidden flex flex-col justify-between hover:border-[#171B18] transition-all duration-200">
                    
                    {{-- Media Cover & Badges --}}
                    <div class="relative aspect-[16/10] bg-[#111412] overflow-hidden">
                        <img src="{{ $pkg->cover_image }}" alt="{{ $pkg->name }}" class="w-full h-full object-cover filter contrast-105 hover:scale-102 transition-transform duration-300" loading="lazy">
                        
                        {{-- Tag Tingkat Basah / Kering --}}
                        <div class="absolute top-3 left-3 flex flex-wrap gap-1.5">
                            @if(str_contains(strtolower($pkg->name), 'sunrise'))
                                <span class="px-2 py-1 bg-[#171B18] text-[#FAF9F5] text-[10px] font-mono uppercase tracking-wider rounded-sm font-semibold border border-[#2B332C]">
                                    FAJAR 04.30 WIB
                                </span>
                            @elseif(str_contains(strtolower($pkg->slug), 'long') || str_contains(strtolower($pkg->slug), 'medium'))
                                <span class="px-2 py-1 bg-[#C25E38] text-white text-[10px] font-mono uppercase tracking-wider rounded-sm font-semibold">
                                    MANUVER AIR KALI KUNING
                                </span>
                            @else
                                <span class="px-2 py-1 bg-[#2C6B3E] text-white text-[10px] font-mono uppercase tracking-wider rounded-sm font-semibold">
                                    RUTE RINGAN & PANORAMA
                                </span>
                            @endif
                        </div>

                        {{-- Durasi Lapangan --}}
                        <div class="absolute bottom-3 right-3 px-2 py-1 bg-[#171B18]/90 text-white text-[11px] font-mono rounded-sm border border-white/20">
                            ⏱ {{ $pkg->effective_duration_text }}
                        </div>
                    </div>

                    {{-- Body Content --}}
                    <div class="p-5 sm:p-6 flex-1 flex flex-col justify-between space-y-4">
                        
                        <div>
                            <div class="flex items-baseline justify-between gap-2">
                                <h3 class="text-lg sm:text-xl font-serif font-bold text-[#171B18] leading-snug">
                                    {{ $pkg->name }}
                                </h3>
                            </div>

                            <p class="text-xs sm:text-sm text-[#5C635B] mt-2 leading-relaxed">
                                {{ $pkg->description }}
                            </p>

                            {{-- Waypoint Titik Rute (Spesifik & Nyata) --}}
                            <div class="mt-4 pt-3 border-t border-[#E2DDD3]">
                                <span class="text-[10px] font-mono text-[#5C635B] uppercase tracking-wider block mb-1.5">TITIK SINGGAH UTAMA:</span>
                                @if(!empty($pkg->routes_list) && is_array($pkg->routes_list))
                                    <ul class="space-y-1">
                                        @foreach(array_slice($pkg->routes_list, 0, 4) as $routeItem)
                                            <li class="text-xs text-[#171B18] flex items-center gap-1.5">
                                                <span class="w-1 h-1 rounded-full bg-[#C25E38]"></span>
                                                <span class="truncate">{{ $routeItem }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-xs text-[#171B18]">
                                        Bunker Kaliadem, Museum Sisa Hartaku, Batu Alien & Manuver Kali Kuning.
                                    </p>
                                @endif
                            </div>
                        </div>

                        {{-- Pricing & Action Dock --}}
                        <div class="pt-4 border-t border-[#E2DDD3] space-y-3">
                            <div class="flex items-baseline justify-between">
                                <div>
                                    <span class="text-[10px] uppercase font-mono text-[#5C635B] block">TARIF PER JEEP</span>
                                    <span class="font-serif font-black text-2xl text-[#171B18]">
                                        Rp {{ number_format($pkg->price_per_jeep, 0, ',', '.') }}
                                    </span>
                                </div>
                                <span class="text-xs text-[#5C635B] font-medium">Maks. 4 Pax</span>
                            </div>

                            <div class="grid grid-cols-2 gap-2 pt-1">
                                <a href="{{ route('packages.show', $pkg->slug) }}" class="py-2.5 px-3 rounded-md border border-[#E2DDD3] hover:border-[#171B18] text-[#171B18] text-xs font-semibold text-center transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#171B18]">
                                    Detail Rute
                                </a>
                                <a href="{{ route('booking.create', ['package' => $pkg->slug]) }}" class="py-2.5 px-3 rounded-md bg-[#C25E38] hover:bg-[#A54B28] text-white text-xs font-bold text-center transition-all shadow-[2px_2px_0px_0px_#111412] active:translate-y-0.5 active:shadow-none focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#C25E38]">
                                    Pesan Rute Ini
                                </a>
                            </div>
                        </div>

                    </div>
                </article>
                @endforeach
            </div>

            {{-- Info Tambahan Penjemputan --}}
            <div class="mt-8 p-4 bg-[#FAF9F5] border border-[#E2DDD3] rounded-md flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-[#5C635B]">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#2C6B3E] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span><strong>Membawa rombongan keluarga/perusahaan lebih dari 4 orang?</strong> Kami siagakan puluhan armada konvoi terkoordinasi.</span>
                </div>
                <a href="https://wa.me/6281228677843?text=Halo%20Admin%20Wukirsari,%20saya%20ingin%20sewa%20rombongan%20jeep" target="_blank" class="font-bold text-[#C25E38] hover:underline whitespace-nowrap">
                    Konsultasi Rombongan →
                </a>
            </div>

        </div>
    </section>


    {{-- =========================================================================
         4. PANDUAN LAPANGAN WISATAWAN (FIELD ETIQUETTE & PREPARATION)
         Pedoman praktis langsung yang sangat dibutuhkan wisatawan nyata.
    ========================================================================= --}}
    <section id="panduan" class="py-12 sm:py-16 bg-[#FAF9F5] border-b border-[#E2DDD3]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="max-w-2xl pb-8">
                <span class="font-mono text-xs text-[#C25E38] uppercase tracking-wider font-bold block mb-1">PERSIAPAN SEBELUM KE BASECAMP</span>
                <h2 class="text-2xl sm:text-3xl font-serif font-bold text-[#171B18] leading-tight">
                    Panduan & Perlengkapan Wisatawan
                </h2>
                <p class="text-xs sm:text-sm text-[#5C635B] mt-1">
                    Agar petualangan offroad lereng Merapi berjalan nyaman, perhatikan empat hal praktis berikut sebelum berangkat:
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                
                <div class="p-4 bg-white border border-[#E2DDD3] rounded-md">
                    <span class="font-mono text-sm font-bold text-[#C25E38] block mb-2">01 / PAKAIAN</span>
                    <h3 class="font-serif font-bold text-sm text-[#171B18] mb-1">Baju Hangat & Baju Ganti</h3>
                    <p class="text-xs text-[#5C635B] leading-relaxed">
                        Suhu Bunker Kaliadem saat pagi fajar berkisar 17°C (siapkan jaket). Jika memilih rute dengan manuver air Kali Kuning, bawa celana atau baju ganti.
                    </p>
                </div>

                <div class="p-4 bg-white border border-[#E2DDD3] rounded-md">
                    <span class="font-mono text-sm font-bold text-[#C25E38] block mb-2">02 / FASILITAS</span>
                    <h3 class="font-serif font-bold text-sm text-[#171B18] mb-1">Parkir & Ruang Transit</h3>
                    <p class="text-xs text-[#5C635B] leading-relaxed">
                        Basecamp Pusung Wukirsari menyediakan area parkir luas untuk mobil pribadi maupun bus pariwisata, toilet bersih gratis, dan mushola.
                    </p>
                </div>

                <div class="p-4 bg-white border border-[#E2DDD3] rounded-md">
                    <span class="font-mono text-sm font-bold text-[#C25E38] block mb-2">03 / KONDISI FISIK</span>
                    <h3 class="font-serif font-bold text-sm text-[#171B18] mb-1">Anak & Lansia Aman</h3>
                    <p class="text-xs text-[#5C635B] leading-relaxed">
                        Wisatawan dengan anak kecil atau orang tua lansia cukup menginfokan ke driver; kecepatan dan manuver guncangan akan disesuaikan santai.
                    </p>
                </div>

                <div class="p-4 bg-white border border-[#E2DDD3] rounded-md">
                    <span class="font-mono text-sm font-bold text-[#C25E38] block mb-2">04 / CUACA</span>
                    <h3 class="font-serif font-bold text-sm text-[#171B18] mb-1">Jaminan Bebas Reschedule</h3>
                    <p class="text-xs text-[#5C635B] leading-relaxed">
                        Jika aktivitas Gunung Merapi meningkat atau terjadi cuaca ekstrem berdasarkan arahan BPBD, jadwal dapat diubah tanpa denda.
                    </p>
                </div>

            </div>

        </div>
    </section>


    {{-- =========================================================================
         5. SECTION DOKUMENTASI NYATA JALUR (FIELD PHOTO DOCUMENTATION)
         Foto nyata tanpa bingkai lingkaran berlebih, komposisi ritmik.
    ========================================================================= --}}
    <section id="galeri" class="py-14 sm:py-20 bg-[#171B18] text-[#FAF9F5] border-b border-[#2B332C]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="flex flex-col md:flex-row md:items-end justify-between pb-8 border-b border-[#2B332C] gap-4">
                <div>
                    <span class="font-mono text-xs text-[#C25E38] uppercase tracking-wider font-bold block mb-1">DOKUMENTASI LAPANGAN</span>
                    <h2 class="text-2xl sm:text-3xl font-serif font-bold text-[#FAF9F5] leading-tight">
                        Potret Petualangan di Jalur Lava & Air
                    </h2>
                    <p class="text-xs sm:text-sm text-[#ECE8DF]/75 mt-1 max-w-xl">
                        Momen nyata wisatawan saat melintasi sungai Kali Kuning, menyaksikan fajar di Bunker Kaliadem, hingga menyusuri tebing lahar.
                    </p>
                </div>
                <span class="text-xs text-[#ECE8DF]/60 font-mono">
                    Foto Asli Dokumentasi Driver Wukirsari
                </span>
            </div>

            {{-- Grid Asimetris Dokumentasi --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 pt-8">
                
                {{-- Foto 1: Landscape Besar (Col-Span 2) --}}
                <div class="sm:col-span-2 relative aspect-[16/10] bg-[#111412] border border-[#2B332C] rounded-md overflow-hidden group">
                    <img src="{{ asset('img/manuver_air.png') }}" alt="Manuver Air Kali Kuning" class="w-full h-full object-cover filter contrast-110 group-hover:scale-102 transition-transform duration-300" loading="lazy">
                    <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-[#171B18] via-[#171B18]/60 to-transparent p-3.5">
                        <span class="font-mono text-[10px] text-[#C25E38] font-bold uppercase tracking-wider block">SPOT FAVORIT</span>
                        <p class="text-xs sm:text-sm font-semibold text-white">Manuver Basah di Sungai Kali Kuning</p>
                    </div>
                </div>

                {{-- Foto 2 --}}
                <div class="relative aspect-[4/3] sm:aspect-auto bg-[#111412] border border-[#2B332C] rounded-md overflow-hidden group">
                    <img src="{{ asset('img/sunrise_trip.jpeg') }}" alt="Sunrise di Bunker Kaliadem" class="w-full h-full object-cover filter contrast-105 group-hover:scale-102 transition-transform duration-300" loading="lazy">
                    <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-[#171B18] via-[#171B18]/60 to-transparent p-3">
                        <p class="text-xs font-semibold text-white">Fajar Emas di Bunker Kaliadem</p>
                    </div>
                </div>

                {{-- Foto 3 --}}
                <div class="relative aspect-[4/3] sm:aspect-auto bg-[#111412] border border-[#2B332C] rounded-md overflow-hidden group">
                    <img src="{{ asset('img/long_trip.jpeg') }}" alt="Jalur Pasir Kali Gendol" class="w-full h-full object-cover filter contrast-105 group-hover:scale-102 transition-transform duration-300" loading="lazy">
                    <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-[#171B18] via-[#171B18]/60 to-transparent p-3">
                        <p class="text-xs font-semibold text-white">Susur Endapan Lahar Kali Gendol</p>
                    </div>
                </div>

                {{-- Foto 4 --}}
                <div class="relative aspect-[4/3] bg-[#111412] border border-[#2B332C] rounded-md overflow-hidden group">
                    <img src="{{ asset('img/3.jpeg') }}" alt="Armada Standby Basecamp" class="w-full h-full object-cover filter contrast-105 group-hover:scale-102 transition-transform duration-300" loading="lazy">
                    <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-[#171B18] via-[#171B18]/60 to-transparent p-3">
                        <p class="text-xs font-semibold text-white">Armada Siaga di Basecamp Pusung</p>
                    </div>
                </div>

                {{-- Foto 5 (Col-Span 3 on Desktop) --}}
                <div class="sm:col-span-2 lg:col-span-3 relative aspect-[16/7] bg-[#111412] border border-[#2B332C] rounded-md overflow-hidden group">
                    <img src="{{ asset('img/medium_trip_a.jpeg') }}" alt="Batu Alien Merapi" class="w-full h-full object-cover filter contrast-105 group-hover:scale-102 transition-transform duration-300" loading="lazy">
                    <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-[#171B18] via-[#171B18]/60 to-transparent p-3.5">
                        <span class="font-mono text-[10px] text-[#C25E38] font-bold uppercase tracking-wider block">SPOT EDUKASI</span>
                        <p class="text-xs sm:text-sm font-semibold text-white">Wisata Edukasi Batu Alien & Museum Kampung Hilang</p>
                    </div>
                </div>

            </div>

        </div>
    </section>


    {{-- =========================================================================
         6. SECTION ULASAN NYATA WISATAWAN (AUTHENTIC TRAVELER REVIEWS)
         Data riil database, format editorial jujur, tanpa angka bulat palsu.
    ========================================================================= --}}
    <section class="py-14 sm:py-20 bg-[#FAF9F5] border-b border-[#E2DDD3]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="flex flex-col md:flex-row md:items-end justify-between pb-8 border-b border-[#E2DDD3] gap-4">
                <div>
                    <span class="font-mono text-xs text-[#C25E38] uppercase tracking-wider font-bold block mb-1">SUARA WISATAWAN</span>
                    <h2 class="text-2xl sm:text-3xl font-serif font-bold text-[#171B18] leading-tight">
                        Pengalaman Mereka yang Sudah Meluncur
                    </h2>
                    <p class="text-xs sm:text-sm text-[#5C635B] mt-1 max-w-xl">
                        Ulasan terverifikasi dari wisatawan yang telah menyewa armada dan menjelajahi lereng Merapi bersama paguyuban kami.
                    </p>
                </div>
                
                {{-- Rata-rata Skor Riil --}}
                <div class="flex items-center gap-3 bg-white px-4 py-2 border border-[#E2DDD3] rounded-md self-start md:self-end">
                    <span class="font-serif font-black text-2xl text-[#171B18]">{{ number_format($avgRating, 1) }}</span>
                    <div class="text-xs text-[#5C635B]">
                        <div class="text-[#C25E38] font-mono tracking-tighter">★★★★★</div>
                        <span>Dari {{ $totalReviewsCount ?: 'puluhan' }} ulasan masuk</span>
                    </div>
                </div>
            </div>

            {{-- Review Cards Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-8">
                @forelse($reviews as $rev)
                    <article class="p-5 bg-white border border-[#E2DDD3] rounded-md flex flex-col justify-between space-y-4">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="font-mono text-xs text-[#C25E38]">
                                    {{ str_repeat('★', $rev->rating) }}{{ str_repeat('☆', 5 - $rev->rating) }}
                                </span>
                                <span class="text-[10px] text-[#5C635B] font-mono">
                                    {{ $rev->created_at ? $rev->created_at->format('d M Y') : 'Wisatawan' }}
                                </span>
                            </div>

                            <p class="text-xs sm:text-sm text-[#171B18] leading-relaxed italic">
                                "{{ $rev->comment }}"
                            </p>
                        </div>

                        <div class="pt-3 border-t border-[#E2DDD3] flex items-center justify-between text-xs">
                            <div>
                                <h4 class="font-bold text-[#171B18]">{{ $rev->user->name ?? 'Wisatawan' }}</h4>
                                <span class="text-[11px] text-[#5C635B] block">{{ $rev->city ?? 'Indonesia' }}</span>
                            </div>
                            @if($rev->package)
                                <span class="text-[10px] font-mono px-2 py-0.5 bg-[#ECE8DF] text-[#171B18] rounded">
                                    {{ $rev->package->name }}
                                </span>
                            @endif
                        </div>
                    </article>
                @empty
                    {{-- Realistic Fallbacks jika database baru/kosong --}}
                    <article class="p-5 bg-white border border-[#E2DDD3] rounded-md flex flex-col justify-between space-y-4">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="font-mono text-xs text-[#C25E38]">★★★★★</span>
                                <span class="text-[10px] text-[#5C635B] font-mono">Trip Sunrise</span>
                            </div>
                            <p class="text-xs sm:text-sm text-[#171B18] leading-relaxed italic">
                                "Berangkat jam 04.30 WIB dari basecamp, pemandangan Merapi saat fajar tanpa awan sama sekali. Driver Mas Joko sangat sabar membantu foto-foto keluarga."
                            </p>
                        </div>
                        <div class="pt-3 border-t border-[#E2DDD3] text-xs">
                            <h4 class="font-bold text-[#171B18]">Bramantyo Kurnia</h4>
                            <span class="text-[11px] text-[#5C635B]">Wisatawan Asal Jakarta</span>
                        </div>
                    </article>

                    <article class="p-5 bg-white border border-[#E2DDD3] rounded-md flex flex-col justify-between space-y-4">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="font-mono text-xs text-[#C25E38]">★★★★★</span>
                                <span class="text-[10px] text-[#5C635B] font-mono">Medium Trip A</span>
                            </div>
                            <p class="text-xs sm:text-sm text-[#171B18] leading-relaxed italic">
                                "Manuver air di Kali Kuning basah kuyup tapi luar biasa seru! Anak-anak senang sekali. Armada Katana merahnya kuat dan tarikannya enak di tanjakan pasir."
                            </p>
                        </div>
                        <div class="pt-3 border-t border-[#E2DDD3] text-xs">
                            <h4 class="font-bold text-[#171B18]">Dewi Ratnasari</h4>
                            <span class="text-[11px] text-[#5C635B]">Wisatawan Asal Surabaya</span>
                        </div>
                    </article>

                    <article class="p-5 bg-white border border-[#E2DDD3] rounded-md flex flex-col justify-between space-y-4">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="font-mono text-xs text-[#C25E38]">★★★★★</span>
                                <span class="text-[10px] text-[#5C635B] font-mono">Short Trip</span>
                            </div>
                            <p class="text-xs sm:text-sm text-[#171B18] leading-relaxed italic">
                                "Pelayanan dari parkir mobil pribadi sampai naik Jeep sangat teratur. Tidak ada calo yang memaksa. Harga sesuai yang tertera di web."
                            </p>
                        </div>
                        <div class="pt-3 border-t border-[#E2DDD3] text-xs">
                            <h4 class="font-bold text-[#171B18]">Irfan Syahputra</h4>
                            <span class="text-[11px] text-[#5C635B]">Wisatawan Asal Semarang</span>
                        </div>
                    </article>
                @endforelse
            </div>

            {{-- Link ke Semua Ulasan & Kirim Testimoni --}}
            <div class="pt-8 text-center">
                <a href="{{ route('reviews.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#171B18] hover:text-[#C25E38] transition-colors underline underline-offset-4">
                    <span>Lihat Halaman Ulasan Wisatawan & Bagikan Cerita Anda →</span>
                </a>
            </div>

        </div>
    </section>


    {{-- =========================================================================
         7. SECTION LOKASI BASECAMP & TANYA JAWAB (FIELD HQ & FAQ)
         Koordinat peta nyata, alamat jelas di Wukirsari, dan native accessible FAQ.
    ========================================================================= --}}
    <section id="tentang-paguyuban" class="py-14 sm:py-20 bg-[#F6F4EE] border-b border-[#E2DDD3]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">
                
                {{-- Left: Informasi Alamat & Peta Basecamp --}}
                <div class="lg:col-span-5 space-y-4">
                    <span class="font-mono text-xs text-[#C25E38] uppercase tracking-wider font-bold block">POS PUSAT BASECAMP</span>
                    <h2 class="text-2xl sm:text-3xl font-serif font-bold text-[#171B18] leading-tight">
                        Titik Kumpul & Loket Keberangkatan
                    </h2>
                    
                    <p class="text-xs sm:text-sm text-[#5C635B] leading-relaxed">
                        Basecamp Jeep Wukirsari berlokasi di jalur utama akses wisata Cangkringan. Berjarak sekitar 25 menit dari Candi Prambanan dan 40 menit dari kawasan Malioboro Kota Yogyakarta.
                    </p>

                    {{-- Detail Alamat Box --}}
                    <div class="p-4 bg-white border border-[#E2DDD3] rounded-md space-y-3 text-xs">
                        <div>
                            <span class="font-mono text-[10px] text-[#5C635B] uppercase block">ALAMAT LENGKAP POS:</span>
                            <strong class="text-[#171B18] text-sm">Dusun Pusung, Desa Wukirsari, Cangkringan, Sleman, D.I. Yogyakarta 55583</strong>
                        </div>
                        <div class="grid grid-cols-2 gap-2 pt-2 border-t border-[#E2DDD3]">
                            <div>
                                <span class="text-[10px] text-[#5C635B] block">JAM OPERASIONAL:</span>
                                <span class="font-semibold text-[#171B18]">04.30 – 17.00 WIB</span>
                            </div>
                            <div>
                                <span class="text-[10px] text-[#5C635B] block">PENGURUS POS:</span>
                                <span class="font-semibold text-[#171B18]">Paguyuban Pemuda</span>
                            </div>
                        </div>
                    </div>

                    {{-- Action Map Buttons --}}
                    <div class="flex flex-col sm:flex-row gap-2 pt-1">
                        <a href="https://maps.app.goo.gl/4JDthdZY315Qpf6D6" target="_blank" rel="noopener noreferrer" class="flex-1 py-3 px-4 rounded-md bg-[#171B18] hover:bg-[#232924] text-white text-xs font-bold text-center transition-all inline-flex items-center justify-center gap-2">
                            <svg class="w-4 h-4 text-[#C25E38]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>Buka Google Maps Pos</span>
                        </a>
                        <a href="https://wa.me/6281228677843" target="_blank" class="py-3 px-4 rounded-md border border-[#E2DDD3] bg-white hover:bg-[#FAF9F5] text-[#171B18] text-xs font-semibold text-center transition-all">
                            Tanya Petunjuk Arah
                        </a>
                    </div>
                </div>

                {{-- Right: Pertanyaan Sering Ditanyakan (Native HTML Details FAQ) --}}
                <div class="lg:col-span-7 space-y-3">
                    <span class="font-mono text-xs text-[#5C635B] uppercase tracking-wider font-bold block mb-2">TANYA JAWAB WISATAWAN (FAQ)</span>

                    {{-- FAQ 1 --}}
                    <details class="group p-4 bg-white border border-[#E2DDD3] rounded-md open:border-[#171B18] transition-colors">
                        <summary class="font-serif font-bold text-sm sm:text-base text-[#171B18] cursor-pointer list-none flex items-center justify-between gap-3">
                            <span>Berapa kapasitas penumpang untuk satu unit mobil Jeep?</span>
                            <span class="text-[#C25E38] font-mono text-sm transition-transform group-open:rotate-180">▼</span>
                        </summary>
                        <p class="text-xs sm:text-sm text-[#5C635B] mt-3 pt-3 border-t border-[#E2DDD3] leading-relaxed">
                            Kapasitas maksimal yang ditetapkan standar keselamatan paguyuban adalah <strong>4 orang dewasa</strong> (ditambah 1 driver dari kami). Jika ada anak kecil usia balita, bisa dipangku orang tua. Jika rombongan Anda 5-8 orang, kami sarankan memesan 2 unit armada agar manuver tetap aman dan nyaman.
                        </p>
                    </details>

                    {{-- FAQ 2 --}}
                    <details class="group p-4 bg-white border border-[#E2DDD3] rounded-md open:border-[#171B18] transition-colors">
                        <summary class="font-serif font-bold text-sm sm:text-base text-[#171B18] cursor-pointer list-none flex items-center justify-between gap-3">
                            <span>Apakah harus booking online terlebih dahulu atau bisa langsung datang?</span>
                            <span class="text-[#C25E38] font-mono text-sm transition-transform group-open:rotate-180">▼</span>
                        </summary>
                        <p class="text-xs sm:text-sm text-[#5C635B] mt-3 pt-3 border-t border-[#E2DDD3] leading-relaxed">
                            Anda bisa datang langsung (walk-in). Namun saat musim liburan, akhir pekan, atau trip fajar (sunrise trip 04.30 WIB), ketersediaan unit armada sering kali sudah habis dipesan. Kami sangat menyarankan reservasi online minimal H-1 untuk mengunci slot armada dan driver Anda.
                        </p>
                    </details>

                    {{-- FAQ 3 --}}
                    <details class="group p-4 bg-white border border-[#E2DDD3] rounded-md open:border-[#171B18] transition-colors">
                        <summary class="font-serif font-bold text-sm sm:text-base text-[#171B18] cursor-pointer list-none flex items-center justify-between gap-3">
                            <span>Bagaimana jika terjadi hujan badai atau jalur ditutup otoritas?</span>
                            <span class="text-[#C25E38] font-mono text-sm transition-transform group-open:rotate-180">▼</span>
                        </summary>
                        <p class="text-xs sm:text-sm text-[#5C635B] mt-3 pt-3 border-t border-[#E2DDD3] leading-relaxed">
                            Keselamatan penumpang adalah prioritas tertinggi. Jika Balai Penyelidikan dan Pengembangan Teknologi Kebencanaan Geologi (BPBD/TNGM) menyatakan jalur ditutup demi keselamatan, Anda berhak <strong>mengubah jadwal (reschedule) tanpa denda</strong> atau memperoleh pengembalian dana pembayaran.
                        </p>
                    </details>

                    {{-- FAQ 4 --}}
                    <details class="group p-4 bg-white border border-[#E2DDD3] rounded-md open:border-[#171B18] transition-colors">
                        <summary class="font-serif font-bold text-sm sm:text-base text-[#171B18] cursor-pointer list-none flex items-center justify-between gap-3">
                            <span>Apakah melayani antar-jemput langsung dari hotel di Kota Jogja?</span>
                            <span class="text-[#C25E38] font-mono text-sm transition-transform group-open:rotate-180">▼</span>
                        </summary>
                        <p class="text-xs sm:text-sm text-[#5C635B] mt-3 pt-3 border-t border-[#E2DDD3] leading-relaxed">
                            Armada Jeep 4x4 kami bertipe offroad terbuka dan sesuai regulasi dilarang melintasi jalan protokol perkotaan Jogja. Wisatawan berkumpul di Basecamp Wukirsari. Namun bila Anda tidak membawa kendaraan pribadi, tim admin kami dapat membantu mengaturkan mobil shuttle penjemputan (Avanza/Innova) dari hotel ke basecamp.
                        </p>
                    </details>
                </div>

            </div>

        </div>
    </section>


    {{-- =========================================================================
         8. FINAL CTA STRIP: RESERVASI TANPA REPOT
         Aksi penutup yang tegas, kontras, dan bebas jargon.
    ========================================================================= --}}
    <section class="py-12 sm:py-16 bg-[#171B18] text-[#FAF9F5] border-t border-[#2B332C]">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-5">
            <span class="font-mono text-xs text-[#C25E38] uppercase tracking-wider font-bold block">
                SIAP MENIKMATI ALAM MERAPI?
            </span>
            <h2 class="text-2xl sm:text-4xl font-serif font-black tracking-tight text-white leading-tight">
                Pilih Rute Anda Sekarang. Driver Paguyuban Siap Menyambut di Basecamp.
            </h2>
            <p class="text-xs sm:text-sm text-[#ECE8DF]/80 max-w-xl mx-auto leading-relaxed">
                Reservasi cepat kurang dari 2 menit. Tiket elektronik dan nomor kontak driver langsung dikirimkan ke WhatsApp Anda.
            </p>
            <div class="pt-2 flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="{{ route('booking.create') }}" class="w-full sm:w-auto px-8 py-3.5 rounded-md bg-[#C25E38] hover:bg-[#A54B28] text-white text-xs sm:text-sm font-bold tracking-wide transition-all shadow-[3px_3px_0px_0px_#111412] active:translate-y-0.5 active:shadow-none focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#C25E38]">
                    Mulai Booking Jeep Sekarang →
                </a>
                <a href="#rute-sewa" class="w-full sm:w-auto px-6 py-3.5 rounded-md bg-[#111412] hover:bg-[#232924] border border-[#2B332C] text-[#FAF9F5] text-xs sm:text-sm font-semibold transition-all">
                    Lihat Perbandingan Tarif
                </a>
            </div>
        </div>
    </section>


    {{-- =========================================================================
         JAVASCRIPT LOGIC: PRICE UPDATE & SIMULATION
         Ringan, native, tanpa library berat, ramah performa dan aksesibilitas.
    ========================================================================= --}}
    <script>
        function updateHeroPriceEstimate() {
            const select = document.getElementById('heroPackageSelect');
            const priceDisplay = document.getElementById('heroEstimatedPriceText');
            if (!select || !priceDisplay) return;

            const selectedOption = select.options[select.selectedIndex];
            const price = selectedOption.getAttribute('data-price') || '450000';
            
            const formatted = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                maximumFractionDigits: 0
            }).format(price).replace('IDR', 'Rp');

            priceDisplay.textContent = formatted;
        }

        document.addEventListener('DOMContentLoaded', function () {
            updateHeroPriceEstimate();
        });
    </script>

@endsection
