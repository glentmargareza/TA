@extends('layouts.app')

@section('title', 'Paket ' . $package->name . ' | Jeep Wukirsari')

@section('content')
<div class="py-12 bg-wukirsari-cream min-h-screen">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <a href="{{ route('home') }}#paket" class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-semibold text-wukirsari-primary hover:text-wukirsari-hover transition-colors mb-6">
            ← Kembali ke Daftar Paket
        </a>

        <div class="bg-white rounded-3xl border border-wukirsari-border overflow-hidden shadow-sm">
            <div class="relative aspect-[16/8] sm:aspect-[21/9] overflow-hidden">
                <img src="{{ $package->cover_image }}" alt="{{ $package->name }}" class="w-full h-full object-cover">
                @if($package->category_badge)
                <span class="absolute top-4 left-4 px-4 py-1.5 rounded-full bg-wukirsari-primary text-white text-xs font-semibold shadow-md">
                    {{ $package->category_badge }}
                </span>
                @endif
            </div>

            <div class="p-6 sm:p-10 space-y-8">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-wukirsari-border pb-6">
                    <div>
                        <h1 class="text-2xl sm:text-4xl font-extrabold text-merapiText-primary font-display">
                            Paket {{ $package->name }}
                        </h1>
                        <p class="text-merapiText-secondary text-sm mt-1">
                            Jeep Wukirsari Adventure Tour - Lereng Gunung Merapi
                        </p>
                    </div>

                    <div>
                        <span class="text-xs text-merapiText-muted block">Tarif Sewa</span>
                        <div class="flex items-baseline gap-1">
                            <span class="text-2xl sm:text-3xl font-extrabold text-merapiText-primary font-display">
                                Rp {{ number_format($package->price_per_jeep, 0, ',', '.') }}
                            </span>
                            <span class="text-xs text-merapiText-secondary font-medium">/ Jeep</span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    <div class="p-4 rounded-2xl bg-wukirsari-sage/40 border border-wukirsari-border">
                        <span class="text-xs text-merapiText-muted block">Estimasi Durasi</span>
                        <span class="text-sm font-bold text-merapiText-primary block mt-1">🕒 {{ $package->duration_minutes }} Menit</span>
                    </div>

                    <div class="p-4 rounded-2xl bg-wukirsari-sage/40 border border-wukirsari-border">
                        <span class="text-xs text-merapiText-muted block">Kapasitas Maksimal</span>
                        <span class="text-sm font-bold text-merapiText-primary block mt-1">👥 Max 4 Orang / Jeep</span>
                    </div>

                    <div class="col-span-2 sm:col-span-1 p-4 rounded-2xl bg-wukirsari-sage/40 border border-wukirsari-border">
                        <span class="text-xs text-merapiText-muted block">Asuransi Wisata</span>
                        <span class="text-sm font-bold text-merapiText-primary block mt-1">🛡️ Termasuk (Gratis)</span>
                    </div>
                </div>

                <div class="space-y-3">
                    <h3 class="text-lg font-bold text-merapiText-primary font-display">Deskripsi Rute Perjalanan</h3>
                    <p class="text-merapiText-secondary text-sm sm:text-base leading-relaxed">
                        {{ $package->description }} Menyusuri kawasan saksi bisu erupsi Gunung Merapi dengan jalur bebatuan, pasir vulkanik, spot foto pemandangan terbaik, dan aksi susur air di aliran Sungai Kalikuning yang memacu adrenalin namun tetap terjamin keamanannya.
                    </p>
                </div>

                <div class="space-y-3">
                    <h3 class="text-lg font-bold text-merapiText-primary font-display">Fasilitas yang Termasuk (Include)</h3>
                    <ul class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm text-merapiText-secondary">
                        <li class="flex items-center gap-2">✅ Driver jeep profesional & pemandu lokal</li>
                        <li class="flex items-center gap-2">✅ Bahan bakar minyak (BBM) & armada 4x4 prima</li>
                        <li class="flex items-center gap-2">✅ Tiket masuk seluruh objek destinasi paket</li>
                        <li class="flex items-center gap-2">✅ Asuransi keselamatan perjalanan wisata</li>
                        <li class="flex items-center gap-2">✅ Helm pelindung & masker debu lahar</li>
                    </ul>
                </div>

                <div class="pt-6 border-t border-wukirsari-border flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div>
                        <span class="text-xs text-merapiText-muted block">Siap berpetualang?</span>
                        <span class="text-sm font-semibold text-merapiText-primary">Pemesanan online instan dengan opsi DP 30% atau Bayar Penuh.</span>
                    </div>

                    <a href="{{ route('booking.create', ['package' => $package->slug]) }}" class="w-full sm:w-auto px-8 py-3.5 rounded-full bg-wukirsari-primary hover:bg-wukirsari-hover text-white font-semibold text-sm flex items-center justify-center gap-2 shadow-md shadow-wukirsari-primary/20 hover:scale-[1.02] transition-all">
                        <span>Pesan Paket Ini Sekarang →</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
