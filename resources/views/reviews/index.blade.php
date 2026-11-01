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

        <!-- Filter Bar & CTA Tulis Ulasan -->
        <div class="bg-white p-4 sm:p-5 rounded-3xl border border-wukirsari-border shadow-xs flex flex-col md:flex-row items-center justify-between gap-4">
            <!-- Filter Bintang & Paket -->
            <div class="flex flex-wrap items-center gap-2 w-full md:w-auto">
                <span class="text-xs font-bold text-merapiText-muted uppercase tracking-wider mr-1">Filter:</span>
                
                <a href="{{ route('reviews.index', array_filter(['package' => $packageFilter])) }}" class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition-all {{ empty($ratingFilter) ? 'bg-wukirsari-primary text-white shadow-xs' : 'bg-slate-100 text-merapiText-secondary hover:bg-slate-200' }}">
                    Semua Bintang
                </a>

                @for($star = 5; $star >= 3; $star--)
                <a href="{{ route('reviews.index', array_filter(['rating' => $star, 'package' => $packageFilter])) }}" class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition-all flex items-center gap-1 {{ (string)$ratingFilter === (string)$star ? 'bg-wukirsari-primary text-white shadow-xs' : 'bg-slate-100 text-merapiText-secondary hover:bg-slate-200' }}">
                    <span>★ {{ $star }}</span>
                </a>
                @endfor

                <!-- Dropdown Pilih Paket -->
                <form method="GET" action="{{ route('reviews.index') }}" class="inline-flex items-center ml-1">
                    @if($ratingFilter)
                    <input type="hidden" name="rating" value="{{ $ratingFilter }}">
                    @endif
                    <select name="package" onchange="this.form.submit()" class="px-3 py-1.5 rounded-full text-xs font-semibold bg-slate-100 text-merapiText-primary border border-wukirsari-border focus:outline-none focus:border-wukirsari-primary cursor-pointer">
                        <option value="">Semua Paket Wisata</option>
                        @foreach($packages as $pkg)
                        <option value="{{ $pkg->slug }}" {{ $packageFilter === $pkg->slug ? 'selected' : '' }}>
                            {{ $pkg->name }}
                        </option>
                        @endforeach
                    </select>
                </form>

                @if($packageFilter || $ratingFilter)
                <a href="{{ route('reviews.index') }}" class="px-3 py-1 rounded-full text-xs font-bold text-rose-600 hover:bg-rose-50 border border-rose-200 transition-colors" title="Reset semua filter">
                    Reset Filter ✕
                </a>
                @endif
            </div>

            <!-- Tombol Tulis Ulasan -->
            <div class="w-full md:w-auto flex justify-stretch sm:justify-end">
                @auth
                <a href="{{ route('home') }}#tulis-ulasan" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-full bg-wukirsari-primary hover:bg-wukirsari-hover text-white text-xs font-bold shadow-sm shadow-wukirsari-primary/20 transition-all">
                    <span>Tulis Ulasan Saya</span>
                </a>
                @else
                <a href="{{ route('login') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-full border border-wukirsari-primary text-wukirsari-primary hover:bg-wukirsari-sage text-xs font-bold transition-all">
                    <span>Masuk untuk Tulis Ulasan</span>
                </a>
                @endauth
            </div>
        </div>

        <!-- Grid Kartu Ulasan Wisatawan Lain -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($reviews as $review)
            <div class="bg-white p-6 rounded-3xl border border-wukirsari-border shadow-xs hover:shadow-md transition-all flex flex-col justify-between space-y-4">
                <div class="space-y-4">
                    <!-- User Header -->
                    <div class="flex items-center justify-between border-b border-wukirsari-border/70 pb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-2xl bg-wukirsari-sage text-wukirsari-primary flex items-center justify-center font-bold text-sm shrink-0 border border-wukirsari-border font-display">
                                {{ strtoupper(substr($review->user->name ?? 'W', 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <h3 class="text-sm font-bold text-merapiText-primary font-display truncate">
                                    {{ $review->user->name ?? 'Wisatawan' }}
                                </h3>
                                <span class="text-xs text-merapiText-muted block truncate">
                                    {{ $review->city ?? 'Indonesia' }}
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <div class="flex items-center text-yellow-500 text-xs font-bold bg-yellow-50 px-2.5 py-1 rounded-full border border-yellow-200">
                                <span>★</span>
                                <span class="ml-1 text-slate-800">{{ $review->rating }}.0</span>
                            </div>

                            @if(Auth::check() && (Auth::id() === $review->user_id || Auth::user()->isAdmin()))
                            <div class="flex items-center gap-1">
                                <button type="button" 
                                    onclick="handleEditReviewClick(this)"
                                    data-id="{{ $review->id }}"
                                    data-rating="{{ $review->rating }}"
                                    data-city="{{ $review->city }}"
                                    data-comment="{{ $review->comment }}"
                                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-wukirsari-sage/70 hover:bg-wukirsari-sage text-wukirsari-primary text-[11px] font-bold border border-wukirsari-border transition-all cursor-pointer" 
                                    title="Edit Ulasan">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    <span>Edit</span>
                                </button>
                                <form action="{{ route('reviews.destroy', $review->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ulasan ini?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                        class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-rose-50 hover:bg-rose-100 text-rose-600 text-[11px] font-bold border border-rose-200 transition-all cursor-pointer" 
                                        title="Hapus Ulasan">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        <span>Delete</span>
                                    </button>
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
                    <span class="text-emerald-700 font-semibold flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        Wisatawan Terverifikasi
                    </span>
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
        @if($reviews->hasPages())
        <div class="pt-4 flex justify-center">
            {{ $reviews->links() }}
        </div>
        @endif

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
            <a href="{{ route('booking.create') }}" class="px-7 py-3 rounded-full bg-wukirsari-primary hover:bg-wukirsari-hover text-white text-xs font-bold shadow-md shadow-wukirsari-primary/20 transition-all whitespace-nowrap">
                Booking Jeep Sekarang →
            </a>
        </div>

    </div>
</div>
@endsection
