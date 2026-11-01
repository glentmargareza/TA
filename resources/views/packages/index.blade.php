@extends('layouts.app')

@section('title', 'Paket Wisata Jeep Wukirsari | Petualangan Offroad Lereng Merapi')

@section('content')
<div class="bg-wukirsari-cream min-h-screen py-10 sm:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Judul Halaman -->
        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 gap-4 border-b border-wukirsari-border pb-6">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-wukirsari-primary font-display block mb-1">PILIHAN RESMI JEEP WUKIRSARI</span>
                <h1 class="text-2xl sm:text-4xl font-extrabold text-merapiText-primary font-display">Paket Wisata</h1>
                <p class="text-merapiText-secondary text-xs sm:text-sm mt-1">Tarif resmi dihitung per armada jeep (maksimal 4 penumpang per jeep).</p>
            </div>

            <div class="text-xs sm:text-sm text-merapiText-muted font-medium" id="package-counter">
                Menampilkan <span class="font-bold text-merapiText-primary" id="visible-count">{{ $packages->count() }}</span> dari {{ $packages->count() }} paket
            </div>
        </div>

        <!-- Filter Bar & Search Section -->
        <div class="bg-white rounded-3xl p-3 sm:p-4 shadow-sm border border-wukirsari-border flex flex-col lg:flex-row items-center justify-between gap-4 mb-8">
            <!-- Category Filter Pills -->
            <div class="flex items-center gap-2 overflow-x-auto w-full lg:w-auto pb-2 lg:pb-0 scrollbar-none" id="filter-container">
                <button type="button" data-filter="all" class="filter-btn shrink-0 px-5 py-2.5 rounded-full text-xs sm:text-sm font-semibold transition-all duration-200 bg-wukirsari-primary text-white shadow-md shadow-wukirsari-primary/20">
                    Semua ({{ $packages->count() }})
                </button>
                <button type="button" data-filter="short" class="filter-btn shrink-0 px-5 py-2.5 rounded-full text-xs sm:text-sm font-medium transition-all duration-200 bg-wukirsari-sage/70 hover:bg-wukirsari-sage text-merapiText-secondary hover:text-wukirsari-primary border border-wukirsari-border">
                    Short Trip
                </button>
                <button type="button" data-filter="medium" class="filter-btn shrink-0 px-5 py-2.5 rounded-full text-xs sm:text-sm font-medium transition-all duration-200 bg-wukirsari-sage/70 hover:bg-wukirsari-sage text-merapiText-secondary hover:text-wukirsari-primary border border-wukirsari-border">
                    Medium Trip (A & B)
                </button>
                <button type="button" data-filter="long" class="filter-btn shrink-0 px-5 py-2.5 rounded-full text-xs sm:text-sm font-medium transition-all duration-200 bg-wukirsari-sage/70 hover:bg-wukirsari-sage text-merapiText-secondary hover:text-wukirsari-primary border border-wukirsari-border">
                    Long Trip
                </button>
                <button type="button" data-filter="sunrise" class="filter-btn shrink-0 px-5 py-2.5 rounded-full text-xs sm:text-sm font-medium transition-all duration-200 bg-wukirsari-sage/70 hover:bg-wukirsari-sage text-merapiText-secondary hover:text-wukirsari-primary border border-wukirsari-border">
                    Sunrise Trip
                </button>
            </div>

            <!-- Search Input Bar -->
            <div class="w-full lg:w-80 relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-merapiText-muted">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" id="package-search-input" placeholder="Cari paket wisata atau rute..." value="{{ $search ?? '' }}" class="w-full pl-10 pr-4 py-2.5 rounded-full bg-wukirsari-cream/80 border border-wukirsari-border focus:bg-white focus:border-wukirsari-primary focus:ring-2 focus:ring-wukirsari-primary/20 text-xs sm:text-sm text-merapiText-primary placeholder-merapiText-muted outline-none transition-all">
            </div>
        </div>

        <!-- Empty State jika pencarian tidak ditemukan -->
        <div id="empty-state" class="hidden py-16 text-center bg-white rounded-3xl border border-wukirsari-border p-8 mb-8">
            <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-wukirsari-sage flex items-center justify-center text-wukirsari-primary">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-merapiText-primary font-display">Paket tidak ditemukan</h3>
            <p class="text-sm text-merapiText-secondary mt-1 max-w-md mx-auto">Tidak ada paket wisata yang cocok dengan pencarian kata kunci tersebut. Coba pilih filter "Semua" atau kata kunci lain.</p>
            <button type="button" onclick="resetFilters()" class="mt-4 px-5 py-2 rounded-full bg-wukirsari-primary text-white text-xs font-semibold hover:bg-wukirsari-hover transition-all">
                Reset Filter
            </button>
        </div>

        <!-- Grid 5 Kartu Paket Wisata -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8" id="packages-grid">
                @foreach($packages as $pkg)
                @php
                    $categoryGroup = 'all';
                    if (str_contains($pkg->slug, 'short')) {
                        $categoryGroup = 'short';
                    } elseif (str_contains($pkg->slug, 'medium')) {
                        $categoryGroup = 'medium';
                    } elseif (str_contains($pkg->slug, 'long')) {
                        $categoryGroup = 'long';
                    } elseif (str_contains($pkg->slug, 'sunrise')) {
                        $categoryGroup = 'sunrise';
                    }
                    $routes = $pkg->routes_array;
                @endphp
                <div class="package-card bg-white rounded-3xl overflow-hidden border border-wukirsari-border shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col group"
                     data-slug="{{ $pkg->slug }}"
                     data-name="{{ strtolower($pkg->name) }}"
                     data-category="{{ $categoryGroup }}"
                     data-description="{{ strtolower($pkg->description . ' ' . implode(' ', $routes)) }}">
                    
                    <!-- Cover Image & Badges -->
                    <div class="relative aspect-[16/10] overflow-hidden bg-slate-100">
                        <img src="{{ $pkg->cover_image }}" alt="{{ $pkg->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>

                        <!-- Badge Destinasi & Kategori -->
                        <div class="absolute top-3.5 left-3.5 flex flex-wrap gap-1.5 items-center">
                            <span class="px-3 py-1 rounded-full bg-wukirsari-primary/90 backdrop-blur-md text-white text-[11px] font-bold shadow-md">
                                {{ $pkg->destinations_count ?? count($routes) }} Destinasi
                            </span>
                            @if($pkg->category_badge)
                            <span class="px-3 py-1 rounded-full bg-emerald-700 text-white text-[11px] font-bold shadow-md">
                                {{ $pkg->category_badge }}
                            </span>
                            @endif
                        </div>

                        <!-- Lokasi Tag di bawah foto -->
                        <div class="absolute bottom-3 left-3.5 flex items-center gap-1.5 text-white/90 text-xs">
                            <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span>Sleman, Yogyakarta</span>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                        
                        <div>
                            <!-- Judul Paket -->
                            <div class="flex items-center justify-between gap-2">
                                <h3 class="text-xl font-bold text-merapiText-primary font-display group-hover:text-wukirsari-primary transition-colors">
                                    {{ $pkg->name }}
                                </h3>
                                <span class="px-2.5 py-0.5 rounded-full bg-wukirsari-sage text-wukirsari-primary text-[11px] font-semibold">
                                    Resmi
                                </span>
                            </div>

                            <!-- Metadata Durasi & Kapasitas -->
                            <div class="mt-3 flex items-center gap-4 text-xs text-merapiText-secondary">
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-wukirsari-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="font-medium">{{ $pkg->effective_duration_text }}</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-wukirsari-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                    <span class="font-medium">Max 4 orang</span>
                                </div>
                            </div>

                            <!-- Deskripsi Singkat -->
                            <p class="text-merapiText-secondary text-xs sm:text-sm mt-3 line-clamp-2 leading-relaxed">
                                {{ $pkg->description }}
                            </p>

                            <!-- Daftar Rute Destinasi -->
                            <div class="mt-4 pt-3 border-t border-wukirsari-border/70">
                                <span class="text-[11px] font-bold uppercase tracking-wider text-wukirsari-primary block mb-2">
                                    Rute Perjalanan ({{ count($routes) }} Titik):
                                </span>
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach($routes as $idx => $dest)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-wukirsari-cream text-merapiText-primary text-[11px] font-medium border border-wukirsari-border/80">
                                        <span class="w-1.5 h-1.5 rounded-full bg-wukirsari-primary"></span>
                                        <span>{{ $dest }}</span>
                                    </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Bagian Harga & Tombol Aksi -->
                        <div class="pt-4 border-t border-wukirsari-border space-y-3">
                            <div class="flex items-baseline justify-between">
                                <div>
                                    <span class="text-[11px] text-merapiText-muted block">Harga Resmi</span>
                                    <div class="flex items-baseline gap-1">
                                        <span class="text-2xl font-extrabold text-merapiText-primary font-display">
                                            {{ $pkg->formatted_price }}
                                        </span>
                                        <span class="text-xs text-merapiText-secondary font-medium">/ paket</span>
                                    </div>
                                </div>
                                <span class="text-[11px] font-medium text-wukirsari-primary bg-wukirsari-sage/70 px-2.5 py-1 rounded-full border border-wukirsari-border">
                                    ✓ Termasuk Asuransi
                                </span>
                            </div>

                            <div class="grid grid-cols-2 gap-2 pt-1">
                                <a href="{{ route('packages.show', $pkg->slug) }}" class="py-2.5 px-3 rounded-full border border-wukirsari-border text-merapiText-primary hover:border-wukirsari-primary hover:text-wukirsari-primary hover:bg-wukirsari-sage/30 text-xs font-semibold text-center transition-all flex items-center justify-center gap-1.5">
                                    <span>Lihat Detail</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </a>
                                <a href="{{ route('booking.create', ['package' => $pkg->slug]) }}" class="py-2.5 px-3 rounded-full bg-wukirsari-primary hover:bg-wukirsari-hover text-white text-xs font-semibold text-center transition-all shadow-sm shadow-wukirsari-primary/20 hover:scale-[1.02] active:scale-95 flex items-center justify-center gap-1.5">
                                    <span>Booking</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </div>

    <!-- 4. BANNER INFO FASILITAS & KONTAK BASECAMP -->
    <section class="py-12 bg-white border-t border-wukirsari-border">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Card 1: Fasilitas Lengkap Basecamp -->
                <div class="p-6 rounded-3xl bg-wukirsari-cream border border-wukirsari-border flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-wukirsari-primary text-white flex items-center justify-center shrink-0 shadow-md">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-base font-bold text-merapiText-primary font-display">Fasilitas Lengkap Basecamp</h4>
                        <p class="text-xs text-merapiText-secondary mt-1 leading-relaxed">
                            Area parkir bus & mobil luas, musholla nyaman, toilet bersih, ruang tunggu santai, dan air mineral gratis.
                        </p>
                    </div>
                </div>

                <!-- Card 2: Driver & Armada Terawat -->
                <div class="p-6 rounded-3xl bg-wukirsari-cream border border-wukirsari-border flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-wukirsari-primary text-white flex items-center justify-center shrink-0 shadow-md">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-base font-bold text-merapiText-primary font-display">Armada 4x4 & Pemandu Ahli</h4>
                        <p class="text-xs text-merapiText-secondary mt-1 leading-relaxed">
                            Armada Jeep 4x4 tangguh selalu dicek berkala. Pengemudi lokal paguyuban ramah, bersertifikat, dan paham sejarah Merapi.
                        </p>
                    </div>
                </div>

                <!-- Card 3: Konsultasi & Layanan Cepat -->
                <div class="p-6 rounded-3xl bg-wukirsari-cream border border-wukirsari-border flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-wukirsari-primary text-white flex items-center justify-center shrink-0 shadow-md">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-base font-bold text-merapiText-primary font-display">Bantuan WhatsApp 24 Jam</h4>
                        <p class="text-xs text-merapiText-secondary mt-1 leading-relaxed">
                            Pertanyaan khusus rombongan keluarga atau kantor? Hubungi tim admin loket kami via WhatsApp: <a href="https://wa.me/6281234567890" target="_blank" class="font-bold text-wukirsari-primary hover:underline">+62 812-3456-7890</a>.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- 5. NATURE CALLOUT BANNER (PERSIS GAMBAR REFERENSI KANAN) -->
    <section class="py-12 bg-wukirsari-cream">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-wukirsari-primary via-wukirsari-hover to-wukirsari-dark text-white p-8 sm:p-12 shadow-lg">
                <!-- Background decorative mountains pattern -->
                <div class="absolute inset-0 opacity-15 pointer-events-none mix-blend-overlay">
                    <img src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1600&q=80" alt="Mountain Texture" class="w-full h-full object-cover">
                </div>

                <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6 text-center md:text-left">
                    <div class="space-y-2">
                        <span class="text-xs font-accent italic text-wukirsari-sage tracking-wider">Wisata Jeep Merapi Terpercaya</span>
                        <h3 class="text-2xl sm:text-3xl font-extrabold font-accent italic tracking-wide text-white">
                            "Jelajahi keindahan alam bersama kami, Jeep Wukirsari!"
                        </h3>
                        <p class="text-xs sm:text-sm text-white/80 max-w-xl">
                            Dapatkan jaminan pengalaman offroad yang aman, nyaman, dan berkesan bersama keluarga dan sahabat.
                        </p>
                    </div>

                    <a href="{{ route('booking.create') }}" class="shrink-0 px-8 py-3.5 rounded-full bg-white text-wukirsari-primary hover:bg-wukirsari-sage font-bold text-sm shadow-lg hover:scale-105 active:scale-95 transition-all">
                        Booking Sekarang →
                    </a>
                </div>
            </div>
        </div>
    </section>

