@extends('layouts.app')

@section('title', 'Profil Akun Wisatawan | Jeep Wukirsari')

@section('content')
<div class="py-10 bg-wukirsari-cream min-h-[calc(100vh-80px)]">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        <!-- Header Profile Card -->
        <div class="bg-white rounded-3xl border border-wukirsari-border p-6 sm:p-8 shadow-sm flex flex-col sm:flex-row items-center sm:items-start justify-between gap-6">
            <div class="flex flex-col sm:flex-row items-center gap-5 text-center sm:text-left">
                <div class="w-20 h-20 rounded-3xl bg-wukirsari-sage text-wukirsari-primary flex items-center justify-center text-3xl font-extrabold shadow-sm font-display">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="space-y-1">
                    <div class="flex items-center justify-center sm:justify-start gap-2">
                        <h1 class="text-xl sm:text-2xl font-bold text-merapiText-primary font-display">
                            {{ $user->name }}
                        </h1>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-wukirsari-sage text-wukirsari-primary">
                            Wisatawan
                        </span>
                    </div>
                    <p class="text-xs text-merapiText-secondary flex items-center justify-center sm:justify-start gap-1.5">
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-wukirsari-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            {{ $user->email }}
                        </span>
                        @if($user->phone)
                        <span class="flex items-center gap-1">
                            • <svg class="w-3.5 h-3.5 text-wukirsari-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            {{ $user->phone }}
                        </span>
                        @endif
                    </p>
                    <p class="text-[11px] text-merapiText-muted">
                        Bergabung sejak: {{ $user->created_at->format('d M Y') }}
                    </p>
                </div>
            </div>

            <!-- Action Logout & Booking -->
            <div class="flex items-center gap-3 w-full sm:w-auto justify-center sm:justify-end">
                <a href="{{ route('booking.create') }}" class="px-5 py-2.5 rounded-full bg-wukirsari-primary hover:bg-wukirsari-hover text-white text-xs font-semibold shadow-xs transition-all flex items-center gap-1.5">
                    <span>+ Booking Baru</span>
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2.5 rounded-full border border-rose-200 text-rose-700 hover:bg-rose-50 text-xs font-semibold transition-all">
                        Keluar
                    </button>
                </form>
            </div>
        </div>

        <!-- Section Riwayat Pemesanan (My Bookings) -->
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold text-merapiText-primary font-display flex items-center gap-2">
                    <span>Riwayat Tiket & Pesanan Saya</span>
                    <span class="text-xs font-semibold px-2 py-0.5 bg-wukirsari-sage text-wukirsari-primary rounded-full">
                        {{ $myBookings->count() }}
                    </span>
                </h2>
                <a href="{{ route('ticket.check') }}" class="text-xs font-semibold text-wukirsari-primary hover:underline">
                    Lacak Tiket dengan Kode →
                </a>
            </div>

            @if($myBookings->isEmpty())
            <div class="bg-white rounded-3xl border border-wukirsari-border p-8 text-center space-y-4">
                <div class="w-16 h-16 rounded-full bg-wukirsari-sage/50 text-wukirsari-primary flex items-center justify-center mx-auto">
                    <svg class="w-8 h-8 text-wukirsari-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1"/></svg>
                </div>
                <div class="space-y-1">
                    <h3 class="text-base font-bold text-merapiText-primary font-display">Belum Ada Riwayat Pemesanan</h3>
                    <p class="text-xs text-merapiText-muted max-w-md mx-auto">
                        Anda belum memiliki tiket tour jeep. Pilih paket petualangan seru lereng Merapi sekarang dan nikmati liburan tak terlupakan!
                    </p>
                </div>
                <a href="{{ route('booking.create') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-wukirsari-primary text-white text-xs font-bold shadow-md shadow-wukirsari-primary/20 hover:scale-102 transition-all">
                    <span>Pilih Paket & Booking Sekarang</span>
                </a>
            </div>
            @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($myBookings as $booking)
                <div class="bg-white rounded-3xl border border-wukirsari-border p-5 shadow-xs space-y-4 hover:border-wukirsari-primary/50 transition-all">
                    <div class="flex items-start justify-between border-b border-wukirsari-border pb-3">
                        <div>
                            <span class="text-[10px] uppercase font-extrabold tracking-wider text-merapiText-muted">Kode Booking</span>
                            <h3 class="text-base font-bold font-mono text-wukirsari-primary">
                                {{ $booking->booking_code }}
                            </h3>
                        </div>
                        <div>
                            @if($booking->booking_status === 'FULL_PAID')
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-600 text-white">
                                <span>●</span> Lunas (100%)
                            </span>
                            @elseif($booking->booking_status === 'DP_PAID')
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-wukirsari-sage text-wukirsari-primary">
                                <span>●</span> DP (Sisa Rp {{ number_format($booking->remaining_amount, 0, ',', '.') }})
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700">
                                {{ $booking->booking_status }}
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="space-y-1.5 text-xs">
                        <div class="flex justify-between">
                            <span class="text-merapiText-muted">Paket Wisata:</span>
                            <span class="font-bold text-merapiText-primary">{{ $booking->package->name ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-merapiText-muted">Jadwal Tour:</span>
                            <span class="font-semibold text-merapiText-primary">{{ \Carbon\Carbon::parse($booking->trip_date)->translatedFormat('d M Y') }} • {{ $booking->slot->slot_label ?? '' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-merapiText-muted">Jumlah Armada:</span>
                            <span class="font-semibold text-merapiText-primary">{{ $booking->jeep_quantity }} Jeep ({{ $booking->passenger_count }} Orang)</span>
                        </div>
                        <div class="flex justify-between pt-1 border-t border-dashed border-wukirsari-border">
                            <span class="text-merapiText-muted">Total Tagihan:</span>
                            <span class="font-bold text-merapiText-primary">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="pt-2 flex items-center justify-between">
                        <span class="text-[11px] text-merapiText-muted">
                            {{ $booking->created_at->diffForHumans() }}
                        </span>
                        <a href="{{ route('booking.success', $booking->booking_code) }}" class="inline-flex items-center gap-1 px-4 py-2 rounded-xl bg-wukirsari-sage text-wukirsari-primary text-xs font-bold hover:bg-wukirsari-primary hover:text-white transition-all">
                            <span>Buka E-Tiket →</span>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        <!-- Section Ulasan & Testimoni Saya -->
        <div class="space-y-4 pt-4 border-t border-wukirsari-border">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold text-merapiText-primary font-display flex items-center gap-2">
                    <span>⭐ Ulasan & Testimoni Saya</span>
                    <span class="text-xs font-semibold px-2 py-0.5 bg-wukirsari-sage text-wukirsari-primary rounded-full">
                        {{ $myReviews->count() }}
                    </span>
                </h2>
                <a href="{{ route('home') }}#tulis-ulasan" class="text-xs font-semibold text-wukirsari-primary hover:underline">
                    + Tulis Ulasan Baru →
                </a>
            </div>

            @if($myReviews->isEmpty())
            <div class="bg-white rounded-3xl border border-wukirsari-border p-8 text-center space-y-2">
                <p class="text-xs text-merapiText-muted">
                    Anda belum menuliskan ulasan. Bagikan cerita keseruan Anda setelah trip offroad lereng Merapi!
                </p>
                <a href="{{ route('home') }}#tulis-ulasan" class="inline-block text-xs font-bold text-wukirsari-primary hover:underline">
                    Tulis Ulasan Sekarang →
                </a>
            </div>
            @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($myReviews as $rev)
                <div class="bg-white rounded-3xl border border-wukirsari-border p-5 shadow-xs space-y-3 hover:border-wukirsari-primary/40 transition-all flex flex-col justify-between">
                    <div class="space-y-3">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <span class="text-xs font-bold text-merapiText-primary font-display block">
                                    {{ $rev->package->name ?? 'Tour Jeep Wukirsari' }}
                                </span>
                                <div class="flex items-center gap-1 text-yellow-400 text-xs mt-0.5">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $rev->rating) ★ @else ☆ @endif
                                    @endfor
                                    <span class="text-[11px] font-bold text-slate-700 ml-1">{{ $rev->rating }}.0</span>
                                </div>
                            </div>

                            <!-- Tombol Edit & Delete -->
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
                        </div>

                        <p class="text-xs sm:text-sm text-merapiText-secondary italic leading-relaxed">
                            "{{ $rev->comment }}"
                        </p>
                    </div>

                    <div class="pt-2 border-t border-wukirsari-border text-[10px] text-merapiText-muted flex items-center justify-between">
                        <span>Kota: {{ $rev->city ?? 'Wisatawan' }}</span>
                        <span>{{ $rev->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

    </div>
</div>
@endsection
