@extends('layouts.app')

@section('title', 'Cek Status Tiket | Jeep Wukirsari')

@section('content')
<div class="py-8 sm:py-12 bg-wukirsari-cream min-h-screen">
    <div class="max-w-xl mx-auto px-4 sm:px-6 space-y-6">
        
        {{-- Header Section --}}
        <div class="text-center space-y-2">
            <span class="text-[11px] font-bold uppercase tracking-wider text-wukirsari-primary font-display block">
                Pelacakan Tiket Online
            </span>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-merapiText-primary font-display tracking-tight">
                Cek Status Pemesanan Jeep
            </h1>
            <p class="text-merapiText-secondary text-xs sm:text-sm max-w-md mx-auto leading-relaxed">
                Masukkan kode booking (contoh: <code class="font-mono font-bold text-wukirsari-primary bg-wukirsari-sage/50 px-1.5 py-0.5 rounded">WKR-123456</code>) atau nomor WhatsApp pemesan untuk melihat status dan e-tiket Anda.
            </p>
        </div>

        {{-- Form Pencarian --}}
        <form action="{{ route('ticket.check') }}" method="GET" class="flex flex-col sm:flex-row gap-2">
            <input type="text"
                   name="code"
                   value="{{ $code ?? '' }}"
                   required
                   placeholder="Masukkan Kode Booking / No WhatsApp..."
                   class="w-full sm:flex-1 px-4 py-2.5 sm:py-3 rounded-full border border-wukirsari-border bg-white text-xs sm:text-sm focus:outline-none focus:border-wukirsari-primary shadow-2xs">
            <button type="submit"
                    class="w-full sm:w-auto px-6 py-2.5 sm:py-3 rounded-full bg-wukirsari-primary hover:bg-wukirsari-hover text-white text-xs sm:text-sm font-semibold transition-all shadow-xs active:scale-95 cursor-pointer whitespace-nowrap">
                Cari Tiket
            </button>
        </form>

        @if($hasSearched && $booking)
        {{-- Card Hasil Pencarian Tiket --}}
        <div class="bg-white rounded-3xl border border-wukirsari-border p-5 sm:p-7 shadow-xs space-y-5">
            {{-- Header Kode & Badge --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 border-b border-wukirsari-border pb-4">
                <div class="min-w-0">
                    <span class="text-[10px] sm:text-[11px] font-semibold text-merapiText-muted uppercase tracking-wider block">
                        Kode Reservasi
                    </span>
                    <span class="text-base sm:text-xl font-mono font-bold text-wukirsari-primary whitespace-nowrap tracking-wide">
                        {{ $booking->booking_code }}
                    </span>
                </div>
                <div class="shrink-0 self-start sm:self-center">
                    @if($booking->booking_status === 'FULL_PAID')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] sm:text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200/90 shadow-2xs whitespace-nowrap">
                        Lunas (100%)
                    </span>
                    @elseif($booking->booking_status === 'DP_PAID')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] sm:text-xs font-bold bg-amber-50 text-amber-900 border border-amber-200/90 shadow-2xs whitespace-nowrap">
                        Uang Muka Terverifikasi (DP)
                    </span>
                    @elseif($booking->booking_status === 'WAITING_CONFIRMATION')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] sm:text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200/90 shadow-2xs whitespace-nowrap">
                        Menunggu Verifikasi Bukti
                    </span>
                    @elseif($booking->booking_status === 'PENDING')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] sm:text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200/90 shadow-2xs whitespace-nowrap">
                        Menunggu Pembayaran
                    </span>
                    @elseif($booking->booking_status === 'CANCELLED')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] sm:text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200/90 shadow-2xs whitespace-nowrap">
                        Dibatalkan
                    </span>
                    @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] sm:text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200/90 shadow-2xs whitespace-nowrap">
                        {{ $booking->booking_status }}
                    </span>
                    @endif
                </div>
            </div>

            {{-- Informasi Detail Booking --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 sm:gap-4 py-1 text-xs sm:text-sm">
                <div class="space-y-0.5">
                    <span class="text-[10px] sm:text-[11px] font-semibold text-merapiText-muted uppercase tracking-wider block">Nama Pemesan</span>
                    <span class="font-bold text-merapiText-primary block text-sm">{{ $booking->customer_name }}</span>
                </div>
                <div class="space-y-0.5">
                    <span class="text-[10px] sm:text-[11px] font-semibold text-merapiText-muted uppercase tracking-wider block">Paket Wisata</span>
                    <span class="font-bold text-merapiText-primary block text-sm">{{ $booking->package->name ?? '-' }}</span>
                </div>
                <div class="space-y-0.5">
                    <span class="text-[10px] sm:text-[11px] font-semibold text-merapiText-muted uppercase tracking-wider block">Jadwal Tour</span>
                    <div class="font-bold text-merapiText-primary text-xs sm:text-sm">
                        <span>{{ \Carbon\Carbon::parse($booking->trip_date)->translatedFormat('d M Y') }}</span>
                        @if($booking->slot)
                        <span class="text-merapiText-secondary font-medium block sm:inline sm:ml-1">({{ $booking->slot->slot_label }})</span>
                        @endif
                    </div>
                </div>
                <div class="space-y-0.5">
                    <span class="text-[10px] sm:text-[11px] font-semibold text-merapiText-muted uppercase tracking-wider block">Peserta &amp; Armada</span>
                    <span class="font-bold text-merapiText-primary block text-xs sm:text-sm">
                        {{ $booking->passenger_count }} Orang <span class="text-merapiText-secondary font-medium">({{ $booking->jeep_quantity }} Jeep)</span>
                    </span>
                </div>
            </div>

            {{-- Ringkasan Pembayaran --}}
            <div class="p-4 sm:p-5 rounded-2xl bg-[#F7F7F2] border border-wukirsari-border space-y-2 text-xs sm:text-sm">
                <div class="flex items-center justify-between py-1 border-b border-wukirsari-border/70">
                    <span class="text-merapiText-secondary">Total Tagihan</span>
                    <span class="font-bold text-merapiText-primary">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</span>
                </div>
                <div class="flex items-center justify-between py-1 border-b border-wukirsari-border/70">
                    <span class="text-merapiText-secondary">Jumlah Terbayar</span>
                    <span class="font-bold text-wukirsari-primary">Rp {{ number_format($booking->paid_amount, 0, ',', '.') }}</span>
                </div>
                @if($booking->remaining_amount > 0)
                <div class="flex items-center justify-between pt-1">
                    <span class="text-amber-800 font-semibold">Sisa Bayar di Loket</span>
                    <span class="font-extrabold text-amber-900 text-sm sm:text-base">Rp {{ number_format($booking->remaining_amount, 0, ',', '.') }}</span>
                </div>
                @endif
            </div>

            {{-- QR Code E-Tiket & Tombol Aksi Utama --}}
            @if(in_array($booking->booking_status, ['FULL_PAID', 'DP_PAID']))
            <div class="p-4 sm:p-5 rounded-2xl bg-wukirsari-sage/30 border border-wukirsari-border flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-3.5 w-full sm:w-auto">
                    @php
                        $qrData = route('ticket.show', $booking->booking_code);
                        $qrUrl  = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($qrData) . '&color=1E3F20&bgcolor=FFFFFF&margin=4&format=png';
                    @endphp
                    <img src="{{ $qrUrl }}"
                         alt="QR Code Tiket {{ $booking->booking_code }}"
                         class="w-16 h-16 sm:w-18 sm:h-18 rounded-xl border border-wukirsari-border bg-white p-1 shrink-0 shadow-2xs object-contain">
                    <div class="min-w-0 space-y-0.5">
                        <span class="font-bold text-merapiText-primary text-xs sm:text-sm block">QR E-Tiket Resmi</span>
                        <p class="text-merapiText-secondary text-[11px] sm:text-xs leading-relaxed">
                            Tunjukkan QR Code ini kepada petugas loket pangkalan saat tiba di basecamp.
                        </p>
                    </div>
                </div>
                <a href="{{ route('booking.success', $booking->booking_code) }}"
                   class="w-full sm:w-auto shrink-0 inline-flex items-center justify-center px-5 py-2.5 rounded-full bg-wukirsari-primary hover:bg-wukirsari-hover text-white text-xs font-bold transition-all shadow-xs active:scale-95 whitespace-nowrap">
                    {{ $booking->booking_status === 'FULL_PAID' ? 'Buka E-Tiket Lengkap →' : 'Buka E-Tiket DP →' }}
                </a>
            </div>
            @else
            <div class="text-center pt-1">
                @if($booking->booking_status === 'WAITING_CONFIRMATION')
                <a href="{{ route('booking.payment', $booking->booking_code) }}"
                   class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-2.5 rounded-full bg-amber-600 hover:bg-amber-700 text-white text-xs font-semibold transition-colors shadow-xs">
                    Lihat Status Verifikasi Bukti →
                </a>
                @elseif($booking->booking_status === 'PENDING')
                <a href="{{ route('booking.payment', $booking->booking_code) }}"
                   class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-2.5 rounded-full bg-amber-600 hover:bg-amber-700 text-white text-xs font-semibold transition-colors shadow-xs">
                    Selesaikan Pembayaran Sekarang →
                </a>
                @else
                <a href="{{ route('booking.create') }}"
                   class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-2.5 rounded-full bg-wukirsari-primary hover:bg-wukirsari-hover text-white text-xs font-semibold transition-colors shadow-xs">
                    Buat Reservasi Baru →
                </a>
                @endif
            </div>
            @endif
        </div>
        @elseif($hasSearched && !$booking)
        <div class="bg-white rounded-3xl border border-wukirsari-border p-6 sm:p-8 text-center space-y-2.5 shadow-xs">
            <h3 class="text-base font-bold text-merapiText-primary font-display">Tiket Tidak Ditemukan</h3>
            <p class="text-xs sm:text-sm text-merapiText-secondary max-w-sm mx-auto leading-relaxed">
                Pastikan kode booking atau nomor WhatsApp yang Anda masukkan sudah benar dan telah tersimpan di sistem.
            </p>
        </div>
        @endif

    </div>
</div>
@endsection
