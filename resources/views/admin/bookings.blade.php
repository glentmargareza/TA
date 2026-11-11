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
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#F4F6F8] text-slate-800 min-h-screen flex flex-col md:flex-row antialiased">

    @include('admin.partials.sidebar')

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 min-h-screen overflow-x-hidden">
        
        <!-- Header Banner -->
        <header class="bg-white border-b border-slate-200 px-6 sm:px-8 py-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 sticky top-0 z-20 shadow-2xs">
            <div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>
                    Manajemen Booking Wisatawan
                </h1>
                <p class="text-xs text-slate-500 mt-0.5">Kelola reservasi, verifikasi pembayaran loket, penugasan armada & driver, serta detail pemesan.</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="px-3 py-1.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                    Total: {{ $bookings->total() }} Booking Terdaftar
                </span>
                <a href="{{ route('admin.dashboard') }}" class="px-3.5 py-1.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-100 transition-all">
                    &larr; Dashboard
                </a>
            </div>
        </header>

        <main class="p-6 sm:p-8 space-y-6 flex-1">

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
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider text-[10px] border-b border-slate-200">
                            <tr>
                                <th class="py-3 px-4">Kode Booking</th>
                                <th class="py-3 px-4">Pemesan</th>
                                <th class="py-3 px-4">Paket & Trip</th>
                                <th class="py-3 px-4">Armada/Pax</th>
                                <th class="py-3 px-4">Status & Sisa</th>
                                <th class="py-3 px-4">Penugasan Jeep</th>
                                <th class="py-3 px-4 text-center">Aksi Loket</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($bookings as $b)
                            @php
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
                                    'payment_mode' => $b->payment_mode === 'DP' ? 'Uang Muka (DP 50%)' : 'Pembayaran Penuh (100%)',
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
                                    <span class="text-slate-500 text-[11px]">{{ $b->trip_date ? $b->trip_date->translatedFormat('d/m/Y') : '-' }}</span>
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
                                                <svg class="w-3.5 h-3.5 text-emerald-700 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                                                {{ $assign->jeep?->model_name ?? 'Jeep Armada' }}
                                            </span>
                                            <button type="button" data-assign='@json($assignData)' onclick="openAssignModal(this)" class="text-[10px] font-bold text-emerald-800 hover:text-emerald-950 px-2 py-0.5 rounded-md bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 cursor-pointer inline-flex items-center gap-0.5 shrink-0" title="Ubah driver atau jeep">
                                                <span>✏️ Ganti</span>
                                            </button>
                                        </div>
                                        <div class="text-[11px] text-slate-500">
                                            Driver: <strong class="text-slate-800">{{ $assign->driver?->full_name ?? 'Auto Paguyuban' }}</strong>
                                        </div>
                                        @if($b->customer_notes)
                                        <div class="p-1 px-1.5 rounded-lg bg-amber-50 border border-amber-200/90 text-[10px] text-amber-900 flex items-start gap-1 mt-1">
                                            <span class="shrink-0 font-bold">💬 Req:</span>
                                            <span class="truncate font-semibold" title="{{ $b->customer_notes }}">{{ Str::limit($b->customer_notes, 28) }}</span>
                                        </div>
                                        @endif
                                    </div>
                                    @else
                                    <div class="space-y-1">
                                        <button type="button" data-assign='@json($assignData)' onclick="openAssignModal(this)" class="px-2.5 py-1.5 bg-emerald-800 hover:bg-emerald-900 text-white rounded-lg text-[10px] font-bold shadow-xs transition-all flex items-center gap-1 cursor-pointer">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                            <span>Pilih Driver & Jeep</span>
                                        </button>
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

                                        @if($b->booking_status === 'DP_PAID')
                                        <form action="{{ route('admin.settle', $b->id) }}" method="POST" onsubmit="return confirm('Konfirmasi terima pelunasan sisa tagihan untuk booking ini?')">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-[10px] shadow-xs cursor-pointer">
                                                Pelunasan
                                            </button>
                                        </form>
                                        @endif

                                        @if($b->booking_status !== 'CANCELLED' && $b->booking_status !== 'COMPLETED')
                                        <form action="{{ route('admin.cancel', $b->id) }}" method="POST" onsubmit="return confirm('Batalkan booking #{{ $b->booking_code }}?')">
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
                    </div>

                    <!-- Modal Footer -->
                    <div class="p-5 sm:p-6 border-t border-slate-100 bg-slate-50 flex items-center justify-between">
                        <a id="btnWhatsappCustomer" href="#" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm hover:shadow transition-all cursor-pointer">
                            <span>Hubungi Pemesan (WhatsApp)</span>
                        </a>
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
                                    <h3 class="font-bold text-slate-800 text-base">Atur / Ganti Driver & Armada</h3>
                                    <p class="text-xs text-slate-500">Pilih driver manual sesuai permintaan pelanggan loket/online.</p>
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

                            <!-- 2. Dropdown Pilih Unit Jeep Manual -->
                            <div>
                                <label class="block font-bold text-slate-700 mb-1.5 text-xs">
                                    Pilih Armada Jeep:
                                </label>
                                <select name="jeep_id" id="assign_jeep_select" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 font-semibold cursor-pointer">
                                    <option value="random">🎲 Auto-Pilih Jeep Tersedia (Ready)</option>
                                    @foreach($jeeps as $j)
                                    <option value="{{ $j->id }}">🚙 {{ $j->model_name }} [{{ $j->plate_number }}] &mdash; {{ $j->status === 'READY' ? '🟢 TERSEDIA' : ($j->status === 'ON_TRIP' ? '🔵 ON TRIP' : '🟡 OFF/SERVIS') }}</option>
                                    @endforeach
                                </select>
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

            <!-- JAVASCRIPT UNTUK MODAL DETAIL PEMESAN & MODAL PENUGASAN DRIVER -->
            <script>
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
                    form.action = '/admin/bookings/' + data.id + '/assign';

                    document.getElementById('assign_booking_title').textContent = '#' + data.booking_code + ' - ' + data.customer_name;

                    const reqBox = document.getElementById('assign_request_box');
                    const reqText = document.getElementById('assign_request_text');
                    if (data.customer_notes && String(data.customer_notes).trim() !== '' && data.customer_notes !== 'null') {
                        reqBox.classList.remove('hidden');
                        reqText.textContent = '"' + data.customer_notes + '"';
                    } else {
                        reqBox.classList.remove('hidden');
                        reqText.textContent = 'Tidak ada catatan atau permintaan khusus dari pelanggan.';
                    }

                    const driverSelect = document.getElementById('assign_driver_select');
                    if (data.driver_id) {
                        driverSelect.value = data.driver_id;
                    } else {
                        driverSelect.value = 'random';
                    }

                    const jeepSelect = document.getElementById('assign_jeep_select');
                    if (data.jeep_id) {
                        jeepSelect.value = data.jeep_id;
                    } else {
                        jeepSelect.value = 'random';
                    }

                    document.getElementById('modalAssignDriver').classList.remove('hidden');
                }

                function closeAssignModal() {
                    document.getElementById('modalAssignDriver').classList.add('hidden');
                }

                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        closeBookingDetailModal();
                        closeAssignModal();
                    }
                });
            </script>

        </main>
    </div>

</body>
</html>

