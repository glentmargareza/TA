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
    /* Foto Merapi: membentang penuh dengan mask gradasi halus dari kiri ke kanan */
    .ticket-header-photo {
        position: absolute;
        inset: 0;
        background-size: cover;
        background-position: right center;
        opacity: 0.45;
        /* Mask gradasi lembut: kiri transparan penuh, kanan solid bertahap */
        -webkit-mask-image: linear-gradient(
            to right,
            transparent 0%,
            transparent 25%,
            rgba(0, 0, 0, 0.08) 35%,
            rgba(0, 0, 0, 0.25) 48%,
            rgba(0, 0, 0, 0.55) 65%,
            rgba(0, 0, 0, 0.85) 82%,
            black 100%
        );
        mask-image: linear-gradient(
            to right,
            transparent 0%,
            transparent 25%,
            rgba(0, 0, 0, 0.08) 35%,
            rgba(0, 0, 0, 0.25) 48%,
            rgba(0, 0, 0, 0.55) 65%,
            rgba(0, 0, 0, 0.85) 82%,
            black 100%
        );
    }
    /* Veil: rona hijau Deep Forest (#1E3F20) yang membaurkan foto dan latar belakang secara seamless */
    .ticket-header-veil {
        position: absolute;
        inset: 0;
        pointer-events: none;
        background:
            linear-gradient(
                to right,
                #1E3F20 0%,
                #1E3F20 28%,
                rgba(30, 63, 32, 0.85) 48%,
                rgba(30, 63, 32, 0.45) 70%,
                rgba(30, 63, 32, 0.15) 100%
            ),
            linear-gradient(
                to top,
                rgba(20, 43, 22, 0.65) 0%,
                rgba(20, 43, 22, 0.15) 40%,
                transparent 70%
            );
    }    .perfo-line {
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

    /* ============================================================
       ANIMASI CENTANG SUKSES — Elegan, Modern, Profesional
       Desain: lingkaran solid hijau Forest (#1E3F20) + centang putih
       Urutan: circle draw (0.7s) → check draw (0.4s, delay 0.5s)
                → bounce settle (0.5s, delay 0.85s) → glow pulse (1x)
    ============================================================ */
    .checkmark-wrapper {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 72px;
        height: 72px;
        border-radius: 50%;
        /* soft glow ring — hijau Forest dengan opacity rendah */
        box-shadow:
            0 0 0 0 rgba(30, 63, 32, 0),
            0 4px 24px rgba(30, 63, 32, 0.22);
        animation: checkmarkWrapperReveal 0.3s ease-out forwards,
                   checkmarkGlowPulse 0.6s ease-out 0.95s forwards;
        opacity: 0;
        transform: scale(0.7);
    }
    @keyframes checkmarkWrapperReveal {
        0%   { opacity: 0; transform: scale(0.7); }
        60%  { opacity: 1; transform: scale(1.06); }
        80%  { transform: scale(0.97); }
        100% { opacity: 1; transform: scale(1); }
    }
    @keyframes checkmarkGlowPulse {
        0%   { box-shadow: 0 0 0 0 rgba(30, 63, 32, 0.30), 0 4px 24px rgba(30, 63, 32, 0.22); }
        50%  { box-shadow: 0 0 0 10px rgba(30, 63, 32, 0.07), 0 4px 24px rgba(30, 63, 32, 0.18); }
        100% { box-shadow: 0 0 0 0 rgba(30, 63, 32, 0), 0 4px 24px rgba(30, 63, 32, 0.12); }
    }

    /* Lingkaran luar — outline yang tergambar bertahap */
    .checkmark-circle-track {
        fill: none;
        stroke: rgba(30, 63, 32, 0.15);
        stroke-width: 3;
    }
    .checkmark-circle-fill {
        fill: #1E3F20;
        opacity: 0;
        animation: checkmarkFillFade 0.25s ease-out 0.08s forwards;
    }
    @keyframes checkmarkFillFade {
        0%   { opacity: 0; }
        100% { opacity: 1; }
    }
    .checkmark-circle-stroke {
        fill: none;
        stroke: #1E3F20;
        stroke-width: 3;
        stroke-linecap: round;
        stroke-linejoin: round;
        stroke-dasharray: 157;
        stroke-dashoffset: 157;
        animation: checkmarkCircleDraw 0.7s cubic-bezier(0.6, 0, 0.35, 1) forwards;
    }
    @keyframes checkmarkCircleDraw {
        0%   { stroke-dashoffset: 157; }
        100% { stroke-dashoffset: 0; }
    }

    /* Centang putih — tergambar bertahap dengan bounce lembut */
    .checkmark-check {
        fill: none;
        stroke: #ffffff;
        stroke-width: 3.5;
        stroke-linecap: round;
        stroke-linejoin: round;
        stroke-dasharray: 38;
        stroke-dashoffset: 38;
        animation: checkmarkCheckDraw 0.4s cubic-bezier(0.3, 0, 0.1, 1.4) 0.52s forwards;
    }
    @keyframes checkmarkCheckDraw {
        0%   { stroke-dashoffset: 38; }
        70%  { stroke-dashoffset: -3; }
        100% { stroke-dashoffset: 0; }
    }

    /* Kilau singkat — shimmer tipis satu kali */
    .checkmark-shimmer {
        fill: none;
        stroke: rgba(255,255,255,0.45);
        stroke-width: 1.5;
        stroke-linecap: round;
        stroke-dasharray: 50 110;
        stroke-dashoffset: -160;
        animation: checkmarkShimmer 0.55s ease-in-out 1.0s forwards;
        opacity: 0;
    }
    @keyframes checkmarkShimmer {
        0%   { stroke-dashoffset: -160; opacity: 0; }
        20%  { opacity: 1; }
        80%  { opacity: 0.6; }
        100% { stroke-dashoffset: 0; opacity: 0; }
    }
</style>

{{-- Main Page Wrapper --}}
<div class="min-h-screen bg-[#F7F7F2] pb-32 sm:pb-16">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 pt-10 sm:pt-14">

        {{-- ===== SUCCESS HEADER ===== --}}
        <div class="text-center mb-8 no-print">
            {{-- Animated Success Checkmark — Elegan & Profesional --}}
            <div class="checkmark-wrapper mx-auto mb-5">
                <svg viewBox="0 0 60 60" width="72" height="72" fill="none" xmlns="http://www.w3.org/2000/svg">
                    {{-- Track ring (soft background circle) --}}
                    <circle class="checkmark-circle-track" cx="30" cy="30" r="27"/>
                    {{-- Solid fill circle (muncul cepat, lalu tergambar outline-nya) --}}
                    <circle class="checkmark-circle-fill" cx="30" cy="30" r="25.5"/>
                    {{-- Animated stroke circle (tergambar melingkar) --}}
                    <circle class="checkmark-circle-stroke" cx="30" cy="30" r="25"
                        transform="rotate(-90 30 30)"/>
                    {{-- Centang putih dengan bounce --}}
                    <path class="checkmark-check" d="M17 30.5l9 9L43 20"/>
                    {{-- Shimmer pass (kilau tipis satu kali) --}}
                    <circle class="checkmark-shimmer" cx="30" cy="30" r="23"
                        transform="rotate(-90 30 30)"/>
                </svg>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#1E3F20] font-display tracking-tight">
                @if($booking->booking_status === 'FULL_PAID')
                    Pembayaran Berhasil
                @else
                    Pembayaran DP Berhasil
                @endif
            </h1>
        </div>

        {{-- ===== E-TICKET CARD ===== --}}
        <div id="printArea">
        <div class="bg-white rounded-3xl border border-[#E2EAE3] shadow-xl overflow-hidden print-ticket" id="eticketCard">

            {{-- ----- TICKET HEADER (Dark green + mountain BG) ----- --}}
            <div class="ticket-header-bg p-5 sm:p-7 relative" style="min-height: 140px;">
                {{-- Foto Merapi: tampil di kanan, fade halus ke kiri via CSS mask --}}
                <div class="ticket-header-photo" style="background-image:url('{{ $merapiBg }}');"></div>
                {{-- Veil overlay: gradasi hijau Forest yang menjaga identitas visual --}}
                <div class="ticket-header-veil"></div>
                <div class="relative z-10 flex items-start justify-between">

                    {{-- Left: Brand --}}
                    <div>
                        <span class="block font-extrabold text-white text-base sm:text-lg leading-tight tracking-wide">JEEP WUKIRSARI</span>
                        <span class="block text-[10px] text-white/70 font-medium uppercase tracking-widest">Adventure Tour</span>
                    </div>

                    {{-- Right: status tidak ditampilkan di header untuk tampilan lebih bersih --}}

                </div>

                {{-- Bottom area: E-Ticket label & booking code --}}
                <div class="relative z-10 mt-5">
                    @if($booking->booking_status === 'FULL_PAID')
                    <span class="block text-[11px] font-semibold text-emerald-300 uppercase tracking-widest mb-0.5">E-TIKET WISATA RESMI</span>
                    <h2 class="text-xl sm:text-2xl font-extrabold text-white leading-tight">Selamat menikmati perjalanan Anda!</h2>
                    @else
                    <span class="block text-[11px] font-semibold text-amber-300 uppercase tracking-widest mb-0.5">BUKTI RESERVASI (DP)</span>
                    <h2 class="text-xl sm:text-2xl font-extrabold text-white leading-tight">Uang Muka Terkonfirmasi</h2>
                    @endif
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
                    <span class="font-bold text-[#19231A] text-sm">Informasi Pemesanan</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                    @php $assignment = $booking->assignments->first(); @endphp
                    @if($assignment)
                    <div class="sm:col-span-2 p-3.5 rounded-2xl bg-emerald-50/80 border border-emerald-200/80">
                        <span class="block text-[11px] font-semibold text-emerald-800 uppercase tracking-wider">Armada &amp; Driver Paguyuban</span>
                        <div class="font-bold text-emerald-950 text-xs sm:text-sm mt-0.5 flex flex-wrap items-center gap-x-2 gap-y-0.5">
                            <span>{{ $assignment->jeep?->model_name ?? 'Jeep Armada' }}</span>
                            <span class="text-emerald-400 font-normal">&bull;</span>
                            <span>Driver: {{ $assignment->driver?->full_name ?? 'Driver Paguyuban' }}</span>
                        </div>
                    </div>
                    @endif

                    {{-- Nama Pemesan --}}
                    <div class="info-row-item">
                        <div class="min-w-0">
                            <span class="block text-[10px] font-semibold text-[#7E8E81] uppercase tracking-wider">Nama Pemesan</span>
                            <span class="font-bold text-[#19231A] text-sm mt-0.5 block">{{ $booking->customer_name }}</span>
                        </div>
                    </div>

                    {{-- Paket Wisata --}}
                    <div class="info-row-item">
                        <div class="min-w-0">
                            <span class="block text-[10px] font-semibold text-[#7E8E81] uppercase tracking-wider">Paket Wisata</span>
                            <span class="font-bold text-[#19231A] text-sm mt-0.5 block">Paket {{ $booking->package->name }}</span>
                        </div>
                    </div>

                    {{-- Tanggal Wisata --}}
                    <div class="info-row-item">
                        <div class="min-w-0">
                            <span class="block text-[10px] font-semibold text-[#7E8E81] uppercase tracking-wider">Tanggal Wisata</span>
                            <span class="font-bold text-[#19231A] text-sm mt-0.5 block">{{ $booking->trip_date->translatedFormat('d F Y') }}</span>
                        </div>
                    </div>

                    {{-- Jumlah Peserta --}}
                    <div class="info-row-item">
                        <div class="min-w-0">
                            <span class="block text-[10px] font-semibold text-[#7E8E81] uppercase tracking-wider">Jumlah Peserta</span>
                            <span class="font-bold text-[#19231A] text-sm mt-0.5 block">{{ $booking->passenger_count }} Orang</span>
                        </div>
                    </div>

                    {{-- Jumlah Jeep --}}
                    <div class="info-row-item">
                        <div class="min-w-0">
                            <span class="block text-[10px] font-semibold text-[#7E8E81] uppercase tracking-wider">Jumlah Jeep</span>
                            <span class="font-bold text-[#19231A] text-sm mt-0.5 block">{{ $booking->jeep_quantity }} Jeep</span>
                        </div>
                    </div>

                    {{-- Jenis Kedatangan --}}
                    <div class="info-row-item">
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
                        <span class="font-bold text-[#19231A] text-sm">Ringkasan Pembayaran</span>
                    </div>
                    @if($booking->booking_status === 'FULL_PAID')
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-[#1E3F20] text-white text-[10px] font-bold">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        LUNAS (100%)
                    </span>
                    @else
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-amber-100 text-amber-800 text-[10px] font-bold">
                        UANG MUKA TERVERIFIKASI (DP)
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

            {{-- ----- QR CODE SECTION (Hanya tampil jika LUNAS) ----- --}}
            @if($booking->booking_status === 'FULL_PAID')
            <div class="px-5 sm:px-7 py-5">
                <div class="flex items-center gap-4 sm:gap-5">
                    {{-- QR Code --}}
                    <div class="flex-shrink-0">
                        <div class="w-28 h-28 sm:w-32 sm:h-32 bg-white border border-[#E2EAE3] rounded-2xl flex items-center justify-center p-2 shadow-xs">
                            @php
                                // QR Code → halaman publik /tiket/{code} (tidak butuh login)
                                // Customer scan: lihat status. Admin scan (sudah login admin): auto-redirect ke /admin/scan/{code}
                                // QR unik per booking, tetap sama dari DP sampai LUNAS
                                $qrData = route('ticket.show', $booking->booking_code);
                                $qrUrl  = 'https://api.qrserver.com/v1/create-qr-code/?size=240x240&data=' . urlencode($qrData) . '&color=1E3F20&bgcolor=FFFFFF&margin=4&format=png';
                            @endphp
                            <img src="{{ $qrUrl }}"
                                 crossorigin="anonymous"
                                 alt="QR Code Tiket {{ $booking->booking_code }}"
                                 class="w-full h-full object-contain rounded-xl"
                                 onerror="this.style.display='none'; document.getElementById('qrFallback').style.display='flex'">
                            <div id="qrFallback" class="hidden w-full h-full items-center justify-center">
                                <svg class="w-12 h-12 text-[#1E3F20]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- QR Info --}}
                    <div class="flex-1 min-w-0">
                        <span class="font-bold text-[#19231A] text-sm sm:text-base leading-snug block mb-1.5">
                            Verifikasi Tiket
                        </span>
                        <p class="text-xs sm:text-sm text-[#526054] leading-relaxed">
                            Tunjukkan QR E-Ticket ini saat tiba di basecamp untuk verifikasi booking.
                        </p>
                    </div>
                </div>
            </div>
            @else
            {{-- Pemberitahuan E-Tiket Ditangguhkan (Khusus DP) --}}
            <div class="px-5 sm:px-7 py-5">
                <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-xl bg-amber-100 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-4 h-4 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <span class="font-bold text-amber-950 text-sm block mb-1">
                                E-Tiket Tersedia Setelah Pembayaran Lunas
                            </span>
                            <p class="text-xs text-amber-900 leading-relaxed">
                                Pembayaran uang muka (DP) Anda sebesar <strong>Rp{{ number_format($booking->paid_amount, 0, ',', '.') }}</strong> telah kami terima. E-Tiket resmi beserta QR Code verifikasi akan otomatis terbit setelah sisa tagihan sebesar <strong>Rp{{ number_format($booking->remaining_amount, 0, ',', '.') }}</strong> dilunasi di loket kasir basecamp saat kedatangan.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- ----- BASECAMP LOCATION ----- --}}
            <div class="px-5 sm:px-7 pb-5">
                <div class="flex items-center justify-between p-3.5 rounded-2xl bg-[#F7F7F2] border border-[#E2EAE3]">
                    <div class="min-w-0">
                        <span class="font-bold text-[#19231A] text-xs block">Basecamp Jeep Wukirsari</span>
                        <span class="text-[11px] text-[#526054] block truncate">Kapanewon Cangkringan, Sleman, D.I. Yogyakarta</span>
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
            @if($booking->booking_status === 'FULL_PAID')
            {{-- Simpan E-Ticket Langsung PNG --}}
            <button type="button"
                    onclick="downloadTicketPNG()"
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full border border-[#E2EAE3] bg-white text-[#19231A] text-sm font-semibold hover:bg-[#F7F7F2] transition-colors shadow-sm focus:outline-none cursor-pointer">
                <svg class="w-4 h-4 text-[#1E3F20]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                <span>Simpan E-Ticket (PNG)</span>
            </button>
            @endif

            {{-- WhatsApp --}}
            <a href="{{ $waLink }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-[#1E3F20] hover:bg-[#265029] text-white text-sm font-semibold transition-colors shadow-sm">
                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                    <path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0012.04 2zm4.98 14.07c-.21.59-1.21 1.14-1.66 1.21-.45.07-.99.1-1.6-.1-.37-.11-.84-.27-1.44-.53-2.52-1.09-4.17-3.6-4.3-3.77-.13-.17-1.06-1.41-1.06-2.69 0-1.28.67-1.91.9-2.17.24-.26.52-.32.69-.32l.5.01c.16.01.37-.06.58.44l.75 1.84c.07.17.11.36.02.58l-.27.52-.38.49c-.13.16-.27.33-.12.65.16.32.69 1.14 1.49 1.84.72.63 1.42.95 1.72 1.06.3.11.47.09.64-.05.17-.14.73-.85.93-1.14.2-.29.4-.24.67-.14l1.9.9c.3.14.5.21.57.33.07.11.07.65-.14 1.24z"/>
                </svg>
                <span>Konfirmasi WhatsApp</span>
            </a>

            {{-- Kembali Beranda --}}
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full border border-[#E2EAE3] bg-white text-[#19231A] text-sm font-semibold hover:bg-[#F7F7F2] transition-colors shadow-sm">
                <svg class="w-4 h-4 text-[#526054]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span>Kembali ke Beranda</span>
            </a>
        </div>

    </div>{{-- end max-w-2xl --}}
</div>{{-- end min-h-screen --}}

{{-- ===== STICKY BOTTOM BAR (Mobile) ===== --}}
<div class="sticky-bar sm:hidden no-print" id="stickyActionBar">
    <div class="flex items-center gap-2">
        @if($booking->booking_status === 'FULL_PAID')
        {{-- Tombol Simpan E-Ticket Langsung PNG --}}
        <button type="button"
                id="btnSaveMobile"
                onclick="downloadTicketPNG()"
                class="flex-1 min-h-[44px] inline-flex items-center justify-center gap-1.5 py-2.5 px-3 rounded-full border border-[#E2EAE3] bg-white text-[#19231A] text-xs font-semibold hover:bg-[#F7F7F2] active:bg-[#E8EFE9] transition-colors shadow-xs">
            <svg class="w-4 h-4 text-[#1E3F20] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            <span>Simpan</span>
        </button>
        @endif

        {{-- WhatsApp --}}
        <a href="{{ $waLink }}" target="_blank" rel="noopener noreferrer" class="flex-[1.5] min-h-[44px] inline-flex items-center justify-center gap-1.5 py-2.5 px-3 rounded-full bg-[#1E3F20] hover:bg-[#265029] active:bg-[#142B16] text-white text-xs font-bold transition-colors shadow-xs">
            <svg class="w-3.5 h-3.5 fill-current shrink-0" viewBox="0 0 24 24">
                <path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0012.04 2zm4.98 14.07c-.21.59-1.21 1.14-1.66 1.21-.45.07-.99.1-1.6-.1-.37-.11-.84-.27-1.44-.53-2.52-1.09-4.17-3.6-4.3-3.77-.13-.17-1.06-1.41-1.06-2.69 0-1.28.67-1.91.9-2.17.24-.26.52-.32.69-.32l.5.01c.16.01.37-.06.58.44l.75 1.84c.07.17.11.36.02.58l-.27.52-.38.49c-.13.16-.27.33-.12.65.16.32.69 1.14 1.49 1.84.72.63 1.42.95 1.72 1.06.3.11.47.09.64-.05.17-.14.73-.85.93-1.14.2-.29.4-.24.67-.14l1.9.9c.3.14.5.21.57.33.07.11.07.65-.14 1.24z"/>
            </svg>
            <span class="truncate">WhatsApp</span>
        </a>

        {{-- Beranda --}}
        <a href="{{ route('home') }}" class="flex-1 min-h-[44px] inline-flex items-center justify-center gap-1 py-2.5 px-3 rounded-full border border-[#E2EAE3] bg-white text-[#19231A] text-xs font-semibold hover:bg-[#F7F7F2] active:bg-[#E8EFE9] transition-colors shadow-xs">
            <svg class="w-3.5 h-3.5 text-[#526054] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span>Beranda</span>
        </a>
    </div>
</div>

{{-- Library html2canvas via CDN untuk unduh PNG --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

{{-- Inline script — penanganan unduh tiket PNG --}}
<script>
    /**
     * downloadTicketPNG() - Tangkap kartu e-ticket utuh dan unduh sebagai file PNG
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
                alert('Gagal membuat gambar PNG tiket. Silakan coba kembali.');
            });
        }

        if (typeof html2canvas === 'undefined') {
            var s = document.createElement('script');
            s.src = 'https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js';
            s.onload = runCapture;
            s.onerror = function() {
                alert('Tidak dapat memuat modul pengunduh gambar. Silakan periksa koneksi internet Anda.');
            };
            document.head.appendChild(s);
        } else {
            runCapture();
        }
    }
</script>

@endsection
