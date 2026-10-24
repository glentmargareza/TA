@extends('layouts.app')

@section('title', 'E-Tiket ' . $booking->booking_code . ' | Jeep Wukirsari')

@section('content')
<div class="py-12 bg-wukirsari-cream min-h-screen">
    <div class="max-w-2xl mx-auto px-4 sm:px-6">
        
        <!-- Header Sukses -->
        <div class="text-center space-y-3 mb-8">
            <div class="w-16 h-16 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto shadow-sm text-3xl">
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
            
            <!-- Header Hijau Tiket -->
            <div class="bg-wukirsari-dark text-white p-6 sm:p-8 flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-medium text-sunrise-gold uppercase tracking-wider block">
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
                
                <div class="grid grid-cols-2 gap-4 text-xs sm:text-sm">
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
                        <span class="font-bold inline-block px-2.5 py-0.5 rounded-full text-xs mt-0.5 bg-emerald-100 text-emerald-800">
                            LUNAS (100%)
                        </span>
                        @else
                        <span class="font-bold inline-block px-2.5 py-0.5 rounded-full text-xs mt-0.5 bg-amber-100 text-amber-800">
                            UANG MUKA (DP 30%)
                        </span>
                        @endif
                    </div>

                    <div>
                        <span class="text-merapiText-muted block">Jenis Kedatangan:</span>
                        <span class="font-semibold text-merapiText-primary">
                            {{ $booking->arrival_type === 'self_drive' ? 'Datang Sendiri' : 'Jemputan Shuttle' }}
                        </span>
                    </div>
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
                        <span class="text-amber-800 block">Sisa Bayar di Loket</span>
                        <span class="font-extrabold text-amber-800 text-base sm:text-lg">
                            Rp {{ number_format($booking->remaining_amount, 0, ',', '.') }}
                        </span>
                    </div>
                    @endif
                </div>

                <!-- QR Code Dummy / Display -->
                <div class="flex flex-col sm:flex-row items-center justify-between p-4 border border-wukirsari-border rounded-2xl gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-20 h-20 bg-slate-100 rounded-xl border border-wukirsari-border flex items-center justify-center p-2 text-3xl font-mono">
                            🏁
                        </div>
                        <div class="text-xs text-merapiText-secondary">
                            <span class="font-bold text-merapiText-primary block text-sm">Validasi Loket Check-in</span>
                            <span>Tunjukkan tiket ini kepada petugas loket pangkalan Jeep Wukirsari untuk pelunasan & naik armada.</span>
                        </div>
                    </div>
                </div>

                <!-- Alamat Titik Kumpul -->
                <div class="flex items-start gap-3 text-xs text-merapiText-secondary bg-slate-50 p-3.5 rounded-xl border border-wukirsari-border">
                    <span class="text-sunrise-gold text-base">📍</span>
                    <div>
                        <span class="font-bold text-merapiText-primary block">Lokasi Basecamp Jeep Wukirsari:</span>
                        <span>Jl. Cangkringan Km. 18, Wukirsari, Sleman, D.I. Yogyakarta (Tersedia parkir bus & mobil luas).</span>
                    </div>
                </div>

            </div>

            <!-- Tombol Aksi -->
            <div class="p-6 bg-slate-50 border-t border-wukirsari-border flex flex-col sm:flex-row items-center justify-between gap-3">
                <button onclick="window.print()" class="w-full sm:w-auto px-6 py-2.5 rounded-full border border-wukirsari-border bg-white text-xs sm:text-sm font-semibold text-merapiText-primary hover:bg-slate-100 flex items-center justify-center gap-2">
                    🖨️ Simpan / Cetak Tiket
                </button>

                @php
                    $waMsg = "Halo Admin Jeep Wukirsari, saya telah memesan tiket online dengan Kode: {$booking->booking_code} atas nama {$booking->customer_name}.";
                    $waLink = "https://wa.me/6281234567890?text=" . urlencode($waMsg);
                @endphp
                <a href="{{ $waLink }}" target="_blank" class="w-full sm:w-auto px-6 py-2.5 rounded-full bg-emerald-600 hover:bg-emerald-700 text-white text-xs sm:text-sm font-semibold flex items-center justify-center gap-2 shadow-sm">
                    💬 Konfirmasi ke WhatsApp CS
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
@endsection
