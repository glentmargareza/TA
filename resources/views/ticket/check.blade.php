@extends('layouts.app')

@section('title', 'Cek Status Tiket | Jeep Wukirsari')

@section('content')
<div class="py-12 bg-wukirsari-cream min-h-screen">
    <div class="max-w-2xl mx-auto px-4 sm:px-6">
        
        <div class="text-center space-y-2 mb-8">
            <span class="text-xs font-bold uppercase tracking-wider text-wukirsari-primary font-display block">
                PELACAKAN TIKET ONLINE
            </span>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-merapiText-primary font-display">
                Cek Status Pemesanan Jeep
            </h1>
            <p class="text-merapiText-secondary text-sm">
                Masukkan kode booking (contoh: <code>WKR-123456</code>) atau nomor WhatsApp pemesan untuk melihat status dan e-tiket Anda.
            </p>
        </div>

        <form action="{{ route('ticket.check') }}" method="GET" class="flex gap-2 mb-8">
            <input type="text" name="code" value="{{ $code ?? '' }}" required placeholder="Masukkan Kode Booking / No WhatsApp..." class="flex-1 px-4 py-3 rounded-full border border-wukirsari-border bg-white text-sm focus:outline-none focus:border-wukirsari-primary shadow-xs">
            <button type="submit" class="px-6 py-3 rounded-full bg-wukirsari-primary hover:bg-wukirsari-hover text-white text-sm font-semibold flex items-center gap-2 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <span>Cari Tiket</span>
            </button>
        </form>

        @if($hasSearched && $booking)
        <div class="bg-white rounded-3xl border border-wukirsari-border p-6 sm:p-8 shadow-sm space-y-6">
            <div class="flex items-center justify-between border-b border-wukirsari-border pb-4">
                <div>
                    <span class="text-xs text-merapiText-muted block">Kode Reservasi</span>
                    <span class="text-lg font-mono font-bold text-wukirsari-primary">{{ $booking->booking_code }}</span>
                </div>
                @if($booking->booking_status === 'FULL_PAID')
                <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                    LUNAS (100%)
                </span>
                @elseif($booking->booking_status === 'DP_PAID')
                <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                    UANG MUKA TERVERIFIKASI (DP)
                </span>
                @elseif($booking->booking_status === 'PENDING')
                <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-900 border border-amber-300">
                    MENUNGGU PEMBAYARAN
                </span>
                @else
                <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800">
                    {{ $booking->booking_status }}
                </span>
                @endif
            </div>

            <div class="grid grid-cols-2 gap-4 text-xs sm:text-sm">
                <div>
                    <span class="text-merapiText-muted block">Nama Pemesan:</span>
                    <span class="font-bold text-merapiText-primary">{{ $booking->customer_name }}</span>
                </div>
                <div>
                    <span class="text-merapiText-muted block">Paket Wisata:</span>
                    <span class="font-bold text-merapiText-primary">Paket {{ $booking->package->name }}</span>
                </div>
                <div>
                    <span class="text-merapiText-muted block">Tanggal & Jam:</span>
                    <span class="font-bold text-merapiText-primary">{{ $booking->trip_date->format('d M Y') }} ({{ $booking->slot->slot_label }})</span>
                </div>
                <div>
                    <span class="text-merapiText-muted block">Peserta & Armada:</span>
                    <span class="font-bold text-merapiText-primary">{{ $booking->passenger_count }} Orang ({{ $booking->jeep_quantity }} Jeep)</span>
                </div>
            </div>

            <div class="p-4 rounded-2xl bg-wukirsari-sage/40 border border-wukirsari-border flex justify-between items-center text-xs sm:text-sm">
                <div>
                    <span class="text-merapiText-secondary block">Jumlah Terbayar</span>
                    <span class="font-bold text-wukirsari-primary">Rp {{ number_format($booking->paid_amount, 0, ',', '.') }}</span>
                </div>
                @if($booking->remaining_amount > 0)
                <div class="text-right">
                    <span class="text-amber-800 block">Sisa Bayar di Loket</span>
                    <span class="font-extrabold text-amber-800 text-base">Rp {{ number_format($booking->remaining_amount, 0, ',', '.') }}</span>
                </div>
                @endif
            </div>

            <div class="text-center pt-2">
                @if(in_array($booking->booking_status, ['FULL_PAID', 'DP_PAID']))
                <a href="{{ route('booking.success', $booking->booking_code) }}" class="inline-block px-6 py-2.5 rounded-full bg-wukirsari-primary hover:bg-wukirsari-hover text-white text-xs font-semibold transition-colors">
                    {{ $booking->booking_status === 'FULL_PAID' ? 'Buka E-Tiket Lengkap →' : 'Buka E-Tiket DP →' }}
                </a>
                @elseif($booking->booking_status === 'PENDING')
                <a href="{{ route('booking.payment', $booking->booking_code) }}" class="inline-block px-6 py-2.5 rounded-full bg-amber-600 hover:bg-amber-700 text-white text-xs font-semibold transition-colors">
                    Selesaikan Pembayaran Sekarang →
                </a>
                @else
                <a href="{{ route('booking.create') }}" class="inline-block px-6 py-2.5 rounded-full bg-gray-600 hover:bg-gray-700 text-white text-xs font-semibold transition-colors">
                    Buat Reservasi Baru →
                </a>
                @endif
            </div>
        </div>
        @elseif($hasSearched && !$booking)
        <div class="bg-white rounded-3xl border border-wukirsari-border p-8 text-center space-y-3 shadow-xs">
            <span class="text-3xl block">⚠️</span>
            <h3 class="text-base font-bold text-merapiText-primary">Tiket Tidak Ditemukan</h3>
            <p class="text-xs sm:text-sm text-merapiText-secondary max-w-sm mx-auto">
                Pastikan kode booking atau nomor WhatsApp yang Anda masukkan sudah benar dan telah tersimpan di sistem.
            </p>
        </div>
        @endif

    </div>
</div>
@endsection
