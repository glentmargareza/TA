@extends('layouts.app')

@section('title', 'E-Tiket ' . $booking->booking_code . ' | Jeep Wukirsari')

{{-- Inline styles — layout tidak memiliki @stack('styles'), jadi style harus di sini --}}

@section('content')
{{-- ===== INLINE STYLES (layout tidak memiliki @stack styles) ===== --}}
@php $merapiBg = asset('img/beranda.jpeg'); @endphp
<style>
    /* ============================================================
       PRINT / SAVE AS PDF  —  Kartu e-ticket tampil penuh, navbar
       dan tombol aksi tersembunyi.
       Pendekatan: sembunyikan elemen spesifik, bukan body > *
       karena #printArea bukan direct-child of <body>.
    ============================================================ */
    @media print {
        /* Izinkan browser mencetak warna & gambar latar */
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            color-adjust: exact !important;
        }

        /* Sembunyikan elemen yang tidak perlu dicetak */
        header,
        aside,
        footer,
        nav,
        #mobileMenuBackdrop,
        #mobileNavDrawer,
        #stickyActionBar,
        #flashToastContainer,
        .no-print {
            display: none !important;
        }

        /* Reset body & wrapper agar kartu tiket memenuhi halaman */
        html, body {
            margin: 0 !important;
            padding: 0 !important;
            background: white !important;
        }

        /* Wrapper utama: hilangkan padding/min-height agar tidak ada ruang kosong */
        .min-h-screen {
            min-height: unset !important;
            padding: 0 !important;
            background: white !important;
        }
        .max-w-2xl {
            max-width: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        /* Kartu tiket: tampil penuh tanpa shadow berlebihan */
        #printArea {
            display: block !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        .print-ticket {
            box-shadow: none !important;
            border: 1px solid #ddd !important;
            border-radius: 0 !important;
            page-break-inside: avoid;
        }

        /* Pastikan background header tiket (hijau tua) ikut tercetak */
        .ticket-header-bg {
            background-color: #1E3F20 !important;
        }

        /* Sticky bar sudah hidden via #stickyActionBar, tapi pastikan .sticky-bar juga hidden */
        .sticky-bar {
            display: none !important;
        }

        /* Tombol aksi bawah desktop sudah punya class no-print — sudah ditangani di atas */
    }
    .ticket-header-bg {
        background-color: #1E3F20;
        position: relative;
        overflow: hidden;
    }
    .perfo-line {
        position: relative;
        height: 1px;
        margin: 0 -1px;
    }
    .perfo-line::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 20px;
        right: 20px;
        border-top: 2px dashed #E2EAE3;
        transform: translateY(-50%);
    }
    .perfo-notch-left {
        position: absolute;
        left: -1px;
        top: 50%;
        transform: translateY(-50%);
        width: 20px;
        height: 36px;
        background: #F7F7F2;
        border-radius: 0 18px 18px 0;
        border: 1px solid #E2EAE3;
        border-left: none;
    }
    .perfo-notch-right {
        position: absolute;
        right: -1px;
        top: 50%;
        transform: translateY(-50%);
        width: 20px;
        height: 36px;
        background: #F7F7F2;
        border-radius: 18px 0 0 18px;
        border: 1px solid #E2EAE3;
        border-right: none;
    }
    .info-row-item {
        display: flex;
        align-items: flex-start;
        gap: 10px;
    }
    .info-icon-wrap {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: #E8EFE9;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        margin-top: 2px;
    }
    .sticky-bar {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 50;
        padding: 12px 16px;
        background: white;
        border-top: 1px solid #E2EAE3;
        box-shadow: 0 -4px 24px rgba(30, 63, 32, 0.08);
    }
</style>