<!-- JAVASCRIPT UNTUK FILTERING INSTAN & PENCARIAN -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterButtons = document.querySelectorAll('.filter-btn');
        const searchInput = document.getElementById('package-search-input');
        const cards = document.querySelectorAll('.package-card');
        const visibleCountSpan = document.getElementById('visible-count');
        const emptyState = document.getElementById('empty-state');
        const packagesGrid = document.getElementById('packages-grid');

        let currentFilter = 'all';
        let currentSearch = '';

        function updateDisplay() {
            let visibleCount = 0;

            cards.forEach(card => {
                const category = card.getAttribute('data-category');
                const name = card.getAttribute('data-name') || '';
                const description = card.getAttribute('data-description') || '';

                const matchesFilter = (currentFilter === 'all') || (category === currentFilter);
                const matchesSearch = !currentSearch || name.includes(currentSearch) || description.includes(currentSearch);

                if (matchesFilter && matchesSearch) {
                    card.classList.remove('hidden');
                    visibleCount++;
                } else {
                    card.classList.add('hidden');
                }
            });

            if (visibleCountSpan) {
                visibleCountSpan.textContent = visibleCount;
            }

            if (visibleCount === 0) {
                emptyState.classList.remove('hidden');
                packagesGrid.classList.add('hidden');
            } else {
                emptyState.classList.add('hidden');
                packagesGrid.classList.remove('hidden');
            }
        }

        // Event listener klik tombol filter
        filterButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                filterButtons.forEach(b => {
                    b.classList.remove('bg-wukirsari-primary', 'text-white', 'shadow-md', 'shadow-wukirsari-primary/20');
                    b.classList.add('bg-wukirsari-sage/70', 'text-merapiText-secondary', 'border', 'border-wukirsari-border');
                });

                this.classList.remove('bg-wukirsari-sage/70', 'text-merapiText-secondary', 'border', 'border-wukirsari-border');
                this.classList.add('bg-wukirsari-primary', 'text-white', 'shadow-md', 'shadow-wukirsari-primary/20');

                currentFilter = this.getAttribute('data-filter');
                updateDisplay();
            });
        });

        // Event listener input pencarian
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                currentSearch = this.value.trim().toLowerCase();
                updateDisplay();
            });
        }

        window.resetFilters = function() {
            if (searchInput) searchInput.value = '';
            currentSearch = '';
            const allBtn = document.querySelector('[data-filter="all"]');
            if (allBtn) allBtn.click();
        };
    });
</script>
@endsection
