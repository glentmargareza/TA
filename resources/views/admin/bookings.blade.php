<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Booking Wisatawan | Jeep Wukirsari</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        wukirsari: {
                            primary: '#143B1E',
                            dark: '#0D2714',
                            accent: '#1E5E30',
                            light: '#EAF3EC',
                            border: '#D7E5D9',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Poppins:wght@500;600;700;800&display=swap');
        body { font-family: 'Inter', system-ui, -apple-system, sans-serif; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Poppins', 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-[#F4F6F8] text-slate-800 min-h-screen flex flex-col md:flex-row antialiased">

    @include('admin.partials.sidebar')

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 min-h-screen overflow-x-hidden">
        
        <!-- Header Banner -->
        <header class="bg-white border-b border-slate-200 px-4 sm:px-8 py-4 sm:py-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 sticky top-0 z-20 shadow-2xs">
            <div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                    Manajemen Booking Wisatawan
                </h1>
                <p class="text-xs text-slate-500 mt-0.5">Kelola reservasi, verifikasi pembayaran loket, penugasan armada & driver, serta detail pemesan.</p>
            </div>
            <div class="flex items-center gap-2.5 sm:gap-3 flex-wrap">
                <span class="px-3 py-1.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                    Total: {{ $bookings->total() }} Booking Terdaftar
                </span>
                <a href="{{ route('admin.dashboard') }}" class="px-3.5 py-1.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-100 transition-all">
                    &larr; Dashboard
                </a>
            </div>
        </header>

        <main class="p-4 sm:p-8 space-y-5 sm:space-y-6 flex-1">

            @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-900 rounded-2xl text-sm font-semibold shadow-xs flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="text-emerald-600 text-lg">✓</span>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-950 font-bold text-sm cursor-pointer">&times;</button>
            </div>
            @endif

            <!-- CARD FORM PENCARIAN & FILTER LENGKAP -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5">
                <form method="GET" action="{{ route('admin.bookings') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                    <!-- 1. Pencarian Kode Booking / Nama / Kontak -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Cari Booking / Nama</label>
                        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Contoh: JKR-1025 / Budi" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 shadow-2xs">
                    </div>

                    <!-- 2. Filter Tanggal Booking -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Tanggal Trip</label>
                        <input type="date" name="trip_date" value="{{ $tripDate ?? '' }}" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 shadow-2xs">
                    </div>

                    <!-- 3. Filter Paket Wisata -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Paket Wisata</label>
                        <select name="package_id" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 shadow-2xs">
                            <option value="">Semua Paket</option>
                            @foreach($packages as $p)
                            <option value="{{ $p->id }}" {{ (isset($packageId) && (string)$packageId === (string)$p->id) ? 'selected' : '' }}>{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 4. Filter Status Pembayaran -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Status Pembayaran</label>
                        <select name="status" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 shadow-2xs">
                            <option value="">Semua Status</option>
                            <option value="PENDING" {{ (isset($status) && $status === 'PENDING') ? 'selected' : '' }}>Menunggu Bayar</option>
                            <option value="DP_PAID" {{ (isset($status) && $status === 'DP_PAID') ? 'selected' : '' }}>DP Terbayar (50%)</option>
                            <option value="FULL_PAID" {{ (isset($status) && $status === 'FULL_PAID') ? 'selected' : '' }}>Lunas (100%)</option>
                            <option value="COMPLETED" {{ (isset($status) && $status === 'COMPLETED') ? 'selected' : '' }}>Selesai</option>
                            <option value="CANCELLED" {{ (isset($status) && $status === 'CANCELLED') ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>

                    <!-- 5. Tombol Aksi Filter -->
                    <div class="flex items-end gap-2">
                        <button type="submit" class="flex-1 py-2 px-3 rounded-xl bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs shadow-xs transition-all cursor-pointer">
                            Terapkan Filter
                        </button>
                        <a href="{{ route('admin.bookings') }}" class="py-2 px-3 rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-slate-100 font-bold text-xs transition-all shadow-2xs cursor-pointer text-center" title="Reset Filter">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- TABEL DATA LENGKAP BOOKING -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs min-w-[760px]">
                        <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider text-[10px] border-b border-slate-200">
                            <tr>
                                <th class="py-3 px-4">Kode Booking</th>
                                <th class="py-3 px-4">Pemesan</th>
                                <th class="py-3 px-4">Paket & Trip</th>
                                <th class="py-3 px-4">Armada/Pax</th>
                                <th class="py-3 px-4">Status & Sisa</th>
                                <th class="py-3 px-4">Penugasan Driver</th>
                                <th class="py-3 px-4 text-center">Aksi Loket</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($bookings as $b)
                            @php
                                $proofUrl = null;
                                $senderName = null;
                                $senderBank = null;
                                foreach ($b->payments as $p) {
                                    if (is_array($p->payload_response) && !empty($p->payload_response['proof_url'])) {
                                        $proofUrl = asset($p->payload_response['proof_url']);
                                        $senderName = $p->payload_response['sender_name'] ?? null;
                                        $senderBank = $p->payload_response['sender_bank'] ?? null;
                                        break;
                                    }
                                }

                                $detailData = [
                                    'id' => $b->id,
                                    'booking_code' => $b->booking_code,
                                    'customer_name' => $b->customer_name,
                                    'customer_phone' => $b->customer_phone,
                                    'customer_email' => $b->customer_email ?? '-',
                                    'package_name' => $b->package?->name ?? 'Paket Jeep',
                                    'trip_date' => $b->trip_date ? $b->trip_date->translatedFormat('d F Y') : '-',
                                    'slot_label' => ($b->slot?->start_time ?? '09:00') . ' - ' . ($b->slot?->end_time ?? '11:00') . ' WIB',
                                    'jeep_quantity' => $b->jeep_quantity,
                                    'passenger_count' => $b->passenger_count,
                                    'is_pickup' => strtolower((string)$b->arrival_type) === 'pickup',
                                    'arrival_type' => strtolower((string)$b->arrival_type) === 'pickup' ? 'Jemput di Hotel / Alamat' : 'Datang Sendiri ke Basecamp',
                                    'pickup_zone' => $b->pickupZone?->name ?? ($b->pickup_distance_km ? 'Antar-Jemput (' . number_format($b->pickup_distance_km, 1) . ' km)' : 'Layanan Antar-Jemput'),
                                    'pickup_address' => $b->pickup_address ?? '-',
                                    'payment_mode' => $b->payment_mode === 'DP' ? 'Uang Muka (DP 30%)' : 'Pembayaran Penuh (100%)',
                                    'package_subtotal' => number_format($b->package_subtotal, 0, ',', '.'),
                                    'pickup_fee' => number_format($b->pickup_fee, 0, ',', '.'),
                                    'maps_link' => ($b->pickup_address && $b->pickup_address !== '-') ? 'https://www.google.com/maps/search/?api=1&query=' . urlencode($b->pickup_address) : null,
                                    'total_amount' => number_format($b->total_amount, 0, ',', '.'),
                                    'paid_amount' => number_format($b->paid_amount, 0, ',', '.'),
                                    'remaining_amount' => number_format($b->remaining_amount, 0, ',', '.'),
                                    'booking_status' => $b->booking_status,
                                    'created_at' => $b->created_at ? $b->created_at->translatedFormat('d M Y, H:i') . ' WIB' : '-',
                                    'jeep_name' => $b->assignments->first()?->jeep?->model_name ?? 'Belum Ditugaskan',
                                    'driver_name' => $b->assignments->first()?->driver?->full_name ?? 'Dialokasikan Otomatis',
                                    'customer_notes' => $b->customer_notes ?? '',
                                    'raw_trip_date' => $b->trip_date ? $b->trip_date->format('Y-m-d') : date('Y-m-d'),
                                    'slot_id' => $b->slot_id,
                                    'proof_url' => $proofUrl,
                                    'sender_name' => $senderName,
                                    'sender_bank' => $senderBank,
                                ];

                                $rescheduleData = [
                                    'id' => $b->id,
                                    'booking_code' => $b->booking_code,
                                    'customer_name' => $b->customer_name,
                                    'customer_phone' => $b->customer_phone,
                                    'package_name' => $b->package?->name ?? 'Paket',
                                    'jeep_quantity' => $b->jeep_quantity,
                                    'passenger_count' => $b->passenger_count,
                                    'trip_date' => $b->trip_date ? $b->trip_date->format('Y-m-d') : date('Y-m-d'),
                                    'trip_date_formatted' => $b->trip_date ? $b->trip_date->translatedFormat('d F Y') : '-',
                                    'slot_id' => $b->slot_id,
                                    'slot_label' => ($b->slot?->start_time ?? '09:00') . ' - ' . ($b->slot?->end_time ?? '11:00') . ' WIB (' . ($b->slot?->slot_label ?? 'Sesi') . ')',
                                ];
                            @endphp
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="py-3 px-4">
                                    <button type="button" data-booking='@json($detailData)' onclick="openBookingDetailModal(this)" class="font-mono font-bold text-slate-800 hover:text-emerald-700 hover:underline flex items-center gap-1.5 cursor-pointer text-left" title="Klik untuk lihat detail">
                                        <span>{{ $b->booking_code }}</span>
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    </button>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        <span class="font-bold text-slate-800">{{ $b->customer_name }}</span>
                                        @if(strtolower((string)$b->arrival_type) === 'pickup')
                                        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200" title="{{ $b->pickup_address }}">
                                            <svg class="w-2.5 h-2.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            Pickup
                                        </span>
                                        @endif
                                    </div>
                                    <span class="text-slate-500 text-[11px] block">{{ $b->customer_phone }}</span>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="font-semibold text-slate-800 block">{{ $b->package?->name ?? 'Paket' }}</span>
                                    <div class="flex items-center gap-1.5 flex-wrap mt-0.5">
                                        <span class="text-slate-500 text-[11px]">{{ $b->trip_date ? $b->trip_date->translatedFormat('d/m/Y') : '-' }} &bull; {{ $b->slot?->start_time ?? '09:00' }}</span>
                                        @if($b->booking_status !== 'CANCELLED')
                                        <button type="button" data-reschedule='@json($rescheduleData)' onclick="openRescheduleModal(this)" class="text-[10px] font-bold text-amber-800 hover:text-amber-950 px-1.5 py-0.5 rounded-md bg-amber-50 hover:bg-amber-100 border border-amber-200 cursor-pointer inline-flex items-center gap-0.5 transition-colors" title="Ubah tanggal atau jam jika bentrok jadwal">
                                            <svg class="w-3 h-3 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            <span>Ganti Jadwal</span>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="font-medium text-slate-700">{{ $b->jeep_quantity }} Jeep ({{ $b->passenger_count }} Org)</span>
                                </td>
                                <td class="py-3 px-4">
                                    @if($b->booking_status === 'FULL_PAID')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                        LUNAS (100%)
                                    </span>
                                    @elseif($b->booking_status === 'DP_PAID')
                                    <div>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            DP TERBAYAR
                                        </span>
                                        <span class="block text-[10px] text-rose-600 font-bold mt-0.5">
                                            Sisa: Rp {{ number_format($b->remaining_amount, 0, ',', '.') }}
                                        </span>
                                    </div>
                                    @elseif($b->booking_status === 'CANCELLED')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        DIBATALKAN
                                    </span>
                                    @elseif($b->booking_status === 'COMPLETED')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-teal-100 text-teal-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-teal-500"></span>
                                        SELESAI
                                    </span>
                                    @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span>
                                        MENUNGGU BAYAR
                                    </span>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    @php 
                                        $assign = $b->assignments->first();
                                        $assignData = [
                                            'id' => $b->id,
                                            'booking_code' => $b->booking_code,
                                            'customer_name' => $b->customer_name,
                                            'customer_notes' => $b->customer_notes ?? '',
                                            'jeep_id' => $assign ? $assign->jeep_id : null,
                                            'driver_id' => $assign ? $assign->driver_id : null,
                                        ];
                                    @endphp
                                    @if($assign)
                                    <div class="space-y-1">
                                        <div class="flex items-center justify-between gap-1.5">
                                            <span class="font-bold text-slate-800 text-xs flex items-center gap-1.5 truncate">
                                                <svg class="w-3.5 h-3.5 text-emerald-700 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                                {{ $assign->driver?->full_name ?? 'Auto Paguyuban' }}
                                            </span>
                                            <button type="button" data-assign='@json($assignData)' onclick="openAssignModal(this)" class="text-[10px] font-bold text-emerald-800 hover:text-emerald-950 px-2 py-0.5 rounded-md bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 cursor-pointer inline-flex items-center gap-0.5 shrink-0" title="Ubah driver">
                                                <span>✏️ Ganti</span>
                                            </button>
                                        </div>
                                        @if($assign->driver?->phone_number)
                                        <div class="text-[11px] text-slate-500">
                                            HP: {{ $assign->driver->phone_number }}
                                        </div>
                                        @endif
                                        @if($b->customer_notes)
                                        <div class="p-1 px-1.5 rounded-lg bg-amber-50 border border-amber-200/90 text-[10px] text-amber-900 flex items-start gap-1 mt-1">
                                            <span class="shrink-0 font-bold">💬 Req:</span>
                                            <span class="truncate font-semibold" title="{{ $b->customer_notes }}">{{ Str::limit($b->customer_notes, 28) }}</span>
                                        </div>
                                        @endif
                                    </div>
                                    @else
                                    <div class="space-y-1">
                                        @if($b->booking_status === 'CANCELLED')
                                        <span class="text-slate-400 text-xs italic">-</span>
                                        @elseif($b->booking_status === 'PENDING')
                                        <div class="flex items-center gap-1.5 text-[11px] text-slate-500 font-medium">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                            <span>Auto saat bayar</span>
                                        </div>
                                        <button type="button" data-assign='@json($assignData)' onclick="openAssignModal(this)" class="px-2 py-1 rounded-md bg-slate-100 hover:bg-slate-200 text-slate-700 text-[10px] font-bold transition-all flex items-center gap-1 cursor-pointer">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                            <span>Pilih Manual</span>
                                        </button>
                                        @else
                                        <button type="button" data-assign='@json($assignData)' onclick="openAssignModal(this)" class="px-2.5 py-1.5 bg-emerald-800 hover:bg-emerald-900 text-white rounded-lg text-[10px] font-bold shadow-xs transition-all flex items-center gap-1 cursor-pointer">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                            <span>Pilih Driver</span>
                                        </button>
                                        @endif
                                        @if($b->customer_notes)
                                        <div class="p-1 px-1.5 rounded-lg bg-amber-50 border border-amber-200/90 text-[10px] text-amber-900 flex items-start gap-1 mt-1">
                                            <span class="shrink-0 font-bold">💬 Req:</span>
                                            <span class="truncate font-semibold" title="{{ $b->customer_notes }}">{{ Str::limit($b->customer_notes, 28) }}</span>
                                        </div>
                                        @endif
                                    </div>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <!-- Tombol Detail Informasi Pemesan -->
                                        <button type="button" data-booking='@json($detailData)' onclick="openBookingDetailModal(this)" class="p-1.5 rounded-lg border border-slate-200 bg-white hover:bg-emerald-50 hover:border-emerald-300 text-slate-600 hover:text-emerald-700 text-xs font-semibold shadow-2xs transition-all cursor-pointer" title="Lihat detail lengkap pemesan">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </button>

                                        @if(!empty($detailData['proof_url']))
                                        <!-- Tombol Akses Bukti Transfer Cepat (1 Tab / In-Page Modal) -->
                                        <button type="button" 
                                            data-proof-url="{{ $detailData['proof_url'] }}" 
                                            data-booking-code="{{ $b->booking_code }}" 
                                            data-sender-name="{{ $detailData['sender_name'] ?? 'Wisatawan' }}" 
                                            data-sender-bank="{{ $detailData['sender_bank'] ?? 'Bank BPD DIY' }}" 
                                            onclick="openProofFromBtn(this)" 
                                            class="p-1.5 rounded-lg border border-blue-200 bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-semibold shadow-2xs transition-all cursor-pointer" 
                                            title="Lihat Bukti Transfer Pelanggan (Dalam Halaman)">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        </button>
                                        @endif

                                        @if($b->booking_status === 'DP_PAID')
                                        <form action="{{ route('admin.settle', $b->id) }}" method="POST"
                                              data-confirm-title="Konfirmasi Pelunasan Loket"
                                              data-confirm-message="Terima pelunasan sisa tagihan untuk booking <strong class='font-bold text-emerald-800'>#{{ $b->booking_code }}</strong> atas nama <strong class='text-slate-800'>{{ $b->customer_name }}</strong> sebesar <strong class='text-emerald-700 font-bold'>Rp {{ number_format($b->remaining_amount ?? 0, 0, ',', '.') }}</strong>?"
                                              data-confirm-type="success"
                                              data-confirm-text="Ya, Terima Pelunasan"
                                              data-confirm-cancel="Batal">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-[10px] shadow-xs cursor-pointer">
                                                Pelunasan
                                            </button>
                                        </form>
                                        @endif

                                        @if($b->booking_status !== 'CANCELLED' && $b->booking_status !== 'COMPLETED')
                                        <form action="{{ route('admin.cancel', $b->id) }}" method="POST"
                                              data-confirm-title="Batalkan Booking"
                                              data-confirm-message="Apakah Anda yakin ingin membatalkan booking <strong class='font-bold text-rose-700'>#{{ $b->booking_code }}</strong> atas nama <strong class='text-slate-800'>{{ $b->customer_name }}</strong>? Tindakan ini tidak dapat dibatalkan."
                                              data-confirm-type="danger"
                                              data-confirm-text="Ya, Batalkan Booking"
                                              data-confirm-cancel="Kembali">
                                            @csrf
                                            <button type="submit" class="p-1.5 rounded-lg border border-slate-200 bg-white hover:bg-rose-50 hover:border-rose-300 text-slate-400 hover:text-rose-600 text-xs shadow-2xs transition-all cursor-pointer" title="Batalkan booking">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-slate-400">
                                    <div class="max-w-sm mx-auto space-y-2">
                                        <p class="font-bold text-slate-700 text-sm">Tidak ada transaksi ditemukan</p>
                                        <p class="text-xs text-slate-500">Coba ubah kata kunci pencarian atau reset filter di atas.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($bookings instanceof \Illuminate\Pagination\LengthAwarePaginator && $bookings->hasPages())
                <div class="p-4 border-t border-slate-200 bg-slate-50/50">
                    {{ $bookings->links() }}
                </div>
                @endif
            </div>

            <!-- MODAL DETAIL LENGKAP INFORMASI PEMESAN -->
            <div id="modalBookingDetail" onclick="if(event.target === this) closeBookingDetailModal()" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden transition-opacity duration-200">
                <div class="bg-white rounded-3xl max-w-2xl w-full max-h-[90vh] overflow-y-auto shadow-2xl border border-slate-100 transform transition-all animate-in fade-in zoom-in-95">
                    <!-- Modal Header -->
                    <div class="p-5 sm:p-6 border-b border-slate-100 flex items-center justify-between sticky top-0 bg-white/95 backdrop-blur-xs z-10">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold">
                                <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="font-bold text-slate-800 text-base">Detail Informasi Pemesan</h3>
                                    <span id="detail_status_badge"></span>
                                </div>
                                <p class="text-xs text-slate-500">Kode Booking: <span id="detail_booking_code" class="font-mono font-bold text-emerald-800"></span></p>
                            </div>
                        </div>
                        <button type="button" onclick="closeBookingDetailModal()" class="w-8 h-8 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-700 flex items-center justify-center font-bold text-lg cursor-pointer">&times;</button>
                    </div>

                    <!-- Modal Body -->
                    <div class="p-6 space-y-6 text-xs sm:text-sm">
                        <!-- 1. Identitas Pemesan -->
                        <div class="bg-slate-50 p-4 sm:p-5 rounded-2xl border border-slate-200/80 space-y-3">
                            <h4 class="font-bold text-slate-800 flex items-center gap-2 text-xs uppercase tracking-wider text-emerald-800">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                Data Wisatawan / Pemesan
                            </h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <span class="text-slate-400 text-xs block">Nama Lengkap</span>
                                    <span id="detail_customer_name" class="font-bold text-slate-800 text-sm block"></span>
                                </div>
                                <div>
                                    <span class="text-slate-400 text-xs block">No. WhatsApp / HP</span>
                                    <span id="detail_customer_phone" class="font-bold text-slate-800 block"></span>
                                </div>
                                <div>
                                    <span class="text-slate-400 text-xs block">Email</span>
                                    <span id="detail_customer_email" class="font-medium text-slate-700 block"></span>
                                </div>
                                <div>
                                    <span class="text-slate-400 text-xs block">Waktu Pemesanan Masuk</span>
                                    <span id="detail_created_at" class="font-medium text-slate-700 block"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Catatan / Permintaan Khusus Wisatawan -->
                        <div id="detail_notes_container" class="bg-amber-50/80 p-4 sm:p-5 rounded-2xl border border-amber-200/90 space-y-1.5 hidden">
                            <h4 class="font-bold text-amber-900 flex items-center gap-2 text-xs uppercase tracking-wider">
                                <span>💬</span>
                                <span>Pesan / Catatan Khusus dari Pemesan</span>
                            </h4>
                            <p id="detail_customer_notes" class="text-xs text-amber-950 font-semibold italic leading-relaxed"></p>
                        </div>

                        <!-- 2. Paket & Jadwal Trip -->
                        <div class="bg-slate-50 p-4 sm:p-5 rounded-2xl border border-slate-200/80 space-y-3">
                            <h4 class="font-bold text-slate-800 flex items-center gap-2 text-xs uppercase tracking-wider text-emerald-800">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Paket Wisata & Waktu Pelaksanaan
                            </h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <span class="text-slate-400 text-xs block">Paket Wisata</span>
                                    <span id="detail_package_name" class="font-bold text-slate-800 text-sm block"></span>
                                </div>
                                <div>
                                    <span class="text-slate-400 text-xs block">Tanggal Trip</span>
                                    <span id="detail_trip_date" class="font-bold text-slate-800 block"></span>
                                </div>
                                <div>
                                    <span class="text-slate-400 text-xs block">Sesi Waktu</span>
                                    <span id="detail_slot_label" class="font-semibold text-slate-700 block"></span>
                                </div>
                                <div>
                                    <span class="text-slate-400 text-xs block">Armada & Penumpang</span>
                                    <span id="detail_armada_pax" class="font-semibold text-slate-700 block"></span>
                                </div>
                                <div class="sm:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2 border-t border-slate-200/60">
                                    <div>
                                        <span class="text-slate-400 text-xs block">Armada Jeep Ditugaskan</span>
                                        <span id="detail_jeep_name" class="font-bold text-slate-800 block mt-0.5"></span>
                                    </div>
                                    <div>
                                        <span class="text-slate-400 text-xs block">Driver Paguyuban (Otomatis)</span>
                                        <span id="detail_driver_name" class="font-bold text-emerald-800 block mt-0.5"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 3. Kedatangan & Penjemputan -->
                        <div class="bg-slate-50 p-4 sm:p-5 rounded-2xl border border-slate-200/80 space-y-3">
                            <h4 class="font-bold text-slate-800 flex items-center gap-2 text-xs uppercase tracking-wider text-emerald-800">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Logistik Kedatangan Wisatawan
                            </h4>
                            <div class="space-y-2">
                                <div>
                                    <span class="text-slate-400 text-xs block">Tipe Kedatangan</span>
                                    <span id="detail_arrival_type" class="font-bold text-slate-800 block"></span>
                                </div>
                                <div id="detail_pickup_container" class="hidden">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-slate-400 text-xs block">Zona & Alamat Lengkap Penjemputan</span>
                                        <a id="detail_pickup_maps_btn" href="#" target="_blank" rel="noopener noreferrer" class="hidden inline-flex items-center gap-1 text-[11px] font-bold text-emerald-700 hover:text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200 transition">
                                            <svg class="w-3 h-3 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>
                                            Buka Maps
                                        </a>
                                    </div>
                                    <div class="bg-white p-3 rounded-xl border border-slate-200 text-slate-700 mt-1 leading-relaxed">
                                        <span id="detail_pickup_zone" class="font-semibold text-emerald-800 block mb-0.5"></span>
                                        <span id="detail_pickup_address"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 4. Rincian Tagihan & Pembayaran -->
                        <div class="bg-emerald-50/60 p-4 sm:p-5 rounded-2xl border border-emerald-200/80 space-y-3">
                            <h4 class="font-bold text-slate-800 flex items-center gap-2 text-xs uppercase tracking-wider text-emerald-900">
                                <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                Rincian Keuangan & Status Loket
                            </h4>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                <div>
                                    <span class="text-slate-500 text-xs block">Mode Bayar</span>
                                    <span id="detail_payment_mode" class="font-semibold text-slate-800 block"></span>
                                </div>
                                <div>
                                    <span class="text-slate-500 text-xs block">Biaya Paket</span>
                                    <span id="detail_package_subtotal" class="font-semibold text-slate-800 block"></span>
                                </div>
                                <div>
                                    <span class="text-slate-500 text-xs block">Biaya Jemput</span>
                                    <span id="detail_pickup_fee" class="font-semibold text-slate-800 block"></span>
                                </div>
                                <div>
                                    <span class="text-slate-500 text-xs block">Total Tagihan</span>
                                    <span id="detail_total_amount" class="font-black text-slate-900 text-sm block"></span>
                                </div>
                                <div>
                                    <span class="text-slate-500 text-xs block">Telah Dibayar</span>
                                    <span id="detail_paid_amount" class="font-bold text-emerald-800 text-sm block"></span>
                                </div>
                                <div>
                                    <span class="text-slate-500 text-xs block">Sisa di Loket</span>
                                    <span id="detail_remaining_amount" class="font-black text-rose-600 text-sm block"></span>
                                </div>
                            </div>
                        </div>

                        <!-- 5. Bukti Transfer Pelanggan (Jika Ada) -->
                        <div id="detail_proof_box" class="bg-blue-50/70 p-4 sm:p-5 rounded-2xl border border-blue-200/80 space-y-3 hidden">
                            <div class="flex items-center justify-between">
                                <h4 class="font-bold text-blue-900 flex items-center gap-2 text-xs uppercase tracking-wider">
                                    <svg class="w-4 h-4 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    Bukti Transfer Pelanggan (Bank BPD DIY)
                                </h4>
                                <span id="detail_proof_bank_badge" class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800"></span>
                            </div>
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-3.5 rounded-xl border border-blue-100">
                                <div class="flex items-center gap-3">
                                    <img id="detail_proof_thumb" src="" alt="Thumbnail Bukti" onclick="showCurrentProofLightbox()" class="w-12 h-12 sm:w-14 sm:h-14 object-cover rounded-xl border border-slate-200 cursor-pointer hover:opacity-80 transition-opacity shrink-0 shadow-xs" title="Klik untuk memperbesar bukti transfer" />
                                    <div>
                                        <span class="text-slate-400 text-xs block">Nama Pengirim (Sesuai Rekening / Struk):</span>
                                        <span id="detail_proof_sender" class="font-bold text-slate-800 text-xs sm:text-sm block"></span>
                                        <span class="text-[11px] text-blue-600 block mt-0.5">Transfer Resmi Bank BPD DIY &bull; <button type="button" onclick="showCurrentProofLightbox()" class="underline font-semibold hover:text-blue-800 cursor-pointer">Klik gambar untuk perbesar</button></span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button type="button" id="btnDetailViewProof" onclick="showCurrentProofLightbox()" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-xs hover:shadow transition-all cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        <span>Lihat Bukti Transfer</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="p-5 sm:p-6 border-t border-slate-100 bg-slate-50 flex items-center justify-between gap-2 flex-wrap">
                        <div class="flex items-center gap-2 flex-wrap">
                            <a id="btnWhatsappCustomer" href="#" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm hover:shadow transition-all cursor-pointer">
                                <span>Hubungi Pemesan (WhatsApp)</span>
                            </a>
                            <button type="button" id="btnDetailReschedule" onclick="openRescheduleFromDetail()" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs shadow-sm hover:shadow transition-all cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span>Ubah Jadwal Trip</span>
                            </button>
                            <button type="button" id="btnFooterViewProof" onclick="showCurrentProofLightbox()" class="hidden inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-sm hover:shadow transition-all cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span>Lihat Bukti Transfer</span>
                            </button>
                        </div>
                        <button type="button" onclick="closeBookingDetailModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 text-slate-700 font-bold text-xs transition-colors cursor-pointer">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>

            <!-- MODAL PILIH / UBAH DRIVER & JEEP SECARA MANUAL -->
            <div id="modalAssignDriver" onclick="if(event.target === this) closeAssignModal()" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden transition-opacity duration-200">
                <div class="bg-white rounded-3xl max-w-lg w-full overflow-hidden shadow-2xl border border-slate-100 transform transition-all animate-in fade-in zoom-in-95">
                    <form id="formAssignDriver" action="" method="POST">
                        @csrf
                        <!-- Modal Header -->
                        <div class="p-5 sm:p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/80">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold">
                                    <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-slate-800 text-base">Atur / Ganti Driver Paguyuban</h3>
                                    <p class="text-xs text-slate-500">Sistem otomatis menugaskan driver secara bergiliran. Pilih manual jika ada permintaan khusus wisatawan.</p>
                                </div>
                            </div>
                            <button type="button" onclick="closeAssignModal()" class="w-8 h-8 rounded-lg hover:bg-slate-200 text-slate-400 hover:text-slate-700 flex items-center justify-center font-bold text-lg cursor-pointer">&times;</button>
                        </div>

                        <!-- Modal Body -->
                        <div class="p-6 space-y-4 text-xs">
                            <!-- Info Booking -->
                            <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200/80 flex items-center justify-between">
                                <div>
                                    <span class="text-[11px] text-slate-400 block">Kode Booking & Pemesan</span>
                                    <span id="assign_booking_title" class="font-bold text-slate-800 text-xs block"></span>
                                </div>
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">Paguyuban Wukirsari</span>
                            </div>

                            <!-- Box Permintaan Pelanggan (Jika ada) -->
                            <div id="assign_request_box" class="p-3.5 bg-amber-50/90 rounded-2xl border border-amber-200 space-y-1">
                                <div class="flex items-center gap-1.5 text-amber-900 font-bold text-[11px]">
                                    <span>💬</span>
                                    <span>Catatan / Request Khusus Wisatawan:</span>
                                </div>
                                <p id="assign_request_text" class="text-amber-950 text-xs font-semibold italic leading-relaxed"></p>
                            </div>

                            <!-- 1. Dropdown Pilih Driver Manual -->
                            <div>
                                <label class="block font-bold text-slate-700 mb-1.5 text-xs">
                                    Pilih Driver Paguyuban <span class="text-emerald-700 font-semibold">(Bisa pilih manual jika ada request)</span>:
                                </label>
                                <select name="driver_id" id="assign_driver_select" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 font-semibold cursor-pointer">
                                    <option value="random">🎲 Auto-Alokasi Paguyuban (Acak / Giliran Driver)</option>
                                    @foreach($drivers as $d)
                                    <option value="{{ $d->id }}">👤 {{ $d->full_name }} (HP: {{ $d->phone_number }})</option>
                                    @endforeach
                                </select>
                                <span class="text-[10px] text-slate-400 block mt-1">*Jika pemesan me-request driver tertentu, pilih langsung namanya dari daftar di atas.</span>
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="p-5 border-t border-slate-100 bg-slate-50 flex items-center justify-end gap-2.5">
                            <button type="button" onclick="closeAssignModal()" class="px-4 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 text-slate-700 font-semibold text-xs cursor-pointer">
                                Batal
                            </button>
                            <button type="submit" class="px-5 py-2 rounded-xl bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs shadow-sm hover:shadow transition-all cursor-pointer">
                                Simpan & Tetapkan Driver
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- MODAL UBAH JADWAL BOOKING (RESCHEDULE / SELESAIKAN BENTROK) -->
            <div id="modalRescheduleBooking" onclick="if(event.target === this) closeRescheduleModal()" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden transition-opacity duration-200">
                <div class="bg-white rounded-3xl max-w-lg w-full overflow-hidden shadow-2xl border border-slate-100 transform transition-all animate-in fade-in zoom-in-95">
                    <form id="formRescheduleBooking" action="" method="POST">
                        @csrf
                        <!-- Modal Header -->
                        <div class="p-5 sm:p-6 border-b border-slate-100 flex items-center justify-between bg-amber-50/70">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center font-bold">
                                    <svg class="w-5 h-5 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-slate-800 text-base">Ubah Jadwal Booking (Reschedule)</h3>
                                    <p class="text-xs text-slate-500">Pindahkan sesi tanggal & jam untuk menyelesaikan bentrok jadwal.</p>
                                </div>
                            </div>
                            <button type="button" onclick="closeRescheduleModal()" class="w-8 h-8 rounded-lg hover:bg-slate-200 text-slate-400 hover:text-slate-700 flex items-center justify-center font-bold text-lg cursor-pointer">&times;</button>
                        </div>

                        <!-- Modal Body -->
                        <div class="p-6 space-y-4 text-xs">
                            <!-- Info Booking & Jadwal Saat Ini -->
                            <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200/80 space-y-2">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="text-[11px] text-slate-400 block">Kode Booking & Pemesan</span>
                                        <span id="reschedule_booking_title" class="font-bold text-slate-800 text-xs block"></span>
                                    </div>
                                    <span id="reschedule_pax_badge" class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-200 text-slate-800"></span>
                                </div>
                                <div class="pt-2 border-t border-slate-200/60 flex items-center justify-between text-slate-600">
                                    <span class="text-[11px]">Jadwal Saat Ini:</span>
                                    <span id="reschedule_current_schedule" class="font-bold text-slate-800 text-[11px]"></span>
                                </div>
                            </div>

                            <!-- Pilih Tanggal Baru -->
                            <div>
                                <label class="block font-bold text-slate-700 mb-1.5 text-xs">
                                    Pilih Tanggal Keberangkatan Baru:
                                </label>
                                <input type="date" name="trip_date" id="reschedule_date_input" required min="{{ date('Y-m-d') }}" onchange="onRescheduleDateChange(this.value)" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500 font-semibold cursor-pointer">
                            </div>

                            <!-- Pilih Sesi Jam Baru -->
                            <div>
                                <div class="flex items-center justify-between mb-1.5">
                                    <label class="block font-bold text-slate-700 text-xs">
                                        Pilih Sesi Jam Baru:
                                    </label>
                                    <span id="reschedule_slot_loading" class="text-[10px] text-amber-700 font-semibold hidden">Memeriksa kuota...</span>
                                </div>
                                <select name="slot_id" id="reschedule_slot_select" required onchange="onRescheduleSlotChange()" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500 font-semibold cursor-pointer">
                                    @foreach($slots as $s)
                                    <option value="{{ $s->id }}" data-remaining="{{ $s->max_jeeps }}" data-label="{{ $s->slot_label }}" data-time="{{ $s->start_time }} - {{ $s->end_time }} WIB">
                                        🕒 {{ $s->start_time }} - {{ $s->end_time }} WIB ({{ $s->slot_label }})
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Warning Box jika Slot Bentrok / Penuh -->
                            <div id="reschedule_clash_box" class="p-3.5 bg-rose-50 border border-rose-200 rounded-2xl space-y-2 hidden">
                                <div class="flex items-start gap-2">
                                    <span class="text-rose-600 text-base leading-none">⚠️</span>
                                    <div class="text-[11px] text-rose-900 leading-relaxed">
                                        <strong class="font-bold block text-rose-800">Peringatan Bentrok Kuota:</strong>
                                        <span id="reschedule_clash_text">Sesi jam ini sudah penuh atau sisa kuota tidak mencukupi untuk jumlah armada booking ini.</span>
                                    </div>
                                </div>
                                <label class="flex items-center gap-2 cursor-pointer pt-1 border-t border-rose-200/80">
                                    <input type="checkbox" name="force" value="1" id="reschedule_force_checkbox" class="w-4 h-4 rounded text-rose-600 focus:ring-rose-500 cursor-pointer">
                                    <span class="text-[11px] font-bold text-rose-900">Tetap Jadwalkan (Override Kuota Paguyuban)</span>
                                </label>
                            </div>

                            <!-- Alasan Perubahan Jadwal -->
                            <div>
                                <label class="block font-bold text-slate-700 mb-1.5 text-xs">
                                    Alasan Perubahan Jadwal:
                                </label>
                                <select name="reason" id="reschedule_reason_select" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500 font-semibold cursor-pointer mb-2">
                                    <option value="Bentrok Jadwal / Overbooking Sesi">⚡ Bentrok Jadwal / Overbooking Sesi</option>
                                    <option value="Kondisi Cuaca / Jalur Wisata Ditutup Sementara">🌧️ Kondisi Cuaca / Jalur Wisata Ditutup Sementara</option>
                                    <option value="Permintaan Reschedule dari Wisatawan">🙋 Permintaan Reschedule dari Wisatawan</option>
                                    <option value="Penyesuaian Operasional Armada Loket">🚙 Penyesuaian Operasional Armada Loket</option>
                                    <option value="Lainnya">📝 Alasan Lainnya</option>
                                </select>
                                <input type="text" name="admin_note" id="reschedule_admin_note" placeholder="Catatan tambahan (opsional, misal: 'Pindah karena hujan lebat di bunker')" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-white text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500">
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="p-5 border-t border-slate-100 bg-slate-50 flex items-center justify-end gap-2.5">
                            <button type="button" onclick="closeRescheduleModal()" class="px-4 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 text-slate-700 font-semibold text-xs cursor-pointer">
                                Batal
                            </button>
                            <button type="submit" id="btnSubmitReschedule" class="px-5 py-2 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs shadow-sm hover:shadow transition-all cursor-pointer">
                                Simpan Jadwal Baru
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- MODAL POPUP LIGHTBOX BUKTI TRANSFER (1 TAB / IN-PAGE PREVIEW) -->
            <div id="modalProofLightbox" onclick="if(event.target === this) closeProofLightbox()" class="fixed inset-0 z-[60] bg-slate-900/80 backdrop-blur-sm flex items-center justify-center p-3 sm:p-5 hidden transition-all duration-200">
                <div class="bg-white rounded-3xl max-w-2xl w-full max-h-[92vh] flex flex-col overflow-hidden shadow-2xl border border-slate-100 transform transition-all animate-in fade-in zoom-in-95">
                    <!-- Lightbox Header -->
                    <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center font-bold shrink-0">
                                <svg class="w-5 h-5 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="font-bold text-slate-800 text-sm sm:text-base">Bukti Transfer Pelanggan</h3>
                                    <span id="proofLightboxCode" class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800"></span>
                                </div>
                                <p id="proofLightboxSender" class="text-xs text-slate-500 font-medium mt-0.5"></p>
                            </div>
                        </div>
                        <button type="button" onclick="closeProofLightbox()" class="w-8 h-8 rounded-lg hover:bg-slate-200 text-slate-400 hover:text-slate-700 flex items-center justify-center font-bold text-xl cursor-pointer" title="Tutup Bukti">&times;</button>
                    </div>

                    <!-- Lightbox Body Image -->
                    <div class="p-4 sm:p-6 bg-slate-900/5 flex items-center justify-center overflow-auto flex-1 min-h-[280px]">
                        <img id="proofLightboxImg" src="" alt="Bukti Transfer Pelanggan" class="max-h-[65vh] w-auto max-w-full object-contain rounded-2xl shadow-md border border-slate-200 bg-white" />
                    </div>

                    <!-- Lightbox Footer -->
                    <div class="p-3.5 sm:p-4 border-t border-slate-100 bg-slate-50 flex items-center justify-between gap-2">
                        <span id="proofLightboxBank" class="text-xs font-semibold text-slate-600 px-3 py-1 bg-white border border-slate-200 rounded-lg"></span>
                        <div class="flex items-center gap-2">
                            <button type="button" onclick="closeProofLightbox()" class="px-5 py-2 rounded-xl bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs transition-colors cursor-pointer">
                                Tutup Preview
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- JAVASCRIPT UNTUK MODAL DETAIL PEMESAN & MODAL PENUGASAN DRIVER -->
            <script>
                // State bukti transfer aktif untuk modal detail
                let currentDetailProof = { url: '', code: '', sender: '', bank: '' };
                let currentDetailBookingRawData = null;

                // Lightbox Modal Bukti Transfer (1 Tab)
                function openProofLightbox(url, code, sender, bank) {
                    if (!url) return;
                    document.getElementById('proofLightboxImg').src = url;
                    document.getElementById('proofLightboxCode').textContent = code ? ('#' + code) : '';
                    document.getElementById('proofLightboxSender').textContent = sender ? ('Pengirim: ' + sender) : 'Pengirim: Wisatawan';
                    document.getElementById('proofLightboxBank').textContent = bank || 'Transfer Bank BPD DIY';
                    document.getElementById('modalProofLightbox').classList.remove('hidden');
                }

                function closeProofLightbox() {
                    const lightbox = document.getElementById('modalProofLightbox');
                    if (lightbox) lightbox.classList.add('hidden');
                    const img = document.getElementById('proofLightboxImg');
                    if (img) img.src = '';
                }

                function showCurrentProofLightbox() {
                    if (currentDetailProof.url) {
                        openProofLightbox(currentDetailProof.url, currentDetailProof.code, currentDetailProof.sender, currentDetailProof.bank);
                    }
                }

                function openProofFromBtn(btn) {
                    if (!btn) return;
                    openProofLightbox(
                        btn.getAttribute('data-proof-url'),
                        btn.getAttribute('data-booking-code'),
                        btn.getAttribute('data-sender-name'),
                        btn.getAttribute('data-sender-bank')
                    );
                }

                // 1. Modal Booking Detail
                function openBookingDetailModal(source) {
                    let data;
                    try {
                        if (source instanceof HTMLElement) {
                            data = JSON.parse(source.getAttribute('data-booking'));
                        } else if (typeof source === 'string') {
                            data = JSON.parse(source);
                        } else {
                            data = source;
                        }
                    } catch (e) {
                        console.error('Error parsing booking detail data:', e);
                        return;
                    }

                    currentDetailBookingRawData = data;

                    document.getElementById('detail_booking_code').textContent = data.booking_code;
                    document.getElementById('detail_customer_name').textContent = data.customer_name;
                    document.getElementById('detail_customer_phone').textContent = data.customer_phone;
                    document.getElementById('detail_customer_email').textContent = data.customer_email;
                    document.getElementById('detail_created_at').textContent = data.created_at;
                    document.getElementById('detail_package_name').textContent = data.package_name;
                    document.getElementById('detail_trip_date').textContent = data.trip_date;
                    document.getElementById('detail_slot_label').textContent = data.slot_label;
                    document.getElementById('detail_armada_pax').textContent = data.jeep_quantity + ' Jeep (' + data.passenger_count + ' Orang)';
                    document.getElementById('detail_jeep_name').textContent = data.jeep_name || 'Belum Ditugaskan';
                    document.getElementById('detail_driver_name').textContent = data.driver_name;
                    document.getElementById('detail_arrival_type').textContent = data.arrival_type;

                    // Pesan / Catatan Khusus Pelanggan
                    const notesContainer = document.getElementById('detail_notes_container');
                    if (notesContainer) {
                        if (data.customer_notes && data.customer_notes.trim() !== '') {
                            notesContainer.classList.remove('hidden');
                            document.getElementById('detail_customer_notes').textContent = '"' + data.customer_notes + '"';
                        } else {
                            notesContainer.classList.add('hidden');
                        }
                    }

                    const pickupContainer = document.getElementById('detail_pickup_container');
                    const mapsBtn = document.getElementById('detail_pickup_maps_btn');
                    if (data.is_pickup || (data.pickup_address && data.pickup_address !== '-')) {
                        pickupContainer.classList.remove('hidden');
                        document.getElementById('detail_pickup_zone').textContent = 'Lokasi Jemput: ' + data.pickup_zone;
                        document.getElementById('detail_pickup_address').textContent = data.pickup_address;
                        if (mapsBtn) {
                            if (data.maps_link) {
                                mapsBtn.href = data.maps_link;
                                mapsBtn.classList.remove('hidden');
                            } else {
                                mapsBtn.classList.add('hidden');
                            }
                        }
                    } else {
                        pickupContainer.classList.add('hidden');
                    }

                    document.getElementById('detail_payment_mode').textContent = data.payment_mode;
                    document.getElementById('detail_package_subtotal').textContent = 'Rp ' + data.package_subtotal;
                    document.getElementById('detail_pickup_fee').textContent = 'Rp ' + data.pickup_fee;
                    document.getElementById('detail_total_amount').textContent = 'Rp ' + data.total_amount;
                    document.getElementById('detail_paid_amount').textContent = 'Rp ' + data.paid_amount;
                    document.getElementById('detail_remaining_amount').textContent = 'Rp ' + data.remaining_amount;

                    const badgeContainer = document.getElementById('detail_status_badge');
                    if (data.booking_status === 'FULL_PAID') {
                        badgeContainer.innerHTML = '<span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">Lunas 100%</span>';
                    } else if (data.booking_status === 'DP_PAID') {
                        badgeContainer.innerHTML = '<span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">DP Terbayar</span>';
                    } else if (data.booking_status === 'COMPLETED') {
                        badgeContainer.innerHTML = '<span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-teal-100 text-teal-800">Selesai</span>';
                    } else if (data.booking_status === 'CANCELLED') {
                        badgeContainer.innerHTML = '<span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800">Dibatalkan</span>';
                    } else {
                        badgeContainer.innerHTML = '<span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-800">Menunggu</span>';
                    }

                    const waBtn = document.getElementById('btnWhatsappCustomer');
                    let phone = (data.customer_phone || '').replace(/[^0-9]/g, '');
                    if (phone.startsWith('0')) {
                        phone = '62' + phone.substring(1);
                    }
                    if (phone) {
                        waBtn.href = 'https://wa.me/' + phone + '?text=' + encodeURIComponent('Halo ' + data.customer_name + ', kami dari Paguyuban Jeep Wukirsari Adventure mengenai reservasi Anda dengan kode booking: ' + data.booking_code);
                        waBtn.classList.remove('hidden');
                    } else {
                        waBtn.classList.add('hidden');
                    }

                    // Handle Bukti Transfer Pelanggan
                    currentDetailProof = {
                        url: data.proof_url || '',
                        code: data.booking_code || '',
                        sender: (data.sender_name || 'Wisatawan') + (data.sender_bank ? ' (' + data.sender_bank + ')' : ''),
                        bank: data.sender_bank || 'Transfer Bank BPD DIY'
                    };

                    const proofBox = document.getElementById('detail_proof_box');
                    const btnFooterViewProof = document.getElementById('btnFooterViewProof');
                    const thumbImg = document.getElementById('detail_proof_thumb');

                    if (data.proof_url) {
                        if (proofBox) proofBox.classList.remove('hidden');
                        if (thumbImg) thumbImg.src = data.proof_url;
                        if (document.getElementById('detail_proof_sender')) {
                            document.getElementById('detail_proof_sender').textContent = currentDetailProof.sender;
                        }
                        if (document.getElementById('detail_proof_bank_badge')) {
                            document.getElementById('detail_proof_bank_badge').textContent = data.sender_bank || 'Transfer Bank BPD DIY';
                        }
                        if (btnFooterViewProof) btnFooterViewProof.classList.remove('hidden');
                    } else {
                        if (proofBox) proofBox.classList.add('hidden');
                        if (thumbImg) thumbImg.src = '';
                        if (btnFooterViewProof) btnFooterViewProof.classList.add('hidden');
                    }

                    document.getElementById('modalBookingDetail').classList.remove('hidden');
                }

                function closeBookingDetailModal() {
                    document.getElementById('modalBookingDetail').classList.add('hidden');
                }

                // 2. Modal Pilih / Ganti Driver & Jeep
                function openAssignModal(source, bookingCode, customerName, customerNotes, currentJeepId, currentDriverId) {
                    let data = {};
                    if (source instanceof HTMLElement) {
                        try {
                            data = JSON.parse(source.getAttribute('data-assign'));
                        } catch (e) {
                            console.error('Error parsing assign data:', e);
                            return;
                        }
                    } else {
                        data = {
                            id: source,
                            booking_code: bookingCode,
                            customer_name: customerName,
                            customer_notes: customerNotes,
                            jeep_id: currentJeepId,
                            driver_id: currentDriverId
                        };
                    }

                    const form = document.getElementById('formAssignDriver');
                    form.action = "{{ url('admin/bookings') }}/" + data.id + "/assign";

                    document.getElementById('assign_booking_title').textContent = '#' + data.booking_code + ' - ' + data.customer_name;

                    const reqBox = document.getElementById('assign_request_box');
                    const reqText = document.getElementById('assign_request_text');
                    if (data.customer_notes && String(data.customer_notes).trim() !== '' && data.customer_notes !== 'null') {
                        reqBox.classList.remove('hidden');
                        reqBox.className = "p-3.5 bg-amber-50 rounded-2xl border border-amber-200 space-y-1";
                        reqText.className = "text-amber-950 text-xs font-semibold italic leading-relaxed";
                        reqText.textContent = '"' + data.customer_notes + '"';
                    } else {
                        reqBox.classList.remove('hidden');
                        reqBox.className = "p-3 bg-slate-50 rounded-2xl border border-slate-200 space-y-0.5";
                        reqText.className = "text-slate-500 text-xs";
                        reqText.textContent = 'Tidak ada catatan atau permintaan khusus dari pelanggan.';
                    }

                    const driverSelect = document.getElementById('assign_driver_select');
                    if (data.driver_id) {
                        driverSelect.value = data.driver_id;
                    } else {
                        driverSelect.value = 'random';
                    }

                    document.getElementById('modalAssignDriver').classList.remove('hidden');
                }

                function closeAssignModal() {
                    document.getElementById('modalAssignDriver').classList.add('hidden');
                }

                // 3. Modal Reschedule / Ubah Jadwal Bentrok
                let currentRescheduleData = {};

                function openRescheduleModal(source) {
                    let data = {};
                    if (source instanceof HTMLElement) {
                        try {
                            data = JSON.parse(source.getAttribute('data-reschedule'));
                        } catch (e) {
                            console.error('Error parsing reschedule data:', e);
                            return;
                        }
                    } else if (typeof source === 'object' && source !== null) {
                        data = {
                            id: source.id,
                            booking_code: source.booking_code,
                            customer_name: source.customer_name,
                            customer_phone: source.customer_phone,
                            jeep_quantity: source.jeep_quantity || 1,
                            passenger_count: source.passenger_count || 4,
                            trip_date: source.raw_trip_date || source.trip_date || '',
                            trip_date_formatted: source.trip_date || '',
                            slot_id: source.slot_id || '',
                            slot_label: source.slot_label || 'Sesi Jam'
                        };
                    } else {
                        return;
                    }

                    currentRescheduleData = data;

                    const form = document.getElementById('formRescheduleBooking');
                    form.action = "{{ url('admin/bookings') }}/" + data.id + "/reschedule";

                    document.getElementById('reschedule_booking_title').textContent = '#' + data.booking_code + ' - ' + data.customer_name;
                    document.getElementById('reschedule_pax_badge').textContent = (data.jeep_quantity || 1) + ' Jeep (' + (data.passenger_count || 4) + ' Org)';
                    document.getElementById('reschedule_current_schedule').textContent = (data.trip_date_formatted || data.trip_date) + ' • ' + (data.slot_label || '');

                    const dateInput = document.getElementById('reschedule_date_input');
                    if (data.trip_date) {
                        const d = new Date(data.trip_date);
                        if (!isNaN(d.getTime())) {
                            dateInput.value = data.trip_date.length === 10 ? data.trip_date : d.toISOString().split('T')[0];
                        } else {
                            dateInput.value = data.trip_date;
                        }
                    }

                    const slotSelect = document.getElementById('reschedule_slot_select');
                    if (data.slot_id) {
                        slotSelect.value = data.slot_id;
                    }

                    // Reset form & clash warning box
                    document.getElementById('reschedule_force_checkbox').checked = false;
                    document.getElementById('reschedule_clash_box').classList.add('hidden');
                    document.getElementById('reschedule_admin_note').value = '';

                    // Cek kuota slot realtime
                    if (dateInput.value) {
                        onRescheduleDateChange(dateInput.value);
                    }

                    document.getElementById('modalRescheduleBooking').classList.remove('hidden');
                }

                function closeRescheduleModal() {
                    document.getElementById('modalRescheduleBooking').classList.add('hidden');
                }

                function openRescheduleFromDetail() {
                    if (currentDetailBookingRawData) {
                        closeBookingDetailModal();
                        openRescheduleModal(currentDetailBookingRawData);
                    }
                }

                async function onRescheduleDateChange(date) {
                    if (!date) return;
                    const loading = document.getElementById('reschedule_slot_loading');
                    const select = document.getElementById('reschedule_slot_select');
                    if (loading) loading.classList.remove('hidden');

                    try {
                        const excludeId = currentRescheduleData.id || 0;
                        const res = await fetch("{{ route('admin.slot-availability') }}?date=" + encodeURIComponent(date) + "&exclude_booking_id=" + excludeId);
                        const json = await res.json();

                        if (json.slots && json.slots.length > 0) {
                            const currentSelectedSlotId = select.value;
                            select.innerHTML = '';
                            json.slots.forEach(s => {
                                const opt = document.createElement('option');
                                opt.value = s.id;
                                opt.setAttribute('data-remaining', s.remaining_jeeps);
                                opt.setAttribute('data-label', s.slot_label);
                                opt.setAttribute('data-time', s.time_range);

                                const needed = parseInt(currentRescheduleData.jeep_quantity || 1, 10);
                                if (s.remaining_jeeps <= 0) {
                                    opt.textContent = `🔴 ${s.time_range} • Penuh / 0 Jeep (${s.slot_label})`;
                                } else if (s.remaining_jeeps < needed) {
                                    opt.textContent = `🟡 ${s.time_range} • Sisa ${s.remaining_jeeps} Jeep (Kurang) (${s.slot_label})`;
                                } else {
                                    opt.textContent = `🟢 ${s.time_range} • Sisa ${s.remaining_jeeps} Jeep Tersedia (${s.slot_label})`;
                                }
                                select.appendChild(opt);
                            });

                            if (currentSelectedSlotId) {
                                select.value = currentSelectedSlotId;
                            }
                        }
                    } catch (err) {
                        console.error('Error saat memeriksa kuota sesi:', err);
                    } finally {
                        if (loading) loading.classList.add('hidden');
                        onRescheduleSlotChange();
                    }
                }

                function onRescheduleSlotChange() {
                    const select = document.getElementById('reschedule_slot_select');
                    const selectedOpt = select.options[select.selectedIndex];
                    const clashBox = document.getElementById('reschedule_clash_box');
                    const clashText = document.getElementById('reschedule_clash_text');
                    const forceCheckbox = document.getElementById('reschedule_force_checkbox');

                    if (!selectedOpt) return;

                    const remaining = parseInt(selectedOpt.getAttribute('data-remaining') || '99', 10);
                    const needed = parseInt(currentRescheduleData.jeep_quantity || 1, 10);

                    if (remaining < needed) {
                        clashBox.classList.remove('hidden');
                        if (remaining <= 0) {
                            clashText.textContent = `Sesi ini sudah penuh (tersisa 0 jeep). Booking ini membutuhkan ${needed} armada. Tetap jadwalkan jika Paguyuban menambah armada khusus.`;
                        } else {
                            clashText.textContent = `Sesi ini hanya tersisa ${remaining} jeep, sedangkan booking ini membutuhkan ${needed} armada.`;
                        }
                    } else {
                        clashBox.classList.add('hidden');
                        forceCheckbox.checked = false;
                    }
                }

                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        const lightbox = document.getElementById('modalProofLightbox');
                        if (lightbox && !lightbox.classList.contains('hidden')) {
                            closeProofLightbox();
                            return;
                        }
                        closeBookingDetailModal();
                        closeAssignModal();
                        closeRescheduleModal();
                    }
                });
            </script>

        </main>
    </div>

</body>
</html>

