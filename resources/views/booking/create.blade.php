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

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        @foreach($packages as $pkg)
                        <label class="p-3.5 rounded-2xl border cursor-pointer transition-all flex flex-col justify-between package-radio-label {{ $pkg->id == $selectedPackage->id ? 'border-wukirsari-primary bg-wukirsari-sage/40 ring-1 ring-wukirsari-primary' : 'border-wukirsari-border hover:bg-slate-50' }}">
                            <input type="radio" name="package_id" value="{{ $pkg->id }}" data-price="{{ $pkg->price_per_jeep }}" {{ $pkg->id == $selectedPackage->id ? 'checked' : '' }} class="hidden package-radio">
                            <div>
                                <span class="block text-xs font-bold text-merapiText-primary">{{ $pkg->name }}</span>
                                <span class="block text-xs font-bold text-wukirsari-primary mt-1">Rp {{ number_format($pkg->price_per_jeep, 0, ',', '.') }}</span>
                            </div>
                            <span class="flex items-center gap-1 text-[11px] text-merapiText-muted mt-2">
                                <svg class="w-3 h-3 text-merapiText-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>{{ $pkg->duration_minutes }} menit</span>
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
                            <span class="block text-[11px] text-emerald-700 font-medium mt-1.5" id="jeepSummaryText">1 Jeep × Rp 450.000</span>
                        </div>
                    </div>
                </div>

                <!-- 4. Opsi Kedatangan & Kontak -->
                <div class="bg-white p-6 rounded-3xl border border-wukirsari-border shadow-xs space-y-4">
                    <h3 class="text-base font-bold text-merapiText-primary font-display flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-wukirsari-sage text-wukirsari-primary text-xs flex items-center justify-center font-bold">4</span>
                        <span>Opsi Kedatangan & Kontak</span>
                    </h3>

                    <div class="grid grid-cols-2 gap-3">
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
                        <label class="block text-xs font-semibold text-merapiText-primary">Pilih Zona Penjemputan</label>
                        <select name="pickup_zone_id" id="pickup_zone_id" class="w-full px-3 py-2 rounded-xl border border-wukirsari-border bg-white text-xs">
                            @foreach($pickupZones as $zone)
                            <option value="{{ $zone->id }}" data-fee="{{ $zone->additional_fee }}">
                                {{ $zone->zone_name }} (+Rp {{ number_format($zone->additional_fee, 0, ',', '.') }})
                            </option>
                            @endforeach
                        </select>
                        <input type="text" name="pickup_address" placeholder="Nama hotel / alamat penjemputan lengkap" class="w-full px-3 py-2 rounded-xl border border-wukirsari-border bg-white text-xs">
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
                            <span class="font-semibold text-emerald-600">Gratis (Termasuk)</span>
                        </div>

                        <div class="pt-3 border-t border-wukirsari-border flex justify-between items-baseline">
                            <span class="text-sm font-bold text-merapiText-primary">Total Biaya</span>
                            <span class="text-xl font-extrabold text-merapiText-primary font-display" id="valTotalAmount">Rp 450.000</span>
                        </div>
                    </div>

                    <!-- Opsi Bayar: Full vs DP -->
                    <div class="space-y-3 pt-3 border-t border-wukirsari-border">
                        <label class="block text-xs font-bold text-merapiText-primary">Pilih Skema Pembayaran</label>
                        <div class="grid grid-cols-2 gap-2">
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
                        <div id="rowRemaining" class="hidden justify-between text-xs text-amber-800 pt-1 border-t border-wukirsari-border">
                            <span>Sisa Bayar di Loket:</span>
                            <span class="font-bold" id="valRemainingAmount">Rp 0</span>
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

        calculate();
    });
</script>
@endsection