{{-- Main Page Wrapper --}}
<div class="min-h-screen bg-[#F7F7F2] pb-32 sm:pb-16">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 pt-10 sm:pt-14">

        {{-- ===== SUCCESS HEADER ===== --}}
        <div class="text-center mb-8 no-print">
            {{-- Check icon --}}
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-emerald-50 border-2 border-emerald-200 mx-auto mb-4">
                <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#1E3F20] font-display tracking-tight">
                Yeay! Pembayaran Berhasil
            </h1>
            
            {{-- Bagian bawah ucapan (Background Cream) --}}
            <div class="mt-3 inline-block bg-[#F7F7F2] border border-[#E2EAE3] px-5 py-2.5 rounded-2xl shadow-xs">
                <p class="text-sm sm:text-base font-bold text-[#1E3F20] tracking-wide">
                    See you on the adventure!
                </p>
                <p class="text-xs text-[#526054] font-medium mt-0.5">
                    Jeep Wukirsari Adventure Tour
                </p>
            </div>
        </div>

        {{-- ===== E-TICKET CARD ===== --}}
        <div id="printArea">
        <div class="bg-white rounded-3xl border border-[#E2EAE3] shadow-xl overflow-hidden print-ticket" id="eticketCard">

            {{-- ----- TICKET HEADER (Dark green + mountain BG) ----- --}}
            <div class="ticket-header-bg p-5 sm:p-7 relative" style="min-height: 140px;">
                {{-- Mountain photo background (right side) --}}
                <div class="absolute inset-y-0 right-0" style="width:50%; background-image:url('{{ $merapiBg }}'); background-size:cover; background-position:center; opacity:0.45;"></div>
                {{-- Gradient overlay to blend photo into green --}}
                <div class="absolute inset-y-0 right-0" style="width:60%; background:linear-gradient(to right, #1E3F20 0%, transparent 100%);"></div>
                <div class="relative z-10 flex items-start justify-between">

                    {{-- Left: Brand --}}
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-2xl bg-white/15 border border-white/25 flex items-center justify-center backdrop-blur-sm flex-shrink-0">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                            </svg>
                        </div>
                        <div>
                            <span class="block font-extrabold text-white text-base sm:text-lg leading-tight tracking-wide">JEEP WUKIRSARI</span>
                            <span class="block text-[10px] text-white/70 font-medium uppercase tracking-widest">Adventure Tour</span>
                        </div>
                    </div>

                    {{-- Right: Status badge --}}
                    @if($booking->booking_status === 'FULL_PAID')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-400 text-[#142B16] text-xs font-bold shadow-sm">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        LUNAS
                    </span>
                    @else
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-amber-400 text-amber-900 text-xs font-bold shadow-sm">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
                        DP TERVERIFIKASI
                    </span>
                    @endif

                </div>

                {{-- Bottom area: E-Ticket label & booking code --}}
                <div class="relative z-10 mt-5">
                    <span class="block text-[11px] font-semibold text-emerald-300 uppercase tracking-widest mb-0.5">E-TICKET WISATA</span>
                    <h2 class="text-xl sm:text-2xl font-extrabold text-white leading-tight">Selamat menikmati perjalanan Anda!</h2>
                </div>
            </div>

            {{-- ----- BOOKING CODE STRIP ----- --}}
            <div class="px-5 sm:px-7 py-4 bg-[#F7F7F2] border-b border-[#E2EAE3]">
                <span class="block text-[10px] font-semibold text-[#526054] uppercase tracking-widest mb-1">Kode Booking</span>
                <span class="font-mono font-extrabold text-2xl sm:text-3xl text-[#19231A] tracking-wider">{{ $booking->booking_code }}</span>
            </div>

            {{-- ----- PERFORASI LINE ----- --}}
            <div class="perfo-line mx-0 my-0">
                <div class="perfo-notch-left"></div>
                <div class="perfo-notch-right"></div>
            </div>

            {{-- ----- BOOKING INFO SECTION ----- --}}
            <div class="px-5 sm:px-7 pt-5 pb-4">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-6 h-6 rounded-lg bg-[#E8EFE9] flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-[#1E3F20]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <span class="font-bold text-[#19231A] text-sm">Informasi Pemesanan</span>

                    @php $assignment = $booking->assignments->first(); @endphp
                    @if($assignment)
                    <div class="sm:col-span-2 p-2.5 rounded-xl bg-emerald-50/70 border border-emerald-200/80">
                        <span class="text-emerald-800 text-xs font-semibold block">Armada & Driver Paguyuban:</span>
                        <span class="font-bold text-emerald-950 text-xs sm:text-sm flex items-center gap-1.5 mt-0.5">
                            🚙 {{ $assignment->jeep?->model_name ?? 'Jeep Armada' }} &bull; Driver: {{ $assignment->driver?->full_name ?? 'Driver Paguyuban' }}
                        </span>
                    </div>
                    @endif
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                    {{-- Nama Pemesan --}}
                    <div class="info-row-item">
                        <div class="info-icon-wrap">
                            <svg class="w-4 h-4 text-[#1E3F20]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <span class="block text-[10px] font-semibold text-[#7E8E81] uppercase tracking-wider">Nama Pemesan</span>
                            <span class="font-bold text-[#19231A] text-sm mt-0.5 block">{{ $booking->customer_name }}</span>
                        </div>
                    </div>

                    {{-- Paket Wisata --}}
                    <div class="info-row-item">
                        <div class="info-icon-wrap">
                            <svg class="w-4 h-4 text-[#1E3F20]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <span class="block text-[10px] font-semibold text-[#7E8E81] uppercase tracking-wider">Paket Wisata</span>
                            <span class="font-bold text-[#19231A] text-sm mt-0.5 block">Paket {{ $booking->package->name }}</span>
                        </div>
                    </div>

                    {{-- Tanggal Wisata --}}
                    <div class="info-row-item">
                        <div class="info-icon-wrap">
                            <svg class="w-4 h-4 text-[#1E3F20]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <span class="block text-[10px] font-semibold text-[#7E8E81] uppercase tracking-wider">Tanggal Wisata</span>
                            <span class="font-bold text-[#19231A] text-sm mt-0.5 block">{{ $booking->trip_date->translatedFormat('d F Y') }}</span>
                        </div>
                    </div>

                    {{-- Jumlah Peserta --}}
                    <div class="info-row-item">
                        <div class="info-icon-wrap">
                            <svg class="w-4 h-4 text-[#1E3F20]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <span class="block text-[10px] font-semibold text-[#7E8E81] uppercase tracking-wider">Jumlah Peserta</span>
                            <span class="font-bold text-[#19231A] text-sm mt-0.5 block">{{ $booking->passenger_count }} Orang</span>
                        </div>
                    </div>

                    {{-- Jumlah Jeep --}}
                    <div class="info-row-item">
                        <div class="info-icon-wrap">
                            <svg class="w-4 h-4 text-[#1E3F20]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <span class="block text-[10px] font-semibold text-[#7E8E81] uppercase tracking-wider">Jumlah Jeep</span>
                            <span class="font-bold text-[#19231A] text-sm mt-0.5 block">{{ $booking->jeep_quantity }} Jeep</span>
                        </div>
                    </div>

                    {{-- Jenis Kedatangan --}}
                    <div class="info-row-item">
                        <div class="info-icon-wrap">
                            <svg class="w-4 h-4 text-[#1E3F20]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <span class="block text-[10px] font-semibold text-[#7E8E81] uppercase tracking-wider">Kedatangan</span>
                            <span class="font-bold text-[#19231A] text-sm mt-0.5 block">
                                {{ $booking->arrival_type === 'self_drive' ? 'Datang Sendiri' : 'Jemputan Shuttle' }}
                            </span>
                        </div>
                    </div>

                </div>
            </div>

            {{-- ----- PERFORASI 2 ----- --}}
            <div class="perfo-line mx-0 my-0">
                <div class="perfo-notch-left"></div>
                <div class="perfo-notch-right"></div>
            </div>

            {{-- ----- RINGKASAN PEMBAYARAN ----- --}}
            <div class="px-5 sm:px-7 py-5">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-lg bg-[#E8EFE9] flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-[#1E3F20]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                        </div>
                        <span class="font-bold text-[#19231A] text-sm">Ringkasan Pembayaran</span>
                    </div>
                    @if($booking->booking_status === 'FULL_PAID')
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-[#1E3F20] text-white text-[10px] font-bold">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        LUNAS
                    </span>
                    @else
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-amber-100 text-amber-800 text-[10px] font-bold">
                        DP TERBAYAR
                    </span>
                    @endif
                </div>

                @php
                    $settledPayments = $booking->payments->where('payment_status', 'SETTLED')->sortBy('id');
                    $dpPayment = $settledPayments->first();
                    $lunaPayment = $settledPayments->count() > 1 ? $settledPayments->last() : null;
                @endphp

                <div class="space-y-2 text-sm">
                    {{-- Total Tagihan --}}
                    <div class="flex items-center justify-between py-1.5 border-b border-[#E2EAE3]">
                        <span class="text-[#526054]">Total Tagihan</span>
                        <span class="font-semibold text-[#19231A]">Rp{{ number_format($booking->total_amount, 0, ',', '.') }}</span>
                    </div>

                    @if($booking->payment_mode === 'DP' && $dpPayment)
                    {{-- DP row --}}
                    <div class="flex items-center justify-between py-1.5 border-b border-[#E2EAE3]">
                        <span class="text-[#526054]">DP Dibayarkan</span>
                        <span class="font-semibold text-[#19231A]">Rp{{ number_format($dpPayment->gross_amount, 0, ',', '.') }}</span>
                    </div>
                    @if($lunaPayment)
                    {{-- Pelunasan row --}}
                    <div class="flex items-center justify-between py-1.5 border-b border-[#E2EAE3]">
                        <span class="text-[#526054]">Pelunasan</span>
                        <span class="font-semibold text-[#19231A]">Rp{{ number_format($lunaPayment->gross_amount, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    @endif

                    @if($booking->remaining_amount > 0)
                    {{-- Sisa bayar --}}
                    <div class="flex items-center justify-between py-1.5 border-b border-[#E2EAE3]">
                        <span class="text-amber-700 font-medium">Sisa Bayar di Loket</span>
                        <span class="font-semibold text-amber-800">Rp{{ number_format($booking->remaining_amount, 0, ',', '.') }}</span>
                    </div>
                    @endif

                    {{-- Total Dibayar --}}
                    <div class="flex items-center justify-between pt-2.5">
                        <span class="font-bold text-[#19231A]">Total Dibayar</span>
                        <span class="font-extrabold text-[#1E3F20] text-base">Rp{{ number_format($booking->paid_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            {{-- ----- PERFORASI 3 ----- --}}
            <div class="perfo-line mx-0 my-0">
                <div class="perfo-notch-left"></div>
                <div class="perfo-notch-right"></div>
            </div>

            {{-- ----- QR CODE SECTION ----- --}}
            <div class="px-5 sm:px-7 py-5">
                <div class="flex items-start gap-4">
                    {{-- QR Code --}}
                    <div class="flex-shrink-0">
                        <div class="w-24 h-24 bg-white border border-[#E2EAE3] rounded-2xl flex items-center justify-center p-1.5 shadow-sm">
                            @php
                                $qrData = route('ticket.check', ['code' => $booking->booking_code]);
                                $qrUrl  = 'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' . urlencode($qrData) . '&color=1E3F20&bgcolor=FFFFFF&margin=4&format=png';
                            @endphp
                            <img src="{{ $qrUrl }}"
                                 crossorigin="anonymous"
                                 alt="QR Code Tiket {{ $booking->booking_code }}"
                                 class="w-full h-full object-contain rounded-xl"
                                 onerror="this.style.display='none'; document.getElementById('qrFallback').style.display='flex'">
                            <div id="qrFallback" class="hidden w-full h-full items-center justify-center">
                                <svg class="w-10 h-10 text-[#1E3F20]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- QR Info --}}
                    <div class="flex-1 min-w-0">
                        <span class="font-bold text-[#19231A] text-sm block mb-1">Tunjukkan E-Ticket ini saat check-in</span>
                        <span class="text-xs text-[#526054] leading-relaxed block">Simpan e-ticket dan tunjukkan kepada petugas basecamp sebelum perjalanan.</span>
                        <div class="mt-2.5 inline-flex items-center gap-1.5 text-[10px] font-semibold text-[#1E3F20] bg-[#E8EFE9] rounded-full px-2.5 py-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            Scan untuk validasi booking
                        </div>
                    </div>
                </div>
            </div>

            {{-- ----- BASECAMP LOCATION ----- --}}
            <div class="px-5 sm:px-7 pb-5">
                <div class="flex items-center justify-between p-3.5 rounded-2xl bg-[#F7F7F2] border border-[#E2EAE3]">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-8 h-8 rounded-xl bg-[#E8EFE9] flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-[#1E3F20]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <span class="font-bold text-[#19231A] text-xs block">Basecamp Jeep Wukirsari</span>
                            <span class="text-[11px] text-[#526054] block truncate">Kapanewon Cangkringan, Sleman, D.I. Yogyakarta</span>
                        </div>
                    </div>
                    <a href="https://maps.app.goo.gl/hCfpPScNLVMCRwcj7"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="flex-shrink-0 ml-2 text-xs font-semibold text-[#1E3F20] hover:text-[#265029] flex items-center gap-1 transition-colors no-print">
                        Lihat Lokasi
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>

        </div>{{-- end eticketCard --}}
        </div>{{-- end printArea --}}

        {{-- ===== ACTION BUTTONS (Desktop / non-sticky) ===== --}}
        @php
            $waMsg  = "Halo Admin Jeep Wukirsari, saya telah memesan tiket online.\nKode Booking: *{$booking->booking_code}*\nNama: {$booking->customer_name}\nTanggal: {$booking->trip_date->format('d/m/Y')}\nPaket: {$booking->package->name}";
            $waLink = "https://wa.me/6281234567890?text=" . urlencode($waMsg);
        @endphp

        <div class="hidden sm:flex items-center justify-center gap-3 mt-6 flex-wrap no-print">
            {{-- Dropdown Simpan E-Ticket (PDF & PNG) --}}
            <div class="relative inline-block text-left" id="desktopSaveWrapper">
                <button type="button"
                        id="btnSaveDesktop"
                        onclick="toggleSaveDropdown('desktop')"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full border border-[#E2EAE3] bg-white text-[#19231A] text-sm font-semibold hover:bg-[#F7F7F2] transition-colors shadow-sm focus:outline-none cursor-pointer">
                    <svg class="w-4 h-4 text-[#526054]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    <span>Simpan E-Ticket</span>
                    <svg class="w-3.5 h-3.5 text-[#526054] transition-transform duration-200" id="desktopChevron" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                {{-- Popover Dropdown Desktop --}}
                <div id="desktopSaveMenu"
                     class="hidden absolute left-0 mt-2 w-56 rounded-2xl bg-white border border-[#E2EAE3] shadow-xl p-1.5 z-50 transition-all">
                    <button type="button"
                            onclick="saveTicketAs('pdf')"
                            class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-left hover:bg-[#F7F7F2] transition-colors group cursor-pointer">
                        <div class="w-8 h-8 rounded-lg bg-rose-50 border border-rose-100 flex items-center justify-center shrink-0 text-rose-600 group-hover:scale-105 transition-transform">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 13h6m-6 4h4"/>
                            </svg>
                        </div>
                        <div>
                            <span class="block text-xs font-bold text-[#19231A]">Simpan sebagai PDF</span>
                            <span class="block text-[10px] text-[#526054]">Format dokumen cetak</span>
                        </div>
                    </button>
                    <button type="button"
                            onclick="saveTicketAs('png')"
                            class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-left hover:bg-[#F7F7F2] transition-colors group cursor-pointer">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 border border-emerald-100 flex items-center justify-center shrink-0 text-emerald-600 group-hover:scale-105 transition-transform">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <span class="block text-xs font-bold text-[#19231A]">Unduh sebagai PNG</span>
                            <span class="block text-[10px] text-[#526054]">Gambar resolusi tinggi</span>
                        </div>
                    </button>
                </div>
            </div>

            {{-- Cetak --}}
            <button onclick="window.print()" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full border border-[#E2EAE3] bg-white text-[#19231A] text-sm font-semibold hover:bg-[#F7F7F2] transition-colors shadow-sm">
                <svg class="w-4 h-4 text-[#526054]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Cetak Tiket
            </button>
            {{-- WhatsApp --}}
            <a href="{{ $waLink }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-[#1E3F20] hover:bg-[#265029] text-white text-sm font-semibold transition-colors shadow-sm">
                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                    <path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0012.04 2zm4.98 14.07c-.21.59-1.21 1.14-1.66 1.21-.45.07-.99.1-1.6-.1-.37-.11-.84-.27-1.44-.53-2.52-1.09-4.17-3.6-4.3-3.77-.13-.17-1.06-1.41-1.06-2.69 0-1.28.67-1.91.9-2.17.24-.26.52-.32.69-.32l.5.01c.16.01.37-.06.58.44l.75 1.84c.07.17.11.36.02.58l-.27.52-.38.49c-.13.16-.27.33-.12.65.16.32.69 1.14 1.49 1.84.72.63 1.42.95 1.72 1.06.3.11.47.09.64-.05.17-.14.73-.85.93-1.14.2-.29.4-.24.67-.14l1.9.9c.3.14.5.21.57.33.07.11.07.65-.14 1.24z"/>
                </svg>
                Konfirmasi WhatsApp
            </a>
            {{-- Kembali Beranda --}}
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full border border-[#E2EAE3] bg-white text-[#19231A] text-sm font-semibold hover:bg-[#F7F7F2] transition-colors shadow-sm">
                <svg class="w-4 h-4 text-[#526054]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Kembali ke Beranda
            </a>
        </div>

        {{-- Tagline --}}
        <div class="mt-8 text-center no-print">
            <p class="text-xs text-[#7E8E81]">Terima kasih telah memilih Jeep Wukirsari
                <svg class="w-3.5 h-3.5 text-rose-400 inline-block mb-0.5 ml-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                </svg>
            </p>
        </div>

    </div>{{-- end max-w-2xl --}}
</div>{{-- end min-h-screen --}}

{{-- ===== STICKY BOTTOM BAR (Mobile) ===== --}}
<div class="sticky-bar sm:hidden no-print" id="stickyActionBar">
    {{-- Popover menu for mobile (opens above the sticky bar) --}}
    <div id="mobileSaveMenu"
         class="hidden absolute bottom-full left-4 right-4 mb-2 bg-white border border-[#E2EAE3] rounded-2xl shadow-2xl p-1.5 z-50">
        <button type="button"
                onclick="saveTicketAs('pdf')"
                class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-left hover:bg-[#F7F7F2] transition-colors">
            <div class="w-8 h-8 rounded-lg bg-rose-50 border border-rose-100 flex items-center justify-center shrink-0 text-rose-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 13h6m-6 4h4"/>
                </svg>
            </div>
            <div>
                <span class="block text-xs font-bold text-[#19231A]">Simpan sebagai PDF</span>
                <span class="block text-[10px] text-[#526054]">Format dokumen cetak</span>
            </div>
        </button>
        <button type="button"
                onclick="saveTicketAs('png')"
                class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-left hover:bg-[#F7F7F2] transition-colors">
            <div class="w-8 h-8 rounded-lg bg-emerald-50 border border-emerald-100 flex items-center justify-center shrink-0 text-emerald-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <span class="block text-xs font-bold text-[#19231A]">Unduh sebagai PNG</span>
                <span class="block text-[10px] text-[#526054]">Gambar resolusi tinggi</span>
            </div>
        </button>
    </div>

    <div class="flex gap-2">
        <button type="button"
                id="btnSaveMobile"
                onclick="toggleSaveDropdown('mobile')"
                class="flex-1 inline-flex items-center justify-center gap-1.5 py-2.5 rounded-full border border-[#E2EAE3] bg-white text-[#19231A] text-xs font-semibold hover:bg-[#F7F7F2] transition-colors">
            <svg class="w-3.5 h-3.5 text-[#526054]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            <span>Simpan</span>
            <svg class="w-3 h-3 text-[#526054] transition-transform duration-200" id="mobileChevron" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/>
            </svg>
        </button>
        <button onclick="window.print()" class="flex-1 inline-flex items-center justify-center gap-1.5 py-2.5 rounded-full border border-[#E2EAE3] bg-white text-[#19231A] text-xs font-semibold hover:bg-[#F7F7F2] transition-colors">
            <svg class="w-3.5 h-3.5 text-[#526054]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            Cetak
        </button>
        <a href="{{ $waLink }}" target="_blank" rel="noopener noreferrer" class="flex-[2] inline-flex items-center justify-center gap-1.5 py-2.5 rounded-full bg-[#1E3F20] hover:bg-[#265029] text-white text-xs font-bold transition-colors">
            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                <path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0012.04 2zm4.98 14.07c-.21.59-1.21 1.14-1.66 1.21-.45.07-.99.1-1.6-.1-.37-.11-.84-.27-1.44-.53-2.52-1.09-4.17-3.6-4.3-3.77-.13-.17-1.06-1.41-1.06-2.69 0-1.28.67-1.91.9-2.17.24-.26.52-.32.69-.32l.5.01c.16.01.37-.06.58.44l.75 1.84c.07.17.11.36.02.58l-.27.52-.38.49c-.13.16-.27.33-.12.65.16.32.69 1.14 1.49 1.84.72.63 1.42.95 1.72 1.06.3.11.47.09.64-.05.17-.14.73-.85.93-1.14.2-.29.4-.24.67-.14l1.9.9c.3.14.5.21.57.33.07.11.07.65-.14 1.24z"/>
            </svg>
            Konfirmasi WhatsApp
        </a>
        <a href="{{ route('home') }}" class="flex-1 inline-flex items-center justify-center gap-1 py-2.5 rounded-full border border-[#E2EAE3] bg-white text-[#19231A] text-xs font-semibold hover:bg-[#F7F7F2] transition-colors">
            <svg class="w-3.5 h-3.5 text-[#526054]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Beranda
        </a>
    </div>
</div>

{{-- Library html2canvas via CDN untuk unduh PNG --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

{{-- Inline script — penanganan simpan tiket (PDF & PNG) dan cetak --}}
<script>
    /**
     * toggleSaveDropdown(target) - Buka/tutup dropdown pilihan format
     */
    function toggleSaveDropdown(target) {
        var desktopMenu = document.getElementById('desktopSaveMenu');
        var mobileMenu = document.getElementById('mobileSaveMenu');
        var desktopChevron = document.getElementById('desktopChevron');
        var mobileChevron = document.getElementById('mobileChevron');

        if (target === 'desktop') {
            if (mobileMenu) mobileMenu.classList.add('hidden');
            if (desktopMenu) {
                var isHidden = desktopMenu.classList.contains('hidden');
                desktopMenu.classList.toggle('hidden');
                if (desktopChevron) {
                    desktopChevron.style.transform = isHidden ? 'rotate(180deg)' : 'rotate(0deg)';
                }
            }
        } else if (target === 'mobile') {
            if (desktopMenu) desktopMenu.classList.add('hidden');
            if (mobileMenu) {
                var isHidden = mobileMenu.classList.contains('hidden');
                mobileMenu.classList.toggle('hidden');
                if (mobileChevron) {
                    mobileChevron.style.transform = isHidden ? 'rotate(180deg)' : 'rotate(0deg)';
                }
            }
        }
    }

    /**
     * closeAllSaveDropdowns() - Tutup semua menu popover/dropdown
     */
    function closeAllSaveDropdowns() {
        var desktopMenu = document.getElementById('desktopSaveMenu');
        var mobileMenu = document.getElementById('mobileSaveMenu');
        var desktopChevron = document.getElementById('desktopChevron');
        var mobileChevron = document.getElementById('mobileChevron');
        if (desktopMenu) desktopMenu.classList.add('hidden');
        if (mobileMenu) mobileMenu.classList.add('hidden');
        if (desktopChevron) desktopChevron.style.transform = 'rotate(0deg)';
        if (mobileChevron) mobileChevron.style.transform = 'rotate(0deg)';
    }

    /**
     * Listener klik di luar area dropdown untuk menutup otomatis
     */
    document.addEventListener('click', function(e) {
        var desktopWrapper = document.getElementById('desktopSaveWrapper');
        var mobileBar = document.getElementById('stickyActionBar');
        var clickedInsideDesktop = desktopWrapper && desktopWrapper.contains(e.target);
        var clickedInsideMobile = mobileBar && mobileBar.contains(e.target);
        if (!clickedInsideDesktop && !clickedInsideMobile) {
            closeAllSaveDropdowns();
        }
    });

    /**
     * saveTicketAs(format) - Eksekusi penyimpanan sesuai format ('pdf' atau 'png')
     */
    function saveTicketAs(format) {
        closeAllSaveDropdowns();
        if (format === 'pdf') {
            saveTicket();
        } else if (format === 'png') {
            downloadTicketPNG();
        }
    }

    /**
     * saveTicket() - Simpan e-ticket sebagai PDF menggunakan print dialog browser
     */
    function saveTicket() {
        var originalTitle = document.title;
        document.title = 'E-Tiket-{{ $booking->booking_code }}-Jeep-Wukirsari';
        window.print();
        setTimeout(function() { document.title = originalTitle; }, 1500);
    }

    /**
     * downloadTicketPNG() - Tangkap kartu e-ticket dan unduh sebagai file PNG
     */
    function downloadTicketPNG() {
        var card = document.getElementById('eticketCard');
        if (!card) return;

        function runCapture() {
            var toast = document.createElement('div');
            toast.id = 'pngDownloadToast';
            toast.className = 'fixed bottom-20 sm:bottom-6 right-6 z-50 bg-[#142B16] text-white px-4 py-2.5 rounded-2xl shadow-2xl flex items-center gap-2.5 text-xs font-semibold';
            toast.innerHTML = '<svg class="w-4 h-4 animate-spin text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg><span>Menyiapkan gambar PNG...</span>';
            document.body.appendChild(toast);

            html2canvas(card, {
                scale: 2,
                useCORS: true,
                allowTaint: true,
                backgroundColor: '#ffffff',
                logging: false
            }).then(function(canvas) {
                if (toast && toast.parentNode) toast.parentNode.removeChild(toast);
                var link = document.createElement('a');
                link.download = 'E-Tiket-{{ $booking->booking_code }}-Jeep-Wukirsari.png';
                link.href = canvas.toDataURL('image/png');
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }).catch(function(err) {
                if (toast && toast.parentNode) toast.parentNode.removeChild(toast);
                console.error('Gagal membuat gambar PNG:', err);
                alert('Gagal membuat gambar PNG tiket. Anda dapat menggunakan opsi Simpan sebagai PDF.');
            });
        }

        if (typeof html2canvas === 'undefined') {
            var s = document.createElement('script');
            s.src = 'https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js';
            s.onload = runCapture;
            s.onerror = function() {
                alert('Tidak dapat memuat modul pengunduh gambar. Silakan gunakan opsi Simpan sebagai PDF.');
            };
            document.head.appendChild(s);
        } else {
            runCapture();
        }
    }
</script>

@endsection
