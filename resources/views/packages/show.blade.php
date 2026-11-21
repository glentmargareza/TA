@extends('layouts.app')

@section('title', 'Paket ' . $package->name . ' | Jeep Wukirsari Adventure Tour')

@section('content')
<div class="bg-wukirsari-cream min-h-screen py-8 sm:py-12">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Tombol Kembali -->
        <div>
            <a href="{{ route('home') }}#paket" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-white border border-wukirsari-border text-merapiText-primary hover:text-wukirsari-primary hover:border-wukirsari-primary text-xs sm:text-sm font-semibold shadow-xs hover:shadow-md transition-all group">
                <svg class="w-4 h-4 text-wukirsari-primary group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Kembali ke Beranda</span>
            </a>
        </div>

        @if(auth()->check() && auth()->user()->role === 'admin')
        <!-- Admin Bar Pemberitahuan & Tombol Aksi Cepat -->
        <div class="p-4 px-5 rounded-2xl bg-gradient-to-r from-emerald-50 via-teal-50 to-emerald-50 border border-emerald-200/90 shadow-2xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3.5">
            <div class="text-xs sm:text-sm text-emerald-950 font-medium">
                <span class="font-bold text-emerald-900">Akses Admin:</span> Anda dapat mengubah <strong>Foto Utama (Hero)</strong> dan <strong>4 Foto Galeri Destinasi</strong> untuk paket ini.
            </div>
            <button type="button" onclick="openPackagePhotosModal()" class="shrink-0 inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs shadow-xs hover:shadow-md transition-all cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>Kelola Foto Paket</span>
            </button>
        </div>
        @endif

        <!-- Main Card Detail Paket -->
        <div class="bg-white rounded-3xl border border-wukirsari-border overflow-hidden shadow-sm">
            
            <!-- Hero Image & Thumbnail Gallery (Persis Gambar Referensi Kanan) -->
            <div class="p-4 sm:p-6 pb-0">
                <!-- Foto Utama -->
                <div class="relative aspect-[16/9] sm:aspect-[21/10] rounded-2xl overflow-hidden bg-slate-900 shadow-md">
                    <img id="main-hero-image" src="{{ $package->cover_image }}" alt="{{ $package->name }}" class="w-full h-full object-cover transition-opacity duration-300">
                    
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>

                    @if($package->category_badge)
                    <span class="absolute top-4 left-4 px-4 py-1.5 rounded-full bg-wukirsari-primary text-white text-xs font-bold shadow-lg">
                        {{ $package->category_badge }}
                    </span>
                    @endif

                    @if(auth()->check() && auth()->user()->role === 'admin')
                    <!-- Tombol Kelola Foto Admin Floating di Cover -->
                    <div class="absolute top-4 right-4 z-20">
                        <button type="button" onclick="openPackagePhotosModal('cover')" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-white/95 hover:bg-white text-emerald-950 font-bold text-xs shadow-lg backdrop-blur-md border border-white/80 hover:border-emerald-300 transition-all hover:scale-105 cursor-pointer">
                            <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>Ubah Foto Paket</span>
                            <span class="px-1.5 py-0.5 rounded-md bg-emerald-100 text-emerald-800 text-[10px] font-extrabold uppercase">Admin</span>
                        </button>
                    </div>
                    @endif
                </div>

                <!-- 4 Galeri Thumbnail -->
                @php
                    $gallery = $package->effective_gallery;
                @endphp
                <div class="grid grid-cols-4 gap-2 sm:gap-4 mt-3 sm:mt-4">
                    @foreach($gallery as $index => $imgUrl)
                    <div class="relative group">
                        <button type="button" data-src="{{ $imgUrl }}" onclick="selectGalleryImage(this)" class="gallery-thumb w-full relative aspect-[16/10] rounded-xl overflow-hidden border-2 transition-all block cursor-pointer {{ $index === 0 ? 'border-wukirsari-primary ring-2 ring-wukirsari-primary/30' : 'border-transparent opacity-75 hover:opacity-100 hover:border-wukirsari-border' }}">
                            <img src="{{ $imgUrl }}" alt="Galeri {{ $package->name }} {{ $index + 1 }}" class="w-full h-full object-cover">
                        </button>
                        @if(auth()->check() && auth()->user()->role === 'admin')
                        <!-- Shortcut Ubah Foto Galeri Spesifik -->
                        <button type="button" onclick="openPackagePhotosModal('gallery_{{ $index }}')" title="Ganti Foto Galeri {{ $index + 1 }}" class="absolute top-1.5 right-1.5 z-10 w-6 h-6 rounded-lg bg-white/95 hover:bg-white text-emerald-900 shadow-md flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity border border-emerald-300 cursor-pointer">
                            <svg class="w-3.5 h-3.5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        </button>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Detail Isi & Metadata -->
            <div class="p-6 sm:p-10 space-y-8">
                
                <!-- Baris Nama Paket, Lokasi, dan Harga & Tombol Booking -->
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-6 border-b border-wukirsari-border">
                    <div class="space-y-2">
                        <h1 class="text-3xl sm:text-4xl font-extrabold text-merapiText-primary font-display">
                            {{ $package->name }}
                        </h1>

                        <!-- Metadata Badges -->
                        <div class="flex flex-wrap items-center gap-4 text-xs sm:text-sm text-merapiText-secondary">
                            <div class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-wukirsari-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="font-semibold text-merapiText-primary">{{ $package->effective_duration_text }}</span>
                            </div>
                            <span class="text-wukirsari-border">•</span>
                            <div class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-wukirsari-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                <span class="font-semibold text-merapiText-primary">Max 4 orang</span>
                            </div>
                            <span class="text-wukirsari-border">•</span>
                        </div>

                        <!-- Tagline Harga -->
                        <div class="pt-2 flex items-baseline gap-1.5">
                            <span class="text-3xl sm:text-4xl font-extrabold text-merapiText-primary font-display">
                                {{ $package->formatted_price }}
                            </span>
                            <span class="text-sm font-medium text-merapiText-secondary">/ paket</span>
                        </div>
                    </div>

                    <!-- Tombol Booking Utama -->
                    <div class="shrink-0">
                        <a href="{{ route('booking.create', ['package' => $package->slug]) }}" class="inline-flex items-center justify-center gap-2.5 px-8 py-3.5 rounded-full bg-wukirsari-primary hover:bg-wukirsari-hover text-white text-sm sm:text-base font-bold shadow-lg shadow-wukirsari-primary/25 hover:scale-[1.02] active:scale-95 transition-all w-full sm:w-auto">
                            <span>Booking Sekarang</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- CARD: FASILITAS YANG DIDAPAT (PERSIS GAMBAR REFERENSI KANAN) -->
                <div class="p-6 sm:p-8 rounded-3xl bg-wukirsari-cream/70 border border-wukirsari-border space-y-4">
                    <div class="flex items-center gap-2 text-wukirsari-primary">
                        <svg class="w-5 h-5 text-wukirsari-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.286L13 21l-2.286-6.857L5 12l5.714-2.286L13 3z"></path>
                        </svg>
                        <h3 class="text-base sm:text-lg font-bold text-merapiText-primary font-display">
                            Fasilitas yang Didapat
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-3 gap-x-6 text-xs sm:text-sm text-merapiText-primary">
                        <div class="flex items-center gap-2.5">
                            <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            </span>
                            <span class="font-medium">Jeep 4x4 Prima</span>
                        </div>

                        <div class="flex items-center gap-2.5">
                            <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            </span>
                            <span class="font-medium">Musholla Nyaman</span>
                        </div>

                        <div class="flex items-center gap-2.5">
                            <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            </span>
                            <span class="font-medium">Driver Profesional</span>
                        </div>

                        <div class="flex items-center gap-2.5">
                            <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            </span>
                            <span class="font-medium">Toilet Bersih</span>
                        </div>

                        <div class="flex items-center gap-2.5">
                            <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            </span>
                            <span class="font-medium">BBM (Bahan Bakar Minyak)</span>
                        </div>

                        <div class="flex items-center gap-2.5">
                            <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            </span>
                            <span class="font-medium">Parkir Luas Basecamp</span>
                        </div>

                        <div class="flex items-center gap-2.5">
                            <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            </span>
                            <span class="font-medium">Tiket Masuk</span>
                        </div>

                        <div class="flex items-center gap-2.5">
                            <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            </span>
                            <span class="font-medium">Ruang Tunggu Basecamp Santai</span>
                        </div>
                    </div>
                </div>

                <!-- TABS INTERAKTIF: ITINERARY | KETENTUAN -->
                <div class="pt-2">
                    
                    <!-- Tab Headers -->
                    <div class="flex items-center gap-2 border-b border-wukirsari-border pb-3 overflow-x-auto whitespace-nowrap" id="tab-nav">
                        <button type="button" onclick="switchTab('itinerary', this)" class="tab-btn px-5 py-2 rounded-full text-xs sm:text-sm font-bold bg-wukirsari-primary text-white transition-all shadow-xs">
                            Itinerary ({{ count($package->routes_array) }} Destinasi)
                        </button>
                        <button type="button" onclick="switchTab('ketentuan', this)" class="tab-btn px-5 py-2 rounded-full text-xs sm:text-sm font-semibold text-merapiText-secondary hover:text-wukirsari-primary hover:bg-wukirsari-sage/50 transition-all">
                            Ketentuan
                        </button>
                    </div>

                    <!-- Tab Contents -->
                    <div class="pt-6">
                        
                        <!-- TAB: ITINERARY / SELURUH RUTE DESTINASI -->
                        <div id="tab-content-itinerary" class="tab-pane space-y-6">
                            <div class="space-y-4">
                                <h4 class="text-sm font-bold uppercase tracking-wider text-wukirsari-primary font-display">
                                    Urutan Rute Kunjungan (Tepat {{ count($package->routes_array) }} Destinasi):
                                </h4>

                                <div class="relative pl-6 space-y-6 before:absolute before:left-2.5 before:top-2 before:bottom-2 before:w-0.5 before:bg-wukirsari-border">
                                    @foreach($package->routes_array as $idx => $routeItem)
                                    <div class="relative flex items-start gap-4">
                                        <div class="absolute -left-6 mt-0.5 w-5 h-5 rounded-full bg-wukirsari-primary text-white text-[11px] font-bold flex items-center justify-center ring-4 ring-white shadow-xs">
                                            {{ $idx + 1 }}
                                        </div>
                                        <div class="bg-wukirsari-cream/60 p-3.5 sm:p-4 rounded-2xl border border-wukirsari-border/80 flex-1 hover:bg-white transition-colors">
                                            <div class="flex items-center justify-between">
                                                <h5 class="text-sm sm:text-base font-bold text-merapiText-primary font-display">
                                                    {{ $routeItem }}
                                                </h5>
                                                <span class="text-[11px] text-wukirsari-primary font-medium px-2 py-0.5 rounded-md bg-wukirsari-sage">
                                                    Titik #{{ $idx + 1 }}
                                                </span>
                                            </div>
                                            <p class="text-xs text-merapiText-secondary mt-1">
                                                @if(str_contains(strtolower($routeItem), 'manuver air'))
                                                Atraksi basah-basahan memacu adrenalin dengan manuver melintasi aliran air yang seru.
                                                @elseif(str_contains(strtolower($routeItem), 'kampung hilang'))
                                                Saksi bisu pemukiman warga lereng Merapi yang tertimbun material erupsi vulkanik.
                                                @elseif(str_contains(strtolower($routeItem), 'kali gendol'))
                                                Menyusuri bantaran aliran sungai lahar dingin dengan bebatuan besar dan pasir Merapi.
                                                @elseif(str_contains(strtolower($routeItem), 'lorong oksigen'))
                                                Kawasan kanopi pohon hijau nan asri dengan udara pegunungan yang sangat segar dan sejuk.
                                                @elseif(str_contains(strtolower($routeItem), 'sokario'))
                                                Landmark gerbang batuan vulkanik legendaris lereng Merapi yang sangat estetik untuk berfoto.
                                                @elseif(str_contains(strtolower($routeItem), 'bunker'))
                                                Spot bunker perlindungan bersejarah Kaliadem dengan panorama megah puncak Gunung Merapi.
                                                @elseif(str_contains(strtolower($routeItem), 'volcano'))
                                                Taman edukasi dan panorama terbuka batuan vulkanik lahar dingin lereng Merapi.
                                                @elseif(str_contains(strtolower($routeItem), 'selo maruto'))
                                                Spot batu bertumpuk eksotis dengan pemandangan lembah hijau yang memanjakan mata.
                                                @else
                                                Eksplorasi keindahan panorama alam lereng Gunung Merapi dengan spot foto menawan.
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- TAB 3: KETENTUAN -->
                        <div id="tab-content-ketentuan" class="tab-pane hidden space-y-4">
                            <ul class="space-y-3 text-xs sm:text-sm text-merapiText-secondary">
                                <li class="flex items-start gap-3">
                                    <span class="text-wukirsari-primary mt-1 font-bold">•</span>
                                    <span><strong>Kapasitas Penumpang</strong>: 1 armada Jeep maksimal diisi oleh 4 orang penumpang demi kenyamanan dan keselamatan perjalanan.</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="text-wukirsari-primary mt-1 font-bold">•</span>
                                    <span><strong>Waktu Kedatangan</strong>: Harap tiba di Basecamp Jeep Wukirsari minimal 15–30 menit sebelum jadwal sesi trip dimulai untuk verifikasi e-tiket.</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="text-wukirsari-primary mt-1 font-bold">•</span>
                                    <span><strong>Pakaian & Perlengkapan</strong>: Disarankan mengenakan pakaian nyaman, alas kaki outdoor, kacamata, dan membawa baju ganti jika mengikuti sesi Manuver Air.</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="text-wukirsari-primary mt-1 font-bold">•</span>
                                    <span><strong>Pembayaran & Reschedule</strong>: Pembayaran dapat dilakukan secara Uang Muka (DP 30%) atau Lunas 100%. Reschedule dapat dikoordinasikan minimal H-1 sebelum keberangkatan.</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="text-wukirsari-primary mt-1 font-bold">•</span>
                                    <span><strong>Kondisi Cuaca</strong>: Perjalanan tetap aman dipandu driver berpengalaman; rute dapat disesuaikan demi keselamatan jika terjadi cuaca ekstrem.</span>
                                </li>
                            </ul>
                        </div>

                    </div>
                </div>

                <!-- Callout Banner Alam (Persis Gambar Referensi Kanan) -->
                <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-wukirsari-primary to-wukirsari-dark text-white p-6 sm:p-8 shadow-md">
                    <div class="relative z-10 flex flex-col sm:flex-row items-center justify-between gap-4 text-center sm:text-left">
                        <div class="space-y-1">
                            <span class="text-xs font-accent italic text-wukirsari-sage">Kenangan Tak Terlupakan</span>
                            <h4 class="text-xl sm:text-2xl font-extrabold font-accent italic text-white">
                                "Jelajahi keindahan alam bersama kami, Jeep Wukirsari!"
                            </h4>
                            <p class="text-xs text-white/80">
                                Dapatkan garansi pelayanan ramah, driver andal, dan armada 4x4 terbaik di lereng Merapi.
                            </p>
                        </div>

                        <a href="{{ route('booking.create', ['package' => $package->slug]) }}" class="shrink-0 px-6 py-3 rounded-full bg-white text-wukirsari-primary hover:bg-wukirsari-sage font-bold text-xs sm:text-sm shadow-md transition-all active:scale-95">
                            Booking Sekarang →
                        </a>
                    </div>
                </div>

            </div>
        </div>

        <!-- SECTION ULASAN WISATAWAN UNTUK PAKET INI -->
        <div class="pt-6 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                <div>
                    <h3 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-merapiText-primary font-display">
                        Apa Kata Orang Lain Tentang {{ $package->name }}?
                    </h3>
                    <p class="text-xs sm:text-sm text-merapiText-secondary mt-1">
                        Pengalaman dan penilaian langsung dari wisatawan lain yang telah mencoba rute ini.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('reviews.index', ['package' => $package->slug]) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white border border-wukirsari-border hover:border-wukirsari-primary text-merapiText-primary hover:text-wukirsari-primary text-xs font-semibold shadow-xs transition-all">
                        <span>Lihat Semua Ulasan →</span>
                    </a>
                    @guest
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-wukirsari-sage text-wukirsari-primary hover:bg-wukirsari-primary hover:text-white text-xs font-bold transition-all">
                        <span>Masuk untuk Review</span>
                    </a>
                    @endguest
                </div>
            </div>

            <!-- Kartu List Review Wisatawan Lain -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @forelse($reviews as $rev)
                <div class="bg-white p-6 rounded-3xl border border-wukirsari-border shadow-xs hover:shadow-md transition-all flex flex-col justify-between space-y-4">
                    <div class="space-y-3">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-10 h-10 rounded-2xl bg-wukirsari-sage text-wukirsari-primary flex items-center justify-center font-bold text-sm shrink-0 font-display">
                                    {{ strtoupper(substr($rev->user->name ?? 'W', 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <h4 class="text-sm font-bold text-merapiText-primary font-display truncate">
                                        {{ $rev->user->name ?? 'Wisatawan' }}
                                    </h4>
                                    <span class="text-xs text-merapiText-muted truncate block">
                                        {{ $rev->city ?? 'Indonesia' }}
                                    </span>
                                </div>
                            </div>

                            @if(Auth::check() && (Auth::id() === $rev->user_id || Auth::user()->isAdmin()))
                            <div class="flex items-center gap-1.5 shrink-0">
                                <button type="button" 
                                    onclick="handleEditReviewClick(this)"
                                    data-id="{{ $rev->id }}"
                                    data-rating="{{ $rev->rating }}"
                                    data-city="{{ $rev->city }}"
                                    data-comment="{{ $rev->comment }}"
                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-wukirsari-sage/70 hover:bg-wukirsari-sage text-wukirsari-primary text-[11px] font-bold border border-wukirsari-border transition-all cursor-pointer shadow-xs" 
                                    title="Edit Ulasan">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    <span>Edit</span>
                                </button>
                                <form action="{{ route('reviews.destroy', $rev->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ulasan ini?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 text-[11px] font-bold border border-rose-200 transition-all cursor-pointer shadow-xs" 
                                        title="Hapus Ulasan">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        <span>Delete</span>
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>

                        <div class="flex items-center gap-1 text-yellow-400">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $rev->rating)
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @else
                                <svg class="w-4 h-4 fill-slate-200" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @endif
                            @endfor
                            <span class="text-xs font-bold text-merapiText-secondary ml-1">{{ $rev->rating }}.0</span>
                        </div>

                        <p class="text-xs sm:text-sm text-merapiText-secondary italic leading-relaxed">
                            "{{ $rev->comment }}"
                        </p>
                    </div>

                    <div class="pt-2 border-t border-wukirsari-border/70 text-[11px] text-merapiText-muted flex items-center justify-between">
                        <span>{{ $rev->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                @empty
                <div class="col-span-full bg-white p-8 rounded-3xl border border-wukirsari-border text-center space-y-3">
                    <div class="w-12 h-12 rounded-2xl bg-wukirsari-cream text-wukirsari-primary flex items-center justify-center mx-auto">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    </div>
                    <h4 class="text-base font-bold text-merapiText-primary font-display">Belum ada review khusus paket ini</h4>
                    <p class="text-xs text-merapiText-secondary max-w-md mx-auto">
                        Anda dapat melihat ratusan review wisatawan lainnya di halaman ulasan umum, atau jadilah yang pertama memberikan ulasan untuk paket ini!
                    </p>
                    <div class="pt-2">
                        <a href="{{ route('reviews.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-wukirsari-primary text-white text-xs font-bold hover:bg-wukirsari-hover transition-all">
                            <span>Lihat Semua Review Wisatawan Lainnya →</span>
                        </a>
                    </div>
                </div>
                @endforelse
            </div>

            <!-- Form Tulis Ulasan Jika Sedang Login -->
            @auth
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-wukirsari-border shadow-xs mt-6 space-y-4">
                <div class="flex items-center justify-between border-b border-wukirsari-border pb-3">
                    <h4 class="text-sm sm:text-base font-bold text-merapiText-primary font-display flex items-center gap-2">
                        <svg class="w-4 h-4 text-wukirsari-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        <span>Tulis Ulasan Anda untuk {{ $package->name }}</span>
                    </h4>
                    <span class="text-xs text-merapiText-muted">Sebagai: <strong class="text-wukirsari-primary">{{ Auth::user()->name }}</strong></span>
                </div>

                <form action="{{ route('reviews.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="package_id" value="{{ $package->id }}">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-merapiText-primary mb-1">Penilaian (Bintang 1 - 5) *</label>
                            <select name="rating" required class="w-full px-3.5 py-2.5 rounded-xl border border-wukirsari-border text-xs focus:outline-none focus:border-wukirsari-primary bg-white">
                                <option value="5" selected>5 Bintang (5/5) - Sangat Puas</option>
                                <option value="4">4 Bintang (4/5) - Puas</option>
                                <option value="3">3 Bintang (3/5) - Cukup</option>
                                <option value="2">2 Bintang (2/5) - Kurang</option>
                                <option value="1">1 Bintang (1/5) - Kecewa</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-merapiText-primary mb-1">Kota Asal (Opsional)</label>
                            <input type="text" name="city" placeholder="Contoh: Jakarta / Solo / Bandung" class="w-full px-3.5 py-2.5 rounded-xl border border-wukirsari-border text-xs focus:outline-none focus:border-wukirsari-primary">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-merapiText-primary mb-1">Cerita Pengalaman Anda *</label>
                        <textarea name="comment" rows="3" required placeholder="Bagikan bagaimana rute, driver, dan pengalaman seru Anda pada paket ini..." class="w-full px-3.5 py-2.5 rounded-xl border border-wukirsari-border text-xs focus:outline-none focus:border-wukirsari-primary"></textarea>
                    </div>

                    <button type="submit" class="px-6 py-2.5 rounded-full bg-wukirsari-primary text-white text-xs font-bold hover:bg-wukirsari-hover transition-all shadow-sm">
                        Kirim Ulasan Paket Ini →
                    </button>
                </form>
            </div>
            @endauth
        </div>

        <!-- REKOMENDASI PAKET LAINNYA -->
        @if(isset($otherPackages) && $otherPackages->count() > 0)
        <div class="pt-8 space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl sm:text-2xl font-extrabold text-merapiText-primary font-display">Paket Wisata Lainnya</h3>
                </div>
                <a href="{{ route('home') }}#paket" class="text-xs sm:text-sm font-semibold text-wukirsari-primary hover:text-wukirsari-hover transition-colors">
                    Lihat Paket Lainnya di Beranda →
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($otherPackages as $other)
                <div class="bg-white rounded-3xl overflow-hidden border border-wukirsari-border shadow-xs hover:shadow-lg transition-all p-5 flex flex-col justify-between space-y-4">
                    <div class="space-y-2">
                        <div class="relative aspect-[16/10] rounded-2xl overflow-hidden bg-slate-100">
                            <img src="{{ $other->cover_image }}" alt="{{ $other->name }}" class="w-full h-full object-cover">
                            <span class="absolute top-2.5 left-2.5 px-2.5 py-0.5 rounded-full bg-wukirsari-primary text-white text-[10px] font-bold">
                                {{ $other->destinations_count }} Destinasi
                            </span>
                        </div>
                        <h4 class="text-base font-bold text-merapiText-primary font-display mt-2">
                            {{ $other->name }}
                        </h4>
                        <div class="flex items-center gap-3 text-xs text-merapiText-secondary">
                            <span>{{ $other->effective_duration_text }}</span>
                            <span>•</span>
                            <span>Max 4 orang</span>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-wukirsari-border flex items-center justify-between">
                        <div>
                            
                            <span class="text-base font-bold text-merapiText-primary font-display">{{ $other->formatted_price }}</span>
                        </div>
                        <a href="{{ route('packages.show', $other->slug) }}" class="px-4 py-2 rounded-full border border-wukirsari-border hover:border-wukirsari-primary text-merapiText-primary hover:text-wukirsari-primary text-xs font-semibold transition-all">
                            Detail
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</div>

@if(auth()->check() && auth()->user()->role === 'admin')
<!-- Modal Kelola & Ubah Foto Paket (Admin) -->
<div id="modalEditPackagePhotos" class="fixed inset-0 z-50 bg-slate-950/75 backdrop-blur-xs hidden flex items-center justify-center p-3 sm:p-6 overflow-y-auto">
    <div id="modalEditPackagePhotosCard" class="bg-white rounded-3xl shadow-2xl max-w-4xl w-full border border-slate-200 overflow-hidden my-auto transform transition-all duration-200 scale-95 opacity-0 flex flex-col max-h-[92vh]">
        
        <!-- Modal Header -->
        <div class="p-5 sm:p-6 bg-gradient-to-r from-emerald-950 via-emerald-900 to-emerald-950 text-white flex items-center justify-between gap-4 shrink-0">
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-2xl bg-white/10 backdrop-blur border border-white/20 flex items-center justify-center text-emerald-300 shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h3 class="text-base sm:text-lg font-bold font-display leading-tight">Kelola Foto Paket: {{ $package->name }}</h3>
                        <span class="px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 text-[10px] font-extrabold uppercase tracking-wider">Admin</span>
                    </div>
                    <p class="text-xs text-emerald-200/90 mt-0.5">Ubah foto cover utama dan 4 foto thumbnail galeri paket wisata ini.</p>
                </div>
            </div>
            <button type="button" onclick="closePackagePhotosModal()" class="w-9 h-9 rounded-2xl bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors cursor-pointer shrink-0" title="Tutup Modal">
                ✕
            </button>
        </div>

        <!-- Segmented Tab Header -->
        <div class="px-5 sm:px-6 pt-3 bg-slate-100/90 border-b border-slate-200 flex items-center gap-2 shrink-0 overflow-x-auto">
            <button type="button" onclick="switchPhotoModalTab('cover')" id="tabBtnCover" class="px-4 py-2.5 rounded-t-2xl font-bold text-xs sm:text-sm border-b-2 border-emerald-700 text-emerald-900 bg-white shadow-2xs transition-all flex items-center gap-2 whitespace-nowrap cursor-pointer">
                <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span>1. Foto Utama (Cover)</span>
            </button>
            <button type="button" onclick="switchPhotoModalTab('gallery')" id="tabBtnGallery" class="px-4 py-2.5 rounded-t-2xl font-bold text-xs sm:text-sm border-b-2 border-transparent text-slate-500 hover:text-slate-800 hover:bg-white/60 transition-all flex items-center gap-2 whitespace-nowrap cursor-pointer">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                <span>2. Galeri Wisata (4 Foto)</span>
            </button>
        </div>

        <!-- Form Content -->
        <form id="formEditPackagePhotos" action="{{ route('admin.packages.updatePhotos', $package->id) }}" method="POST" enctype="multipart/form-data" class="overflow-y-auto flex-1 p-5 sm:p-6 space-y-6">
            @csrf

            <!-- TAB 1: FOTO UTAMA (COVER) -->
            <div id="photoTabCover" class="space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-2 border-b border-slate-100">
                    <div>
                        <h4 class="text-sm font-extrabold text-slate-900">Foto Utama (Hero Cover)</h4>
                        <p class="text-xs text-slate-500">Foto ini tampil sebagai gambar paling besar di halaman detail dan kartu paket beranda.</p>
                    </div>
                    <label class="inline-flex items-center gap-2 cursor-pointer text-xs font-semibold text-rose-700 bg-rose-50 hover:bg-rose-100 px-3 py-1.5 rounded-xl border border-rose-200 transition-colors w-fit">
                        <input type="checkbox" name="reset_cover" value="1" id="reset_cover_cb" onchange="toggleResetCover(this)" class="rounded text-rose-600 focus:ring-rose-500">
                        <span>Kembalikan Foto Bawaan</span>
                    </label>
                </div>

                <!-- Preview Box Cover -->
                <div class="relative aspect-[16/9] sm:aspect-[21/9] rounded-2xl overflow-hidden bg-slate-900 border-2 border-slate-200 shadow-inner group">
                    <img id="coverModalPreview" src="{{ $package->cover_image }}" alt="Preview Cover" class="w-full h-full object-cover">
                    <div class="absolute bottom-3 left-3 bg-black/75 backdrop-blur text-white text-[11px] font-semibold px-3 py-1 rounded-lg flex items-center">
                        <span id="coverPreviewLabel">Preview Foto Aktif</span>
                    </div>
                </div>

                <!-- Input Grid Cover -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-2">
                        <label class="block text-xs font-bold text-slate-800">
                            1. Upload File dari Perangkat (PC / HP)
                        </label>
                        <input type="file" id="cover_file_input" name="cover_image_file" accept="image/png, image/jpeg, image/jpg, image/webp" class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-700 file:text-white hover:file:bg-emerald-800 file:cursor-pointer cursor-pointer" onchange="previewCoverFile(this)">
                        <p class="text-[11px] text-slate-500">Format yang didukung: JPG, JPEG, PNG, WEBP (Maksimal 5 MB)</p>
                    </div>

                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-2">
                        <label for="cover_url_input" class="block text-xs font-bold text-slate-800">
                            2. Atau Tautkan URL Gambar Online
                        </label>
                        <input type="url" id="cover_url_input" name="cover_image_url" placeholder="https://images.unsplash.com/photo-..." class="w-full px-3.5 py-2 text-xs rounded-xl border border-slate-300 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 bg-white" oninput="previewCoverUrl(this.value)">
                        <p class="text-[11px] text-slate-500">Gunakan jika gambar tersimpan di CDN atau hosting eksternal</p>
                    </div>
                </div>
            </div>

            <!-- TAB 2: 4 FOTO GALERI -->
            <div id="photoTabGallery" class="space-y-4 hidden">
                <div class="pb-2 border-b border-slate-100">
                    <h4 class="text-sm font-extrabold text-slate-900">4 Foto Galeri Destinasi</h4>
                    <p class="text-xs text-slate-500">Foto-foto ini muncul pada 4 kolom thumbnail di bawah foto utama. Pengunjung dapat mengkliknya untuk melihat di foto utama.</p>
                </div>

                @php
                    $effectiveGallery = $package->effective_gallery;
                @endphp

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @for($i = 0; $i < 4; $i++)
                    @php
                        $curThumb = $effectiveGallery[$i] ?? asset('img/7.jpeg');
                    @endphp
                    <div id="gallery_card_{{ $i }}" class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-3 transition-all duration-300">
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-emerald-100 text-emerald-950 text-xs font-extrabold">
                                <svg class="w-3.5 h-3.5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span>Galeri {{ $i + 1 }}</span>
                            </span>
                            <label class="inline-flex items-center gap-1.5 cursor-pointer text-[11px] font-semibold text-rose-700 bg-rose-50 hover:bg-rose-100 px-2.5 py-1 rounded-lg border border-rose-200 transition-colors">
                                <input type="checkbox" name="reset_gallery[{{ $i }}]" value="1" id="reset_gallery_cb_{{ $i }}" onchange="toggleResetGallery({{ $i }}, this)" class="rounded text-rose-600 focus:ring-rose-500">
                                <span>Reset Default</span>
                            </label>
                        </div>

                        <!-- Preview Thumbnail -->
                        <div class="relative aspect-[16/10] rounded-xl overflow-hidden bg-slate-900 border border-slate-200 shadow-inner">
                            <img id="gallery_preview_{{ $i }}" src="{{ $curThumb }}" alt="Galeri {{ $i + 1 }}" class="w-full h-full object-cover">
                            <span id="gallery_label_{{ $i }}" class="absolute bottom-2 left-2 bg-black/75 backdrop-blur text-white text-[10px] font-medium px-2 py-0.5 rounded">
                                Foto Saat Ini
                            </span>
                        </div>

                        <!-- Inputs File & URL -->
                        <div class="space-y-2 pt-1">
                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 mb-1">Upload File Baru:</label>
                                <input type="file" id="gallery_file_{{ $i }}" name="gallery_files[{{ $i }}]" accept="image/png, image/jpeg, image/jpg, image/webp" class="w-full text-xs text-slate-600 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-[11px] file:font-semibold file:bg-emerald-700 file:text-white hover:file:bg-emerald-800 file:cursor-pointer cursor-pointer" onchange="previewGalleryFile({{ $i }}, this)">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 mb-1">Atau Link URL Gambar:</label>
                                <input type="url" id="gallery_url_{{ $i }}" name="gallery_urls[{{ $i }}]" placeholder="https://..." class="w-full px-3 py-1.5 text-xs rounded-xl border border-slate-300 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 bg-white" oninput="previewGalleryUrl({{ $i }}, this.value)">
                            </div>
                        </div>
                    </div>
                    @endfor
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="pt-4 border-t border-slate-200 flex items-center justify-between gap-3">
                <button type="button" onclick="closePackagePhotosModal()" class="px-5 py-2.5 rounded-xl border border-slate-300 hover:bg-slate-100 text-slate-700 font-semibold text-xs sm:text-sm transition-all cursor-pointer">
                    Batal
                </button>
                <button type="submit" id="btnSubmitPhotos" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs sm:text-sm shadow-md hover:shadow-lg transition-all cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span id="btnSubmitPhotosText">Simpan Perubahan Foto</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endif

<!-- JAVASCRIPT UNTUK INTERAKTIVITAS GALERI FOTO DAN TAB KONTEN -->
<script>
    @if(auth()->check() && auth()->user()->role === 'admin')
    const defaultCoverUrl = @json(asset(\App\Models\Package::getDefaultCoverForSlug($package->slug)));
    const defaultGalleryUrls = @json(array_map(fn($p) => asset($p), \App\Models\Package::getDefaultGalleryForSlug($package->slug)));
    const currentCoverUrl = @json($package->cover_image);
    const currentGalleryUrls = @json($package->effective_gallery);

    function openPackagePhotosModal(targetTab) {
        const modal = document.getElementById('modalEditPackagePhotos');
        const card = document.getElementById('modalEditPackagePhotosCard');
        if (!modal || !card) return;

        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');

        requestAnimationFrame(() => {
            card.classList.remove('scale-95', 'opacity-0');
            card.classList.add('scale-100', 'opacity-100');
        });

        if (targetTab && targetTab.startsWith('gallery')) {
            switchPhotoModalTab('gallery');
            const parts = targetTab.split('_');
            if (parts.length > 1) {
                const idx = parseInt(parts[1]);
                setTimeout(() => {
                    const targetCard = document.getElementById('gallery_card_' + idx);
                    if (targetCard) {
                        targetCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        targetCard.classList.add('ring-4', 'ring-emerald-400');
                        setTimeout(() => targetCard.classList.remove('ring-4', 'ring-emerald-400'), 1500);
                    }
                }, 150);
            }
        } else {
            switchPhotoModalTab('cover');
        }
    }

    function closePackagePhotosModal() {
        const modal = document.getElementById('modalEditPackagePhotos');
        const card = document.getElementById('modalEditPackagePhotosCard');
        if (!modal || !card) return;

        card.classList.remove('scale-100', 'opacity-100');
        card.classList.add('scale-95', 'opacity-0');

        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }, 150);
    }

    function switchPhotoModalTab(tab) {
        const tabCover = document.getElementById('photoTabCover');
        const tabGallery = document.getElementById('photoTabGallery');
        const btnCover = document.getElementById('tabBtnCover');
        const btnGallery = document.getElementById('tabBtnGallery');

        if (!tabCover || !tabGallery) return;

        if (tab === 'cover') {
            tabCover.classList.remove('hidden');
            tabGallery.classList.add('hidden');

            btnCover.classList.add('border-emerald-700', 'text-emerald-900', 'bg-white', 'shadow-2xs');
            btnCover.classList.remove('border-transparent', 'text-slate-500');

            btnGallery.classList.remove('border-emerald-700', 'text-emerald-900', 'bg-white', 'shadow-2xs');
            btnGallery.classList.add('border-transparent', 'text-slate-500');
        } else {
            tabCover.classList.add('hidden');
            tabGallery.classList.remove('hidden');

            btnGallery.classList.add('border-emerald-700', 'text-emerald-900', 'bg-white', 'shadow-2xs');
            btnGallery.classList.remove('border-transparent', 'text-slate-500');

            btnCover.classList.remove('border-emerald-700', 'text-emerald-900', 'bg-white', 'shadow-2xs');
            btnCover.classList.add('border-transparent', 'text-slate-500');
        }
    }

    function previewCoverFile(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById('coverModalPreview');
                const lbl = document.getElementById('coverPreviewLabel');
                if (img) img.src = e.target.result;
                if (lbl) lbl.textContent = 'File Dipilih: ' + input.files[0].name;
                const resetCb = document.getElementById('reset_cover_cb');
                if (resetCb) resetCb.checked = false;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewCoverUrl(url) {
        const fileInput = document.getElementById('cover_file_input');
        if (fileInput && fileInput.files && fileInput.files.length > 0) return;

        const img = document.getElementById('coverModalPreview');
        const lbl = document.getElementById('coverPreviewLabel');
        const resetCb = document.getElementById('reset_cover_cb');

        if (url && url.trim().length > 5) {
            if (img) img.src = url.trim();
            if (lbl) lbl.textContent = 'Preview URL Online';
            if (resetCb) resetCb.checked = false;
        } else {
            if (img) img.src = currentCoverUrl;
            if (lbl) lbl.textContent = 'Preview Foto Aktif';
        }
    }

    function toggleResetCover(cb) {
        const img = document.getElementById('coverModalPreview');
        const lbl = document.getElementById('coverPreviewLabel');
        const fileInput = document.getElementById('cover_file_input');
        const urlInput = document.getElementById('cover_url_input');

        if (cb.checked) {
            if (img) img.src = defaultCoverUrl;
            if (lbl) lbl.textContent = 'Akan di-reset ke Default';
            if (fileInput) fileInput.value = '';
            if (urlInput) urlInput.value = '';
        } else {
            if (img) img.src = currentCoverUrl;
            if (lbl) lbl.textContent = 'Preview Foto Aktif';
        }
    }

    function previewGalleryFile(idx, input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById('gallery_preview_' + idx);
                const lbl = document.getElementById('gallery_label_' + idx);
                if (img) img.src = e.target.result;
                if (lbl) lbl.textContent = 'File: ' + input.files[0].name;
                const resetCb = document.getElementById('reset_gallery_cb_' + idx);
                if (resetCb) resetCb.checked = false;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewGalleryUrl(idx, url) {
        const fileInput = document.getElementById('gallery_file_' + idx);
        if (fileInput && fileInput.files && fileInput.files.length > 0) return;

        const img = document.getElementById('gallery_preview_' + idx);
        const lbl = document.getElementById('gallery_label_' + idx);
        const resetCb = document.getElementById('reset_gallery_cb_' + idx);

        if (url && url.trim().length > 5) {
            if (img) img.src = url.trim();
            if (lbl) lbl.textContent = 'URL Online';
            if (resetCb) resetCb.checked = false;
        } else {
            if (img) img.src = currentGalleryUrls[idx] || defaultGalleryUrls[idx];
            if (lbl) lbl.textContent = 'Foto Saat Ini';
        }
    }

    function toggleResetGallery(idx, cb) {
        const img = document.getElementById('gallery_preview_' + idx);
        const lbl = document.getElementById('gallery_label_' + idx);
        const fileInput = document.getElementById('gallery_file_' + idx);
        const urlInput = document.getElementById('gallery_url_' + idx);

        if (cb.checked) {
            if (img) img.src = defaultGalleryUrls[idx];
            if (lbl) lbl.textContent = 'Akan di-reset Default';
            if (fileInput) fileInput.value = '';
            if (urlInput) urlInput.value = '';
        } else {
            if (img) img.src = currentGalleryUrls[idx] || defaultGalleryUrls[idx];
            if (lbl) lbl.textContent = 'Foto Saat Ini';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('modalEditPackagePhotos');
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closePackagePhotosModal();
                }
            });
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closePackagePhotosModal();
            }
        });

        const form = document.getElementById('formEditPackagePhotos');
        if (form) {
            form.addEventListener('submit', function() {
                const btn = document.getElementById('btnSubmitPhotos');
                const txt = document.getElementById('btnSubmitPhotosText');
                if (btn) {
                    btn.disabled = true;
                    btn.classList.add('opacity-75', 'cursor-not-allowed');
                }
                if (txt) {
                    txt.textContent = 'Menyimpan Foto...';
                }
            });
        }
    });
    @endif
    function selectGalleryImage(src, buttonEl) {
        if (src instanceof HTMLElement) {
            buttonEl = src;
            src = buttonEl.getAttribute('data-src');
        }

        const heroImg = document.getElementById('main-hero-image');
        if (heroImg) {
            heroImg.style.opacity = '0.4';
            setTimeout(() => {
                heroImg.src = src;
                heroImg.style.opacity = '1';
            }, 150);
        }

        const thumbs = document.querySelectorAll('.gallery-thumb');
        thumbs.forEach(t => {
            t.classList.remove('border-wukirsari-primary', 'ring-2', 'ring-wukirsari-primary/30');
            t.classList.add('border-transparent', 'opacity-75');
        });

        buttonEl.classList.remove('border-transparent', 'opacity-75');
        buttonEl.classList.add('border-wukirsari-primary', 'ring-2', 'ring-wukirsari-primary/30');
    }

    function switchTab(tabName, buttonEl) {
        // Update tab buttons
        const tabButtons = document.querySelectorAll('.tab-btn');
        tabButtons.forEach(btn => {
            btn.classList.remove('bg-wukirsari-primary', 'text-white', 'shadow-xs');
            btn.classList.add('text-merapiText-secondary');
        });

        buttonEl.classList.remove('text-merapiText-secondary');
        buttonEl.classList.add('bg-wukirsari-primary', 'text-white', 'shadow-xs');

        // Update tab panes
        const panes = document.querySelectorAll('.tab-pane');
        panes.forEach(p => p.classList.add('hidden'));

        const targetPane = document.getElementById('tab-content-' + tabName);
        if (targetPane) {
            targetPane.classList.remove('hidden');
        }
    }
</script>
@endsection
