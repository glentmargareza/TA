@extends('layouts.app')

@section('title', 'E-Tiket ' . $booking->booking_code . ' | Jeep Wukirsari')

@section('content')
<div class="py-12 bg-wukirsari-cream min-h-screen">
    <div class="max-w-2xl mx-auto px-4 sm:px-6">
        
        <!-- Header Sukses -->
        <div class="text-center space-y-3 mb-8">
            <div class="w-16 h-16 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto shadow-sm text-3xl font-bold">
                ✓
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-merapiText-primary font-display">
                Pemesanan Berhasil Disimpan!
            </h1>
            <p class="text-merapiText-secondary text-sm max-w-md mx-auto">
                Terima kasih telah memesan petualangan bersama Jeep Wukirsari. Tunjukkan e-tiket di bawah ini kepada petugas loket saat tiba di Basecamp.
            </p>
        </div>

        <!-- E-Ticket Card Design -->
        <div class="bg-white rounded-3xl border border-wukirsari-border shadow-lg overflow-hidden relative">
            
            <!-- Header Tiket -->
            <div class="bg-wukirsari-dark text-white p-6 sm:p-8 flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-medium text-wukirsari-sage uppercase tracking-wider block">
                        E-TIKET WISATA RESMI
                    </span>
                    <h2 class="text-xl sm:text-2xl font-extrabold font-display">
                        JEEP WUKIRSARI
                    </h2>
                    <span class="text-xs text-white/70">Kawasan Lereng Gunung Merapi</span>
                </div>

                <div class="text-right">
                    <span class="text-xs text-white/60 block">Kode Booking</span>
                    <span class="text-lg sm:text-xl font-mono font-bold text-white tracking-wider">
                        {{ $booking->booking_code }}
                    </span>
                </div>
            </div>

            <!-- Garis Gigi Perforasi -->
            <div class="relative h-6 bg-white flex items-center justify-between px-2">
                <div class="w-4 h-8 rounded-r-full bg-wukirsari-cream -ml-4"></div>
                <div class="flex-1 border-b-2 border-dashed border-wukirsari-border mx-3"></div>
                <div class="w-4 h-8 rounded-l-full bg-wukirsari-cream -mr-4"></div>
            </div>

            <!-- Detail Tiket -->
            <div class="p-6 sm:p-8 space-y-6">
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 text-xs sm:text-sm">
                    <div>
                        <span class="text-merapiText-muted block">Nama Pemesan:</span>
                        <span class="font-bold text-merapiText-primary text-sm sm:text-base">{{ $booking->customer_name }}</span>
                    </div>

                    <div>
                        <span class="text-merapiText-muted block">Paket Wisata:</span>
                        <span class="font-bold text-wukirsari-primary text-sm sm:text-base">Paket {{ $booking->package->name }}</span>
                    </div>

                    <div>
                        <span class="text-merapiText-muted block">Tanggal & Jam:</span>
                        <span class="font-bold text-merapiText-primary">{{ $booking->trip_date->format('d M Y') }} ({{ $booking->slot->slot_label }})</span>
                    </div>

                    <div>
                        <span class="text-merapiText-muted block">Peserta & Armada:</span>
                        <span class="font-bold text-merapiText-primary">{{ $booking->passenger_count }} Orang ({{ $booking->jeep_quantity }} Jeep)</span>
                    </div>

                    <div>
                        <span class="text-merapiText-muted block">Status Pembayaran:</span>
                        @if($booking->booking_status === 'FULL_PAID')
                        <span class="font-bold inline-block px-2.5 py-0.5 rounded-full text-xs mt-0.5 bg-emerald-600 text-white">
                            LUNAS (100%)
                        </span>
                        @else
                        <span class="font-bold inline-block px-2.5 py-0.5 rounded-full text-xs mt-0.5 bg-wukirsari-sage text-wukirsari-primary">
                            UANG MUKA TERVERIFIKASI (DP)
                        </span>
                        @endif
                    </div>

                    <div>
                        <span class="text-merapiText-muted block">Jenis Kedatangan:</span>
                        <span class="font-semibold text-merapiText-primary">
                            {{ $booking->arrival_type === 'self_drive' ? 'Datang Sendiri' : 'Jemputan Shuttle' }}
                        </span>
                    </div>

                    @php $assignment = $booking->assignments->first(); @endphp
                    @if($assignment)
                    <div class="sm:col-span-2 p-2.5 rounded-xl bg-emerald-50/70 border border-emerald-200/80">
                        <span class="text-emerald-800 text-xs font-semibold block">Armada & Driver Paguyuban:</span>
                        <span class="font-bold text-emerald-950 text-xs sm:text-sm flex items-center gap-1.5 mt-0.5">
                            <svg class="w-4 h-4 text-emerald-800 shrink-0 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                            <span>{{ $assignment->jeep?->model_name ?? 'Jeep Armada' }} &bull; Driver: {{ $assignment->driver?->full_name ?? 'Driver Paguyuban' }}</span>
                        </span>
                    </div>
                    @endif
                </div>

                <!-- Box Finansial -->
                <div class="p-4 rounded-2xl bg-wukirsari-sage/40 border border-wukirsari-border flex items-center justify-between text-xs sm:text-sm">
                    <div>
                        <span class="text-merapiText-secondary block">Jumlah Terbayar</span>
                        <span class="font-extrabold text-wukirsari-primary text-base sm:text-lg">
                            Rp {{ number_format($booking->paid_amount, 0, ',', '.') }}
                        </span>
                    </div>
                    @if($booking->remaining_amount > 0)
                    <div class="text-right">
                        <span class="text-slate-600 block">Sisa Bayar di Loket</span>
                        <span class="font-extrabold text-slate-800 text-base sm:text-lg">
                            Rp {{ number_format($booking->remaining_amount, 0, ',', '.') }}
                        </span>
                    </div>
                    @endif
                </div>

                <!-- QR Code Display -->
                <div class="flex flex-col sm:flex-row items-center justify-between p-4 sm:p-5 border border-wukirsari-border rounded-2xl gap-4 bg-emerald-50/40">
                    <div class="flex flex-col sm:flex-row items-center gap-4 text-center sm:text-left">
                        <div id="qrcode" class="w-24 h-24 bg-white rounded-xl border border-wukirsari-border flex items-center justify-center p-1.5 shadow-2xs shrink-0">
                            <!-- Fallback QR code -->
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ urlencode($booking->qr_token ?? $booking->booking_code) }}" alt="QR E-Tiket" class="w-full h-full object-contain">
                        </div>
                        <div class="space-y-1">
                            <div class="flex items-center justify-center sm:justify-start gap-2 flex-wrap">
                                <span class="font-bold text-merapiText-primary text-sm">Validasi Loket Check-in</span>
                                @if($booking->booking_status === 'FULL_PAID')
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-600 text-white shadow-2xs">LUNAS 100%</span>
                                @elseif($booking->booking_status === 'DP_PAID')
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-500 text-white shadow-2xs">DP TERVERIFIKASI</span>
                                @endif
                            </div>
                            <p class="text-xs text-merapiText-secondary max-w-sm leading-relaxed">
                                @if($booking->booking_status === 'DP_PAID')
                                Tunjukkan QR Code ini kepada petugas loket kasir Basecamp Jeep Wukirsari untuk pelunasan sisa tagihan <strong class="text-slate-900 font-extrabold">Rp {{ number_format($booking->remaining_amount, 0, ',', '.') }}</strong> sebelum keberangkatan.
                                @else
                                Tunjukkan QR Code ini kepada petugas loket pangkalan Jeep Wukirsari untuk verifikasi check-in dan keberangkatan armada.
                                @endif
                            </p>
                            <span class="inline-block text-[10px] font-mono font-bold text-slate-500 bg-white px-2 py-0.5 rounded border border-slate-200">
                                Token: {{ $booking->qr_token ?? $booking->booking_code }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Alamat Titik Kumpul -->
                <div class="flex items-start gap-3 text-xs text-merapiText-secondary bg-slate-50 p-3.5 rounded-xl border border-wukirsari-border">
                    <span class="text-emerald-500 text-base shrink-0 mt-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </span>
                    <div>
                        <span class="font-bold text-merapiText-primary block">Lokasi Basecamp Jeep Wukirsari:</span>
                        <span>Jl. Cangkringan Km. 18, Wukirsari, Sleman, D.I. Yogyakarta (Tersedia parkir bus & mobil luas).</span>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="p-6 bg-slate-50 border-t border-wukirsari-border flex flex-col sm:flex-row items-center justify-between gap-3">
                <button onclick="window.print()" class="w-full sm:w-auto px-6 py-2.5 rounded-full border border-wukirsari-border bg-white text-xs sm:text-sm font-semibold text-merapiText-primary hover:bg-slate-100 flex items-center justify-center gap-2 transition-colors">
                    <svg class="w-4 h-4 text-merapiText-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>Simpan / Cetak Tiket</span>
                </button>

                @php
                    $waMsg = "Halo Admin Jeep Wukirsari, saya telah memesan tiket online dengan Kode: {$booking->booking_code} atas nama {$booking->customer_name}.";
                    $csPhoneClean = preg_replace('/[^\d]/', '', \App\Services\BasecampService::getSettings()['cs_phone'] ?? '6281234567890');
                    if (str_starts_with($csPhoneClean, '0')) {
                        $csPhoneClean = '62' . substr($csPhoneClean, 1);
                    }
                    $waLink = "https://wa.me/{$csPhoneClean}?text=" . urlencode($waMsg);
                @endphp
                <a href="{{ $waLink }}" target="_blank" class="w-full sm:w-auto px-6 py-2.5 rounded-full bg-emerald-600 hover:bg-emerald-700 text-white text-xs sm:text-sm font-semibold flex items-center justify-center gap-2 shadow-sm transition-all">
                    <svg class="w-4 h-4 fill-current text-white" viewBox="0 0 24 24"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0012.04 2z"/></svg>
                    <span>Konfirmasi ke WhatsApp CS</span>
                </a>
            </div>

        </div>

        <div class="mt-8 text-center">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-semibold text-wukirsari-primary hover:text-wukirsari-hover transition-colors">
                ← Kembali ke Halaman Beranda
            </a>
        </div>

    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const qrContainer = document.getElementById('qrcode');
        const token = "{{ $booking->qr_token ?? $booking->booking_code }}";
        if (qrContainer && typeof QRCode !== 'undefined' && token) {
            qrContainer.innerHTML = '';
            new QRCode(qrContainer, {
                text: token,
                width: 84,
                height: 84,
                colorDark : "#0D2714",
                colorLight : "#ffffff",
                correctLevel : QRCode.CorrectLevel.M
            });
        }
    });
</script>
@endsection
