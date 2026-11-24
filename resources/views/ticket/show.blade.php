@extends('layouts.app')

@section('title', 'Verifikasi Tiket ' . strtoupper($code) . ' | Jeep Wukirsari')

@section('content')
<div class="py-10 sm:py-14 bg-[#F7F7F2] min-h-screen">
    <div class="max-w-lg mx-auto px-4 sm:px-6">

        {{-- Header --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-[#E8EFE9] border border-[#E2EAE3] mb-4">
                <svg class="w-7 h-7 text-[#1E3F20]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                </svg>
            </div>
            <span class="text-xs font-bold uppercase tracking-widest text-[#1E3F20] block mb-1">Verifikasi E-Ticket</span>
            <h1 class="text-xl sm:text-2xl font-extrabold text-[#19231A]">Jeep Wukirsari</h1>
            <p class="text-xs text-[#7E8E81] mt-1">Kapanewon Cangkringan, Sleman, D.I. Yogyakarta</p>
        </div>

        @if(!$booking)
        {{-- ===== TIKET TIDAK DITEMUKAN ===== --}}
        <div class="bg-white rounded-3xl border border-[#E2EAE3] shadow-sm p-8 text-center space-y-4">
            <div class="w-14 h-14 mx-auto rounded-2xl bg-rose-50 border border-rose-200 flex items-center justify-center">
                <svg class="w-7 h-7 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-base font-bold text-[#19231A] mb-1">Tiket Tidak Ditemukan</h2>
                <p class="text-xs text-[#526054]">
                    Kode <strong class="font-semibold text-[#19231A]">{{ strtoupper($code) }}</strong> tidak ditemukan dalam sistem.<br>
                    QR Code ini mungkin tidak valid atau telah kadaluwarsa.
                </p>
            </div>
            <a href="{{ route('home') }}" class="inline-block mt-2 text-xs font-semibold text-[#1E3F20] hover:underline">
                ← Kembali ke Beranda
            </a>
        </div>

        @else
        {{-- ===== TIKET DITEMUKAN ===== --}}

        {{-- Status Badge --}}
        <div class="flex justify-center mb-5">
            @if($booking->booking_status === 'FULL_PAID')
            <span class="inline-flex items-center gap-2 px-5 py-2 rounded-full bg-emerald-100 text-emerald-800 text-xs font-black border border-emerald-200">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                TIKET SAH — LUNAS
            </span>
            @elseif($booking->booking_status === 'DP_PAID')
            <span class="inline-flex items-center gap-2 px-5 py-2 rounded-full bg-amber-100 text-amber-800 text-xs font-black border border-amber-200">
                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                DP TERBAYAR — LUNASI DI LOKET
            </span>
            @elseif($booking->booking_status === 'PENDING')
            <span class="inline-flex items-center gap-2 px-5 py-2 rounded-full bg-slate-100 text-slate-700 text-xs font-black border border-slate-200">
                <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                MENUNGGU PEMBAYARAN
            </span>
            @elseif($booking->booking_status === 'CANCELLED')
            <span class="inline-flex items-center gap-2 px-5 py-2 rounded-full bg-rose-100 text-rose-700 text-xs font-black border border-rose-200">
                <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                DIBATALKAN
            </span>
            @else
            <span class="inline-flex items-center gap-2 px-5 py-2 rounded-full bg-slate-100 text-slate-700 text-xs font-black border border-slate-200">
                {{ $booking->booking_status }}
            </span>
            @endif
        </div>

        {{-- Card Utama --}}
        <div class="bg-white rounded-3xl border border-[#E2EAE3] shadow-sm overflow-hidden mb-4">

            {{-- Kode Booking --}}
            <div class="px-5 py-4 border-b border-[#E2EAE3] bg-[#F7F7F2] flex items-center justify-between">
                <div>
                    <span class="text-[10px] text-[#7E8E81] uppercase tracking-widest font-semibold block">Kode Booking</span>
                    <span class="font-mono font-black text-lg text-[#1E3F20] tracking-wide">{{ $booking->booking_code }}</span>
                </div>
                <div class="text-right">
                    <span class="text-[10px] text-[#7E8E81] uppercase tracking-widest font-semibold block">Pemesan</span>
                    <span class="font-semibold text-sm text-[#19231A]">{{ $booking->customer_name }}</span>
                </div>
            </div>

            {{-- Detail Trip --}}
            <div class="px-5 py-5 space-y-3">
                <div class="flex items-start justify-between text-sm">
                    <span class="text-[#7E8E81] text-xs">Paket Wisata</span>
                    <span class="font-semibold text-[#19231A] text-right max-w-[55%]">{{ $booking->package?->name ?? '-' }}</span>
                </div>
                <div class="flex items-start justify-between text-sm">
                    <span class="text-[#7E8E81] text-xs">Tanggal Trip</span>
                    <span class="font-semibold text-[#19231A]">
                        {{ $booking->trip_date?->translatedFormat('d F Y') ?? '-' }}
                    </span>
                </div>
                <div class="flex items-start justify-between text-sm">
                    <span class="text-[#7E8E81] text-xs">Sesi Waktu</span>
                    <span class="font-semibold text-[#19231A]">{{ $booking->slot?->slot_label ?? '-' }}</span>
                </div>
                <div class="flex items-start justify-between text-sm">
                    <span class="text-[#7E8E81] text-xs">Jumlah Jeep</span>
                    <span class="font-semibold text-[#19231A]">{{ $booking->jeep_quantity }} Unit</span>
                </div>

                {{-- Ringkasan Pembayaran --}}
                <div class="border-t border-[#E2EAE3] pt-3 mt-1 space-y-2">
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-[#7E8E81]">Total Tagihan</span>
                        <span class="font-semibold text-[#19231A]">Rp{{ number_format($booking->total_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-[#7E8E81]">Sudah Dibayar</span>
                        <span class="font-semibold text-emerald-700">Rp{{ number_format($booking->paid_amount, 0, ',', '.') }}</span>
                    </div>
                    @if($booking->remaining_amount > 0)
                    <div class="flex items-center justify-between text-xs border-t border-[#E2EAE3] pt-2">
                        <span class="font-bold text-[#19231A]">Sisa Bayar di Loket</span>
                        <span class="font-black text-amber-700">Rp{{ number_format($booking->remaining_amount, 0, ',', '.') }}</span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Status Footer --}}
            @if($booking->booking_status === 'FULL_PAID')
            <div class="px-5 py-4 bg-emerald-50 border-t border-emerald-100 flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-xs text-emerald-800 font-medium">
                    Tiket sah dan sudah lunas. Tunjukkan QR ini kepada petugas di basecamp.
                </p>
            </div>
            @elseif($booking->booking_status === 'DP_PAID')
            <div class="px-5 py-4 bg-amber-50 border-t border-amber-100 flex items-center gap-3">
                <svg class="w-5 h-5 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-xs text-amber-800 font-medium">
                    Harap lunasi sisa tagihan sebesar <strong>Rp{{ number_format($booking->remaining_amount, 0, ',', '.') }}</strong> di loket kasir basecamp sebelum berangkat.
                </p>
            </div>
            @endif
        </div>

        {{-- Lokasi Basecamp --}}
        <div class="flex items-center justify-between p-3.5 rounded-2xl bg-white border border-[#E2EAE3] mb-4">
            <div class="min-w-0">
                <span class="font-bold text-[#19231A] text-xs block">Basecamp Jeep Wukirsari</span>
                <span class="text-[11px] text-[#526054] block truncate">Kapanewon Cangkringan, Sleman, D.I. Yogyakarta</span>
            </div>
            <a href="https://maps.app.goo.gl/hCfpPScNLVMCRwcj7"
               target="_blank" rel="noopener noreferrer"
               class="flex-shrink-0 ml-2 text-xs font-semibold text-[#1E3F20] hover:text-[#265029] flex items-center gap-1 transition-colors">
                Lihat Lokasi
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        {{-- Link ke halaman sukses (jika sudah bayar) --}}
        @if(in_array($booking->booking_status, ['FULL_PAID', 'DP_PAID']))
        <div class="text-center mb-4">
            <a href="{{ route('booking.success', $booking->booking_code) }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-[#1E3F20] hover:bg-[#265029] text-white text-xs font-bold transition-colors shadow-sm">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                </svg>
                Buka E-Tiket Lengkap
            </a>
        </div>
        @endif

        @endif{{-- end if booking --}}

        {{-- ===== LINK PETUGAS BASECAMP (tidak mencolok, di bagian bawah) ===== --}}
        <div class="border-t border-[#E2EAE3] pt-4 text-center">
            <p class="text-[11px] text-[#7E8E81] mb-1.5">Petugas Basecamp?</p>
            <a href="{{ route('admin.scan', strtoupper($code)) }}"
               class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#526054] hover:text-[#1E3F20] transition-colors border border-[#E2EAE3] rounded-full px-4 py-1.5 hover:border-[#1E3F20] bg-white">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                Verifikasi & Proses Pelunasan
            </a>
        </div>

    </div>
</div>
@endsection

