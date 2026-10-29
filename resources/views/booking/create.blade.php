@extends('layouts.app')

@section('title', 'Formulir Pemesanan Jeep | Jeep Wukirsari')

@section('content')
<div class="py-12 bg-wukirsari-cream min-h-screen">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8">
            <span class="text-xs font-bold uppercase tracking-wider text-wukirsari-primary font-display block mb-1">
                FORMULIR RESERVASI
            </span>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-merapiText-primary font-display">
                Pemesanan Jeep Wukirsari
            </h1>
            <p class="text-merapiText-secondary text-sm mt-1">
                Pilih paket, jadwal keberangkatan, dan opsi pembayaran aman (Full atau Uang Muka DP 30%).
            </p>
        </div>

        <form action="{{ route('booking.store') }}" method="POST" id="bookingForm" class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            @csrf

            <!-- Kolom Kiri: Input Formulir -->
            <div class="lg:col-span-7 space-y-6">
                
                <!-- 1. Pilih Paket -->
                <div class="bg-white p-6 rounded-3xl border border-wukirsari-border shadow-xs space-y-4">
                    <h3 class="text-base font-bold text-merapiText-primary font-display flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-wukirsari-sage text-wukirsari-primary text-xs flex items-center justify-center font-bold">1</span>
                        <span>Pilih Paket Wisata</span>
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($packages as $pkg)
                        <label class="p-3.5 rounded-2xl border cursor-pointer transition-all flex flex-col justify-between package-radio-label {{ $pkg->id == $selectedPackage->id ? 'border-wukirsari-primary bg-wukirsari-sage/40 ring-1 ring-wukirsari-primary' : 'border-wukirsari-border hover:bg-slate-50' }}">
                            <input type="radio" name="package_id" value="{{ $pkg->id }}" data-price="{{ $pkg->price_per_jeep }}" {{ $pkg->id == $selectedPackage->id ? 'checked' : '' }} class="hidden package-radio">
                            <div>
                                <span class="block text-xs font-bold text-merapiText-primary">{{ $pkg->name }}</span>
                                <span class="block text-xs font-bold text-wukirsari-primary mt-1">Rp {{ number_format($pkg->price_per_jeep, 0, ',', '.') }}</span>
                            </div>
                            <span class="flex items-center gap-1 text-[11px] text-merapiText-muted mt-2">
                                <svg class="w-3 h-3 text-merapiText-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>{{ $pkg->duration_text ?? ($pkg->duration_minutes . ' menit') }}</span>
                            </span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- 2. Jadwal & Sesi Jam -->
                <div class="bg-white p-6 rounded-3xl border border-wukirsari-border shadow-xs space-y-4">
                    <h3 class="text-base font-bold text-merapiText-primary font-display flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-wukirsari-sage text-wukirsari-primary text-xs flex items-center justify-center font-bold">2</span>
                        <span>Pilih Tanggal & Jam Trip</span>
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-merapiText-primary mb-1.5">Tanggal Kunjungan</label>
                            <input type="date" name="trip_date" id="trip_date" min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d', strtotime('+1 day')) }}" required class="w-full px-4 py-2.5 rounded-xl border border-wukirsari-border bg-white text-sm focus:outline-none focus:border-wukirsari-primary">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-merapiText-primary mb-1.5">Sesi Waktu Keberangkatan</label>
                            <select name="slot_id" id="slot_id" class="w-full px-4 py-2.5 rounded-xl border border-wukirsari-border bg-white text-sm focus:outline-none focus:border-wukirsari-primary">
                                @foreach($slots as $slot)
                                <option value="{{ $slot->id }}">{{ $slot->slot_label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- 3. Kapasitas & Jumlah Jeep -->
                <div class="bg-white p-6 rounded-3xl border border-wukirsari-border shadow-xs space-y-4">
                    <h3 class="text-base font-bold text-merapiText-primary font-display flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-wukirsari-sage text-wukirsari-primary text-xs flex items-center justify-center font-bold">3</span>
                        <span>Kapasitas & Jumlah Jeep</span>
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-merapiText-primary mb-1.5">Jumlah Penumpang (Orang)</label>
                            <div class="flex items-center gap-3">
                                <button type="button" id="btnDecPax" class="w-10 h-10 rounded-xl border border-wukirsari-border bg-slate-50 hover:bg-wukirsari-sage font-bold">-</button>
                                <input type="number" name="passenger_count" id="passenger_count" value="4" min="1" max="50" class="w-12 text-center text-base font-bold text-merapiText-primary border-none bg-transparent">
                                <button type="button" id="btnIncPax" class="w-10 h-10 rounded-xl border border-wukirsari-border bg-slate-50 hover:bg-wukirsari-sage font-bold">+</button>
                            </div>
                            <span class="block text-[11px] text-merapiText-muted mt-1.5">*Maksimal 4 penumpang per armada jeep.</span>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-merapiText-primary mb-1.5">Jumlah Armada Jeep</label>
                            <div class="flex items-center gap-3">
                                <button type="button" id="btnDecJeep" class="w-10 h-10 rounded-xl border border-wukirsari-border bg-slate-50 hover:bg-wukirsari-sage font-bold">-</button>
                                <input type="number" name="jeep_quantity" id="jeep_quantity" value="1" min="1" max="20" class="w-12 text-center text-base font-bold text-wukirsari-primary border-none bg-transparent">
                                <button type="button" id="btnIncJeep" class="w-10 h-10 rounded-xl border border-wukirsari-border bg-slate-50 hover:bg-wukirsari-sage font-bold">+</button>
                            </div>
                            <span class="block text-[11px] text-wukirsari-primary font-medium mt-1.5" id="jeepSummaryText">1 Jeep × Rp 450.000</span>
                        </div>
                    </div>
                </div>

                <!-- 4. Opsi Kedatangan & Kontak -->
                <div class="bg-white p-6 rounded-3xl border border-wukirsari-border shadow-xs space-y-4">
                    <h3 class="text-base font-bold text-merapiText-primary font-display flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-wukirsari-sage text-wukirsari-primary text-xs flex items-center justify-center font-bold">4</span>
                        <span>Opsi Kedatangan & Kontak</span>
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 sm:gap-3">
                        <label class="p-3 rounded-2xl border text-xs font-semibold text-center cursor-pointer transition-all arrival-label border-wukirsari-primary bg-wukirsari-sage/40 text-wukirsari-primary">
                            <input type="radio" name="arrival_type" value="self_drive" checked class="hidden arrival-radio">
                            Datang Sendiri ke Basecamp
                        </label>
                        <label class="p-3 rounded-2xl border text-xs font-semibold text-center cursor-pointer transition-all arrival-label border-wukirsari-border text-merapiText-secondary">
                            <input type="radio" name="arrival_type" value="pickup" class="hidden arrival-radio">
                            Layanan Antar-Jemput (Shuttle)
                        </label>
                    </div>

                    <!-- Pickup Box -->
                    <div id="pickupBox" class="hidden p-4 bg-wukirsari-sage/30 rounded-2xl border border-wukirsari-border space-y-3">
                        <div class="flex items-center justify-between gap-2">
                            <label class="block text-xs font-semibold text-merapiText-primary">Pilih Zona Penjemputan</label>
                            <button type="button" id="btnDetectLocation" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[11px] font-semibold bg-white hover:bg-wukirsari-sage text-wukirsari-primary border border-wukirsari-border shadow-xs hover:border-wukirsari-primary transition-all cursor-pointer active:scale-95">
                                <svg id="detectSpinner" class="hidden animate-spin w-3.5 h-3.5 text-wukirsari-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                </svg>
                                <svg id="detectIcon" class="w-3.5 h-3.5 text-wukirsari-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span id="detectText">Deteksi Lokasi Saya</span>
                            </button>
                        </div>

                        <!-- Notifikasi Status Lokasi GPS -->
                        <div id="geoNotice" class="hidden text-xs p-3 rounded-xl border transition-all"></div>

                        <!-- Dropdown Pilihan Zona Manual (Otomatis disembunyikan jika menggunakan GPS) -->
                        <div id="pickupZoneSelectWrapper" class="transition-all">
                            <label class="block text-[11px] font-medium text-merapiText-secondary mb-1">Pilih Zona Penjemputan:</label>
                            <select name="pickup_zone_id" id="pickup_zone_id" class="w-full px-3 py-2.5 rounded-xl border border-wukirsari-border bg-white text-xs text-merapiText-primary focus:outline-none focus:border-wukirsari-primary">
                                @foreach($pickupZones as $zone)
                                <option value="{{ $zone->id }}" data-fee="{{ $zone->additional_fee }}">
                                    {{ $zone->zone_name }} (+Rp {{ number_format($zone->additional_fee, 0, ',', '.') }})
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-[11px] font-medium text-merapiText-secondary mb-1">Alamat Penjemputan / Patokan:</label>
                            <input type="text" name="pickup_address" id="pickup_address" placeholder="Contoh: Hotel Tentrem / Jl. Palagan Km 7 / Nama Villa..." class="w-full px-3 py-2.5 rounded-xl border border-wukirsari-border bg-white text-xs text-merapiText-primary focus:outline-none focus:border-wukirsari-primary">
                        </div>
                    </div>

                    <!-- Kontak Pemesan -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                        <div>
                            <label class="block text-xs font-semibold text-merapiText-primary mb-1">Nama Lengkap Pemesan *</label>
                            <input type="text" name="customer_name" required placeholder="Contoh: Budi Santoso" class="w-full px-3.5 py-2.5 rounded-xl border border-wukirsari-border text-xs focus:outline-none focus:border-wukirsari-primary">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-merapiText-primary mb-1">Nomor WhatsApp Aktif *</label>
                            <input type="tel" name="customer_phone" required placeholder="Contoh: 081234567890" class="w-full px-3.5 py-2.5 rounded-xl border border-wukirsari-border text-xs focus:outline-none focus:border-wukirsari-primary">
                            <span class="block text-[10px] text-merapiText-muted mt-1">*Tiket QR Code dikirim ke nomor ini.</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Kolom Kanan: Rincian Tagihan -->
            <div class="lg:col-span-5 space-y-6">
                <div class="bg-white p-6 sm:p-8 rounded-3xl border border-wukirsari-border shadow-sm space-y-6 sticky top-28">
                    <h3 class="text-lg font-bold text-merapiText-primary font-display border-b border-wukirsari-border pb-3">
                        Ringkasan Tagihan
                    </h3>

                    <div class="space-y-3 text-xs sm:text-sm">
                        <div class="flex justify-between text-merapiText-secondary">
                            <span id="labelPackageSubtotal">Sewa Jeep</span>
                            <span class="font-semibold text-merapiText-primary" id="valPackageSubtotal">Rp 450.000</span>
                        </div>

                        <div class="flex justify-between text-merapiText-secondary">
                            <span>Layanan Penjemputan</span>
                            <span class="font-semibold text-merapiText-primary" id="valPickupFee">Rp 0</span>
                        </div>

                        <div class="flex justify-between text-merapiText-secondary">
                            <span>Asuransi & BBM</span>
                            <span class="font-semibold text-emerald-700">Gratis (Termasuk)</span>
                        </div>

                        <div class="pt-3 border-t border-wukirsari-border flex justify-between items-baseline">
                            <span class="text-sm font-bold text-merapiText-primary">Total Biaya</span>
                            <span class="text-xl font-extrabold text-merapiText-primary font-display" id="valTotalAmount">Rp 450.000</span>
                        </div>
                    </div>

                    <!-- Opsi Bayar: Full vs DP -->
                    <div class="space-y-3 pt-3 border-t border-wukirsari-border">
                        <label class="block text-xs font-bold text-merapiText-primary">Pilih Skema Pembayaran</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            <label class="p-3 rounded-xl border text-xs font-semibold cursor-pointer transition-all paymode-label border-wukirsari-primary bg-wukirsari-sage/40 text-wukirsari-primary font-bold">
                                <input type="radio" name="payment_mode" value="FULL" checked class="hidden paymode-radio">
                                Bayar Lunas (100%)
                                <span class="block text-[10px] font-normal text-merapiText-muted mt-0.5">Langsung beres</span>
                            </label>

                            <label class="p-3 rounded-xl border text-xs font-semibold cursor-pointer transition-all paymode-label border-wukirsari-border text-merapiText-secondary">
                                <input type="radio" name="payment_mode" value="DP" class="hidden paymode-radio">
                                Uang Muka (DP 30%)
                                <span class="block text-[10px] font-normal text-merapiText-muted mt-0.5">Pelunasan di basecamp</span>
                            </label>
                        </div>
                    </div>

                    <!-- Kotak Tagihan -->
                    <div class="p-4 rounded-2xl bg-wukirsari-sage/40 border border-wukirsari-border space-y-2">
                        <div class="flex justify-between text-xs">
                            <span class="font-semibold text-merapiText-primary">Wajib Dibayar Sekarang:</span>
                            <span class="font-extrabold text-wukirsari-primary text-base font-display" id="valPaidAmount">Rp 450.000</span>
                        </div>
                        <div id="rowRemaining" class="hidden justify-between text-xs text-slate-700 pt-1 border-t border-wukirsari-border">
                            <span>Sisa Bayar di Loket:</span>
                            <span class="font-bold text-slate-800" id="valRemainingAmount">Rp 0</span>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-4 rounded-full bg-wukirsari-primary hover:bg-wukirsari-hover text-white font-bold text-sm flex items-center justify-center gap-2 shadow-lg shadow-wukirsari-primary/25 active:scale-98 transition-all">
                        <span>Konfirmasi & Terbitkan E-Tiket →</span>
                    </button>
                </div>
            </div>

        </form>

    </div>
</div>

<script>
    // Logika Kalkulator Harga Otomatis di Sisi Klien
    document.addEventListener('DOMContentLoaded', function () {
        const paxInput = document.getElementById('passenger_count');
        const jeepInput = document.getElementById('jeep_quantity');
        const btnDecPax = document.getElementById('btnDecPax');
        const btnIncPax = document.getElementById('btnIncPax');
        const btnDecJeep = document.getElementById('btnDecJeep');
        const btnIncJeep = document.getElementById('btnIncJeep');

        const packageRadios = document.querySelectorAll('.package-radio');
        const arrivalRadios = document.querySelectorAll('.arrival-radio');
        const paymodeRadios = document.querySelectorAll('.paymode-radio');
        const pickupSelect = document.getElementById('pickup_zone_id');
        const pickupBox = document.getElementById('pickupBox');

        function formatIdr(num) {
            return 'Rp ' + Number(num).toLocaleString('id-ID');
        }

        function calculate() {
            let pax = parseInt(paxInput.value) || 1;
            let minJeeps = Math.ceil(pax / 4);
            let jeeps = parseInt(jeepInput.value) || 1;

            if (jeeps < minJeeps) {
                jeeps = minJeeps;
                jeepInput.value = jeeps;
            }

            let activePkgPrice = 450000;
            packageRadios.forEach(r => {
                if (r.checked) activePkgPrice = parseFloat(r.dataset.price);
            });

            let subtotal = activePkgPrice * jeeps;
            document.getElementById('jeepSummaryText').innerText = `${jeeps} Jeep × ${formatIdr(activePkgPrice)}`;
            document.getElementById('valPackageSubtotal').innerText = formatIdr(subtotal);

            let isPickup = false;
            arrivalRadios.forEach(r => {
                if (r.checked && r.value === 'pickup') isPickup = true;
            });

            let pickupFee = 0;
            if (isPickup) {
                pickupBox.classList.remove('hidden');
                let selOpt = pickupSelect.options[pickupSelect.selectedIndex];
                pickupFee = parseFloat(selOpt.dataset.fee) || 0;
            } else {
                pickupBox.classList.add('hidden');
            }
            document.getElementById('valPickupFee').innerText = formatIdr(pickupFee);

            let total = subtotal + pickupFee;
            document.getElementById('valTotalAmount').innerText = formatIdr(total);

            let isDp = false;
            paymodeRadios.forEach(r => {
                if (r.checked && r.value === 'DP') isDp = true;
            });

            let paid = total;
            let remaining = 0;

            if (isDp) {
                paid = Math.round(total * 0.30);
                remaining = total - paid;
                document.getElementById('rowRemaining').classList.remove('hidden');
                document.getElementById('rowRemaining').classList.add('flex');
            } else {
                document.getElementById('rowRemaining').classList.add('hidden');
                document.getElementById('rowRemaining').classList.remove('flex');
            }

            document.getElementById('valPaidAmount').innerText = formatIdr(paid);
            document.getElementById('valRemainingAmount').innerText = formatIdr(remaining);
        }

        btnIncPax.addEventListener('click', () => { paxInput.value = parseInt(paxInput.value) + 1; calculate(); });
        btnDecPax.addEventListener('click', () => { if (parseInt(paxInput.value) > 1) { paxInput.value = parseInt(paxInput.value) - 1; calculate(); } });
        btnIncJeep.addEventListener('click', () => { jeepInput.value = parseInt(jeepInput.value) + 1; calculate(); });
        btnDecJeep.addEventListener('click', () => { 
            let minJeeps = Math.ceil(parseInt(paxInput.value) / 4);
            if (parseInt(jeepInput.value) > minJeeps) {
                jeepInput.value = parseInt(jeepInput.value) - 1;
                calculate();
            }
        });

        paxInput.addEventListener('change', calculate);
        jeepInput.addEventListener('change', calculate);
        pickupSelect.addEventListener('change', calculate);

        packageRadios.forEach(r => r.addEventListener('change', function () {
            document.querySelectorAll('.package-radio-label').forEach(l => l.classList.remove('border-wukirsari-primary', 'bg-wukirsari-sage/40', 'ring-1', 'ring-wukirsari-primary'));
            this.parentElement.classList.add('border-wukirsari-primary', 'bg-wukirsari-sage/40', 'ring-1', 'ring-wukirsari-primary');
            calculate();
        }));

        arrivalRadios.forEach(r => r.addEventListener('change', function () {
            document.querySelectorAll('.arrival-label').forEach(l => {
                l.classList.remove('border-wukirsari-primary', 'bg-wukirsari-sage/40', 'text-wukirsari-primary');
                l.classList.add('border-wukirsari-border', 'text-merapiText-secondary');
            });
            this.parentElement.classList.add('border-wukirsari-primary', 'bg-wukirsari-sage/40', 'text-wukirsari-primary');
            this.parentElement.classList.remove('border-wukirsari-border', 'text-merapiText-secondary');

            // Jika memilih shuttle dan zona masih pada opsi basecamp (+Rp 0), otomatis pilih opsi penjemputan lokal
            if (this.value === 'pickup' && pickupSelect.selectedIndex === 0 && pickupSelect.options.length > 1) {
                pickupSelect.selectedIndex = 1;
            }

            calculate();
        }));

        paymodeRadios.forEach(r => r.addEventListener('change', function () {
            document.querySelectorAll('.paymode-label').forEach(l => {
                l.classList.remove('border-wukirsari-primary', 'bg-wukirsari-sage/40', 'text-wukirsari-primary', 'font-bold');
                l.classList.add('border-wukirsari-border', 'text-merapiText-secondary');
            });
            this.parentElement.classList.add('border-wukirsari-primary', 'bg-wukirsari-sage/40', 'text-wukirsari-primary', 'font-bold');
            this.parentElement.classList.remove('border-wukirsari-border', 'text-merapiText-secondary');
            calculate();
        }));

        // ==========================================
        // FITUR DETEKSI LOKASI OTOMATIS (GPS & REVERSE GEOCODING)
        // ==========================================
        const btnDetectLocation = document.getElementById('btnDetectLocation');
        const detectSpinner = document.getElementById('detectSpinner');
        const detectIcon = document.getElementById('detectIcon');
        const detectText = document.getElementById('detectText');
        const geoNotice = document.getElementById('geoNotice');
        const pickupAddressInput = document.getElementById('pickup_address');

        // Koordinat Basecamp Wukirsari Cangkringan (Titik Acuan)
        const BASECAMP_LAT = -7.6475;
        const BASECAMP_LNG = 110.4560;

        function calcDistanceKm(lat1, lon1, lat2, lon2) {
            const R = 6371; // Radius bumi (km)
            const dLat = (lat2 - lat1) * (Math.PI / 180);
            const dLon = (lon2 - lon1) * (Math.PI / 180);
            const a =
                Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.cos(lat1 * (Math.PI / 180)) * Math.cos(lat2 * (Math.PI / 180)) *
                Math.sin(dLon / 2) * Math.sin(dLon / 2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            return R * c;
        }

        function showGeoNotice(type, content) {
            geoNotice.className = 'text-xs p-3 rounded-xl border transition-all';
            if (type === 'loading') {
                geoNotice.classList.add('bg-slate-100', 'text-merapiText-primary', 'border-wukirsari-border');
            } else if (type === 'success') {
                geoNotice.classList.add('bg-wukirsari-sage/70', 'text-wukirsari-primary', 'border-wukirsari-border');
            } else {
                geoNotice.classList.add('bg-rose-50', 'text-rose-800', 'border-rose-200');
            }
            geoNotice.innerHTML = content;
        }

        if (btnDetectLocation) {
            btnDetectLocation.addEventListener('click', function () {
                if (!navigator.geolocation) {
                    showGeoNotice('error', 'Peramban/Browser Anda tidak mendukung Geolocation GPS. Silakan tentukan zona dan ketik alamat secara manual.');
                    return;
                }

                // Loading State
                detectSpinner.classList.remove('hidden');
                detectIcon.classList.add('hidden');
                detectText.innerText = 'Melacak GPS...';
                btnDetectLocation.disabled = true;
                showGeoNotice('loading', '<div class="flex items-center gap-2"><span>Sedang mengambil titik koordinat GPS... Harap izinkan akses lokasi jika diminta browser.</span></div>');

                navigator.geolocation.getCurrentPosition(
                    async function (position) {
                        const userLat = position.coords.latitude;
                        const userLng = position.coords.longitude;
                        const distanceKm = calcDistanceKm(BASECAMP_LAT, BASECAMP_LNG, userLat, userLng);

                        let formattedAddress = `Titik GPS: ${userLat.toFixed(5)}, ${userLng.toFixed(5)}`;
                        let rawData = null;

                        try {
                            // Reverse geocoding via OpenStreetMap Nominatim
                            const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${userLat}&lon=${userLng}`, {
                                headers: { 'Accept-Language': 'id' }
                            });
                            if (response.ok) {
                                rawData = await response.json();
                                if (rawData && rawData.address) {
                                    const a = rawData.address;
                                    const road = a.road || a.pedestrian || a.suburb || '';
                                    const village = a.village || a.neighbourhood || '';
                                    const district = a.city_district || a.county || a.city || '';
                                    const parts = [road, village, district].filter(Boolean);
                                    if (parts.length > 0) {
                                        formattedAddress = parts.join(', ');
                                    } else if (rawData.display_name) {
                                        formattedAddress = rawData.display_name.split(',').slice(0, 3).join(', ');
                                    }
                                }
                            }
                        } catch (err) {
                            console.warn('Reverse geocode offline/fallback:', err);
                        }

                        // Isi alamat ke input
                        pickupAddressInput.value = `${formattedAddress} (GPS: ${userLat.toFixed(5)}, ${userLng.toFixed(5)})`;

                        // Analisis kata kunci & jarak untuk penyesuaian otomatis Dropdown Zona
                        const searchContext = (formattedAddress + ' ' + (rawData?.display_name || '')).toLowerCase();
                        const options = Array.from(pickupSelect.options);
                        let matchedIdx = -1;

                        if (searchContext.includes('kulon progo') || searchContext.includes('bandara') || searchContext.includes('yia') || distanceKm > 35) {
                            matchedIdx = options.findIndex(o => o.text.toLowerCase().includes('kulon progo') || o.text.toLowerCase().includes('yia'));
                        } else if (searchContext.includes('cangkringan') || searchContext.includes('pakem') || distanceKm <= 12) {
                            matchedIdx = options.findIndex(o => o.text.toLowerCase().includes('cangkringan') || o.text.toLowerCase().includes('pakem'));
                        } else {
                            matchedIdx = options.findIndex(o => o.text.toLowerCase().includes('sleman') || o.text.toLowerCase().includes('kota yogyakarta'));
                        }

                        if (matchedIdx === -1 && options.length > 1) {
                            matchedIdx = 1;
                        }

                        if (matchedIdx !== -1) {
                            pickupSelect.selectedIndex = matchedIdx;
                            calculate(); // Hitung ulang tagihan dengan biaya zona yang baru terpilih
                        }

                        const selectedZoneName = pickupSelect.options[pickupSelect.selectedIndex].text;

                        // Otomatis sembunyikan dropdown pemilihan zona di bawahnya karena sudah di-set oleh GPS
                        const pickupZoneSelectWrapper = document.getElementById('pickupZoneSelectWrapper');
                        if (pickupZoneSelectWrapper) {
                            pickupZoneSelectWrapper.classList.add('hidden');
                        }

                        showGeoNotice('success', `
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-start gap-2">
                                    <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 mt-0.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    </span>
                                    <div>
                                        <p class="font-bold text-wukirsari-primary">Lokasi Berhasil Dideteksi!</p>
                                        <p class="text-merapiText-primary mt-0.5">${formattedAddress} <span class="text-[11px] font-semibold text-merapiText-secondary">(Jarak ±${distanceKm.toFixed(1)} km dari Basecamp)</span></p>
                                        <p class="text-merapiText-secondary text-[11px] mt-0.5">Zona otomatis diset: <strong>${selectedZoneName}</strong></p>
                                    </div>
                                </div>
                                <button type="button" id="btnToggleManualZone" class="text-[11px] text-wukirsari-primary hover:underline font-semibold whitespace-nowrap cursor-pointer mt-0.5">
                                    Ganti Zona
                                </button>
                            </div>
                        `);

                        const btnToggleManualZone = document.getElementById('btnToggleManualZone');
                        if (btnToggleManualZone && pickupZoneSelectWrapper) {
                            btnToggleManualZone.addEventListener('click', function () {
                                pickupZoneSelectWrapper.classList.toggle('hidden');
                                if (pickupZoneSelectWrapper.classList.contains('hidden')) {
                                    btnToggleManualZone.innerText = 'Ganti Zona';
                                } else {
                                    btnToggleManualZone.innerText = 'Sembunyikan Pilihan';
                                }
                            });
                        }

                        detectSpinner.classList.add('hidden');
                        detectIcon.classList.remove('hidden');
                        detectText.innerText = 'Deteksi Ulang';
                        btnDetectLocation.disabled = false;
                    },
                    function (geoError) {
                        detectSpinner.classList.add('hidden');
                        detectIcon.classList.remove('hidden');
                        detectText.innerText = 'Coba Lagi';
                        btnDetectLocation.disabled = false;

                        let msg = 'Gagal mendeteksi lokasi.';
                        if (geoError.code === geoError.PERMISSION_DENIED) {
                            msg = 'Izin akses lokasi ditolak oleh browser. Silakan izinkan akses lokasi di pengaturan browser Anda, atau pilih zona & ketik alamat secara manual.';
                        } else if (geoError.code === geoError.POSITION_UNAVAILABLE) {
                            msg = 'Informasi lokasi tidak tersedia dari sensor perangkat saat ini.';
                        } else if (geoError.code === geoError.TIMEOUT) {
                            msg = 'Permintaan lokasi GPS melebihi batas waktu (timeout). Silakan klik coba lagi.';
                        }

                        showGeoNotice('error', `<strong>Pemberitahuan:</strong> ${msg}`);
                    },
                    {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 30000
                    }
                );
            });
        }

        calculate();
    });
</script>
@endsection
