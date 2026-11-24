@extends('layouts.app')

@section('title', 'Ulasan & Testimoni Wisatawan | Jeep Wukirsari Merapi')

@section('content')
<div class="py-10 sm:py-14 bg-wukirsari-cream min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

        <!-- Header Banner & Summary Stats -->
        <div class="bg-gradient-to-r from-wukirsari-dark via-wukirsari-primary to-wukirsari-hover text-white rounded-3xl p-8 sm:p-12 shadow-xl relative overflow-hidden">
            <div class="absolute -right-10 -bottom-10 w-72 h-72 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                <div class="lg:col-span-8 space-y-3">
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-white/15 text-emerald-300 text-xs font-semibold backdrop-blur-sm">
                        <span>Ulasan & Testimoni Asli Wisatawan</span>
                    </span>
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold font-display tracking-tight text-white">
                        Apa Kata Wisatawan Lain?
                    </h1>
                    <p class="text-white/85 text-sm sm:text-base max-w-2xl leading-relaxed">
                        Simak pengalaman seru ribuan wisatawan yang telah merasakan sensasi petualangan offroad lereng Gunung Merapi bersama Jeep Wukirsari.
                    </p>
                </div>

                <div class="lg:col-span-4 flex flex-col items-start lg:items-end justify-center">
                    <div class="bg-white/10 backdrop-blur-md border border-white/20 p-5 rounded-3xl text-center min-w-[220px]">
                        <div class="text-3xl sm:text-4xl font-extrabold font-display text-emerald-300">
                            {{ number_format($avgRating, 1) }} <span class="text-lg text-white/70">/ 5.0</span>
                        </div>
                        <div class="flex items-center justify-center gap-1 text-yellow-400 my-1.5">
                            @for($i = 0; $i < 5; $i++)
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>
                        <p class="text-xs text-white/80 font-medium">
                            Berdasarkan {{ $totalReviews }} ulasan terverifikasi
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter Bar & CTA Tulis Ulasan (Menggunakan Standar Komponen x-card-filter) --}}
        <x-card-filter 
            :action="route('reviews.index')" 
            :resetUrl="route('reviews.index')"
            :grid="false"
            :liveSearch="true"
            tableTarget="#reviews-grid-container"
            paginationTarget="#reviews-pagination-container"
            class="rounded-3xl border-wukirsari-border bg-white shadow-xs p-4 sm:p-5 mb-0">

            <!-- Filter Bintang (Radio Pills) -->
            <div class="flex items-center gap-1.5 overflow-x-auto pb-1 sm:pb-0 w-full sm:w-auto text-xs no-scrollbar">
                <span class="text-xs font-bold text-merapiText-muted uppercase tracking-wider mr-1 shrink-0">Filter:</span>
                
                <label class="cursor-pointer shrink-0">
                    <input type="radio" name="rating" value="" class="sr-only peer" {{ empty($ratingFilter) ? 'checked' : '' }}>
                    <span class="px-3.5 py-1.5 rounded-full text-xs font-semibold inline-block transition-all bg-slate-100 text-merapiText-secondary hover:bg-slate-200 peer-checked:bg-wukirsari-primary peer-checked:text-white peer-checked:shadow-xs peer-checked:font-bold">
                        Semua Bintang
                    </span>
                </label>

                @for($star = 5; $star >= 1; $star--)
                <label class="cursor-pointer shrink-0">
                    <input type="radio" name="rating" value="{{ $star }}" class="sr-only peer" {{ (string)$ratingFilter === (string)$star ? 'checked' : '' }}>
                    <span class="inline-flex items-center gap-1 px-3.5 py-1.5 rounded-full text-xs font-semibold transition-all bg-slate-100 text-merapiText-secondary hover:bg-slate-200 peer-checked:bg-wukirsari-primary peer-checked:text-white peer-checked:shadow-xs peer-checked:font-bold">
                        <x-icon name="star" class="w-3.5 h-3.5 text-amber-500 peer-checked:text-amber-300" />
                        <span>{{ $star }}</span>
                    </span>
                </label>
                @endfor
            </div>

            <!-- Dropdown Pilih Paket -->
            <div class="shrink-0">
                <select name="package" class="px-3.5 py-1.5 rounded-full text-xs font-semibold bg-slate-100 text-merapiText-primary border border-wukirsari-border focus:outline-none focus:border-wukirsari-primary cursor-pointer shadow-2xs">
                    <option value="">Semua Paket Wisata</option>
                    @foreach($packages as $pkg)
                    <option value="{{ $pkg->slug }}" {{ $packageFilter === $pkg->slug ? 'selected' : '' }}>
                        {{ $pkg->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Input Pencarian -->
            <div class="flex-1 sm:w-56 min-w-[180px]">
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari ulasan, kota, nama..." class="w-full px-3.5 py-1.5 rounded-full border border-wukirsari-border text-xs focus:outline-none focus:border-wukirsari-primary bg-slate-50 text-merapiText-primary shadow-2xs">
            </div>

            <!-- Slot Aksi & CTA Tulis Ulasan -->
            <x-slot:actions>
                <div class="flex items-center gap-2 ml-auto w-full sm:w-auto justify-between sm:justify-end">
                    <button type="submit" class="py-1.5 px-3.5 rounded-full bg-gradient-to-r from-emerald-700 via-[#183c21] to-[#102b17] hover:from-emerald-600 hover:to-[#183c21] text-white text-xs font-bold transition-all shadow-xs hover:shadow active:scale-95 cursor-pointer inline-flex items-center justify-center gap-1.5 whitespace-nowrap">
                        <x-icon name="search" class="w-3.5 h-3.5 shrink-0" />
                        <span>Cari</span>
                    </button>

                    @php
                        $isFiltered = !empty($ratingFilter) || !empty($packageFilter) || !empty($search);
                    @endphp
                    <a href="{{ route('reviews.index') }}"
                       data-role="clear-filter"
                       class="py-1.5 px-3 rounded-full border border-slate-200 bg-white hover:bg-rose-50 text-slate-600 hover:text-rose-600 text-xs font-bold transition-colors inline-flex items-center justify-center gap-1.5 whitespace-nowrap shadow-2xs {{ $isFiltered ? '' : 'hidden' }}"
                       title="Reset filter pencarian">
                        <x-icon name="x-mark" class="w-3.5 h-3.5 shrink-0" />
                        <span>Reset</span>
                    </a>

                    @auth
                    <button type="button" onclick="openCreateReviewModal()" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2 rounded-full bg-wukirsari-primary hover:bg-wukirsari-hover text-white text-xs font-bold shadow-xs shadow-wukirsari-primary/20 transition-all cursor-pointer whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>Tulis Ulasan Saya</span>
                    </button>
                    @else
                    <a href="{{ route('login', ['redirect' => route('reviews.index', ['action' => 'write'])]) }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2 rounded-full border border-wukirsari-primary text-wukirsari-primary hover:bg-wukirsari-sage text-xs font-bold transition-all whitespace-nowrap">
                        <span>Masuk untuk Tulis Ulasan</span>
                    </a>
                    @endauth
                </div>
            </x-slot:actions>
        </x-card-filter>

        <!-- Grid Kartu Ulasan Wisatawan Lain -->
        <div id="reviews-grid-container" data-table-container="true" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($reviews as $review)
            <div class="bg-white p-6 rounded-3xl border border-wukirsari-border shadow-xs hover:shadow-md transition-all flex flex-col justify-between space-y-4">
                <div class="space-y-4">
                    <!-- User Header -->
                    <div class="flex items-center justify-between border-b border-wukirsari-border/70 pb-3 gap-2">
                        <div class="flex items-center gap-2.5 sm:gap-3 min-w-0 flex-1">
                            <div class="w-10 h-10 rounded-2xl bg-wukirsari-sage text-wukirsari-primary flex items-center justify-center font-bold text-sm shrink-0 border border-wukirsari-border font-display">
                                {{ strtoupper(substr($review->user->name ?? 'W', 0, 1)) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3 class="text-sm font-bold text-merapiText-primary font-display truncate" title="{{ $review->user->name ?? 'Wisatawan' }}">
                                    {{ $review->user->name ?? 'Wisatawan' }}
                                </h3>
                                <span class="text-xs text-merapiText-muted block truncate">
                                    {{ $review->city ?: 'Wisatawan' }}
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center gap-1.5 shrink-0">
                            <!-- Rating Badge -->
                            <div class="flex items-center gap-1 text-amber-500 text-xs font-bold bg-amber-50/80 px-2.5 py-1 rounded-full border border-amber-200 shadow-2xs">
                                <x-icon name="star" class="w-3.5 h-3.5 text-amber-500 shrink-0" />
                                <span class="text-slate-800">{{ $review->rating }}.0</span>
                            </div>

                            @if(Auth::check() && (Auth::id() === $review->user_id || Auth::user()->isAdmin()))
                            <!-- Aksi Khusus Pemilik / Admin (Icon-only dari Komponen) -->
                            <div class="flex items-center gap-1 ml-1 pl-1.5 border-l border-slate-200">
                                <x-action-button 
                                    type="edit" 
                                    onclick="handleEditReviewClick(this)"
                                    data-id="{{ $review->id }}"
                                    data-rating="{{ $review->rating }}"
                                    data-city="{{ $review->city }}"
                                    data-comment="{{ $review->comment }}"
                                    title="Edit Ulasan" />

                                <form action="{{ route('reviews.destroy', $review->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ulasan ini?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <x-action-button type="delete" as="submit" title="Hapus Ulasan" />
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Label Paket Wisata -->
                    @if($review->package)
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-lg bg-slate-100 text-[11px] font-semibold text-wukirsari-primary">
                        <svg class="w-3.5 h-3.5 text-wukirsari-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1"/></svg>
                        <span>{{ $review->package->name }}</span>
                    </div>
                    @endif

                    <!-- Isi Komentar Review -->
                    <p class="text-xs sm:text-sm text-merapiText-secondary leading-relaxed italic">
                        "{{ $review->comment }}"
                    </p>
                </div>

                <div class="pt-3 border-t border-wukirsari-border flex items-center justify-between text-[11px] text-merapiText-muted">
                    @php
                        $isVerified = $review->user && in_array($review->user->email, $verifiedEmails ?? []);
                    @endphp
                    @if($isVerified)
                    <span class="text-emerald-700 font-semibold flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        Wisatawan Terverifikasi
                    </span>
                    @else
                    <span class="text-slate-500 font-medium flex items-center gap-1">
                        Wisatawan Jeep Wukirsari
                    </span>
                    @endif
                    <span>{{ $review->created_at->diffForHumans() }}</span>
                </div>
            </div>
            @empty
            <div class="col-span-full bg-white p-12 rounded-3xl border border-wukirsari-border text-center space-y-3">
                <div class="w-12 h-12 mx-auto rounded-2xl bg-wukirsari-sage text-wukirsari-primary flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <h3 class="text-base font-bold text-merapiText-primary font-display">Tidak Ada Ulasan yang Sesuai</h3>
                <p class="text-xs text-merapiText-muted max-w-md mx-auto">
                    Belum ditemukan ulasan dengan filter yang dipilih. Silakan reset filter untuk melihat semua ulasan wisatawan lain.
                </p>
                <a href="{{ route('reviews.index') }}" class="inline-block px-5 py-2 rounded-full bg-wukirsari-primary text-white text-xs font-semibold">
                    Tampilkan Semua Ulasan
                </a>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div id="reviews-pagination-container" data-pagination-container="true" class="pt-4 flex justify-center">
            @if($reviews->hasPages())
                {{ $reviews->links() }}
            @endif
        </div>

        <!-- Floating Box Kembali ke Booking -->
        <div class="p-8 rounded-3xl bg-white border border-wukirsari-border shadow-xs flex flex-col sm:flex-row items-center justify-between gap-6">
            <div class="space-y-1 text-center sm:text-left">
                <h3 class="text-base sm:text-lg font-bold text-merapiText-primary font-display">
                    Tertarik Menikmati Keseruan Seperti Mereka?
                </h3>
                <p class="text-xs text-merapiText-secondary">
                    Pesan armada jeep Anda sekarang sebelum kuota sesi tour hari ini penuh.
                </p>
            </div>
            <a href="{{ route('booking.create') }}" class="px-7 py-3 rounded-full bg-wukirsari-primary hover:bg-wukirsari-hover text-white text-xs font-bold shadow-md shadow-wukirsari-primary/20 transition-all whitespace-nowrap inline-flex items-center gap-2">
                <span>Booking Jeep Sekarang</span>
                <x-icon name="arrow-right" class="w-4 h-4 shrink-0" />
            </a>
        </div>

    </div>
</div>

@auth
<!-- MODAL TULIS ULASAN BARU -->
<div id="createReviewModal" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-wukirsari-border relative transition-all" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between pb-4 border-b border-wukirsari-border">
            <div class="flex items-center gap-2.5">
                <div class="w-10 h-10 rounded-2xl bg-wukirsari-sage text-wukirsari-primary flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-merapiText-primary font-display">Tulis Ulasan & Pengalaman</h3>
                    <p class="text-xs text-merapiText-muted">Bagikan cerita keseruan trip offroad Anda di Merapi</p>
                </div>
            </div>
            <button type="button" onclick="closeCreateReviewModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-merapiText-secondary flex items-center justify-center transition-colors cursor-pointer" aria-label="Tutup Modal">
                <x-icon name="x-mark" class="w-4 h-4" />
            </button>
        </div>

        <form id="createReviewForm" method="POST" action="{{ route('reviews.store') }}" class="space-y-4 pt-4" onsubmit="handleCreateReviewSubmit(event)">
            @csrf

            <!-- Pilihan Paket Wisata -->
            <div>
                <label class="block text-xs font-semibold text-merapiText-primary mb-1">Paket Wisata yang Diikuti</label>
                <select name="package_id" class="w-full px-3.5 py-2.5 rounded-xl border border-wukirsari-border text-xs focus:outline-none focus:border-wukirsari-primary bg-white cursor-pointer">
                    <option value="">Ulasan Umum Paguyuban Jeep Wukirsari</option>
                    @foreach($packages as $pkg)
                    <option value="{{ $pkg->id }}" {{ ($selectedPackage && $selectedPackage->id === $pkg->id) ? 'selected' : '' }}>
                        {{ $pkg->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Rating Bintang Interaktif -->
            <div>
                <label class="block text-xs font-semibold text-merapiText-primary mb-1.5">Penilaian Bintang *</label>
                <input type="hidden" name="rating" id="createRatingInput" value="{{ old('rating', 5) }}">
                
                <div class="flex items-center gap-2">
                    <div class="flex items-center gap-1" id="createStarContainer">
                        @for($s = 1; $s <= 5; $s++)
                        <button type="button" onclick="setCreateRating({{ $s }})" data-star="{{ $s }}" class="create-star-btn p-1 text-amber-400 hover:text-amber-400 focus:outline-none transition-colors">
                            <svg class="w-6 h-6 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        </button>
                        @endfor
                    </div>
                    <span id="createRatingLabel" class="text-xs font-bold text-wukirsari-primary ml-1">5 Bintang (Sangat Puas)</span>
                </div>
            </div>

            <!-- Kota Asal -->
            <div>
                <label class="block text-xs font-semibold text-merapiText-primary mb-1">Kota / Daerah Asal (Opsional)</label>
                <input type="text" name="city" value="{{ old('city') }}" maxlength="100" placeholder="Contoh: Jakarta / Solo / Bandung" class="w-full px-3.5 py-2.5 rounded-xl border border-wukirsari-border text-xs focus:outline-none focus:border-wukirsari-primary">
            </div>

            <!-- Komentar -->
            <div>
                <div class="flex items-center justify-between mb-1">
                    <label class="block text-xs font-semibold text-merapiText-primary">Cerita Pengalaman Anda *</label>
                    <span id="createCharCounter" class="text-[11px] text-merapiText-muted font-mono">0 / 1000</span>
                </div>
                <textarea name="comment" id="createCommentTextarea" rows="4" required minlength="3" maxlength="1000" oninput="updateCreateCharCount(this)" placeholder="Ceritakan keseruan rute, driver, dan petualangan Anda bersama Jeep Wukirsari..." class="w-full px-3.5 py-2.5 rounded-xl border border-wukirsari-border text-xs focus:outline-none focus:border-wukirsari-primary">{{ old('comment') }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" onclick="closeCreateReviewModal()" class="px-4 py-2.5 rounded-full border border-wukirsari-border text-merapiText-secondary hover:bg-slate-50 text-xs font-semibold transition-all">
                    Batal
                </button>
                <button type="submit" id="btnCreateReviewSubmit" class="px-6 py-2.5 rounded-full bg-wukirsari-primary hover:bg-wukirsari-hover text-white text-xs font-bold transition-all shadow-sm flex items-center gap-1.5">
                    <span>Kirim Ulasan Sekarang</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const createRatingLabels = {
        1: '1 Bintang (Kecewa)',
        2: '2 Bintang (Kurang)',
        3: '3 Bintang (Cukup)',
        4: '4 Bintang (Puas)',
        5: '5 Bintang (Sangat Puas)'
    };

    function setCreateRating(val) {
        val = parseInt(val) || 5;
        const input = document.getElementById('createRatingInput');
        const label = document.getElementById('createRatingLabel');
        if (input) input.value = val;
        if (label) label.textContent = createRatingLabels[val] || (val + ' Bintang');

        const container = document.getElementById('createStarContainer');
        if (container) {
            container.querySelectorAll('.create-star-btn').forEach(btn => {
                const starVal = parseInt(btn.getAttribute('data-star')) || 1;
                if (starVal <= val) {
                    btn.classList.remove('text-slate-300');
                    btn.classList.add('text-amber-400');
                } else {
                    btn.classList.remove('text-amber-400');
                    btn.classList.add('text-slate-300');
                }
            });
        }
    }

    function updateCreateCharCount(textarea) {
        const counter = document.getElementById('createCharCounter');
        if (!counter || !textarea) return;
        const len = textarea.value.length;
        counter.textContent = len + ' / 1000';
        if (len > 950) {
            counter.classList.add('text-rose-600', 'font-bold');
        } else {
            counter.classList.remove('text-rose-600', 'font-bold');
        }
    }

    function openCreateReviewModal() {
        const modal = document.getElementById('createReviewModal');
        if (modal) {
            modal.classList.remove('hidden');
            const textarea = document.getElementById('createCommentTextarea');
            if (textarea) updateCreateCharCount(textarea);
        }
    }

    function closeCreateReviewModal() {
        const modal = document.getElementById('createReviewModal');
        if (modal) modal.classList.add('hidden');
    }

    function handleCreateReviewSubmit(e) {
        const btn = document.getElementById('btnCreateReviewSubmit');
        if (btn) {
            btn.disabled = true;
            btn.classList.add('opacity-75', 'cursor-not-allowed');
            btn.innerHTML = '<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Mengirimkan...';
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const createModal = document.getElementById('createReviewModal');
        if (createModal) {
            createModal.addEventListener('click', function(e) {
                if (e.target === createModal) closeCreateReviewModal();
            });
        }

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeCreateReviewModal();
        });

        // Auto-open modal jika URL berisi ?action=write
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('action') === 'write') {
            openCreateReviewModal();
        }
    });
</script>
@endauth
@endsection
