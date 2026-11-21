<x-admin-layout title="Manajemen Booking Wisatawan | Jeep Wukirsari">

    <!-- Header Banner -->
    <x-admin.header title="Manajemen Booking Wisatawan" subtitle="Kelola reservasi, verifikasi pembayaran loket, penugasan armada & driver, serta detail pemesan.">
        <x-slot:actions>
            <span class="px-3 py-1.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200 shadow-2xs">
                Total: {{ $bookings->total() }} Booking Terdaftar
            </span>
        </x-slot:actions>
    </x-admin.header>

    <main class="p-4 sm:p-8 space-y-5 sm:space-y-6 flex-1">

        @if(session('success'))
        <x-alert type="success" :message="session('success')" />
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
                                <th class="py-3 px-4 text-center whitespace-nowrap">Aksi Loket</th>
                                <th class="py-3 px-4">Kode Booking</th>
                                <th class="py-3 px-4">Pemesan</th>
                                <th class="py-3 px-4">Paket & Trip</th>
                                <th class="py-3 px-4">Armada/Pax</th>
                                <th class="py-3 px-4">Status & Sisa</th>
                                <th class="py-3 px-4">Penugasan Driver</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($bookings as $b)
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

                                $detailData = [
                                    'id' => $b->id,
                                    'booking_code' => $b->booking_code,
                                    'customer_name' => $b->customer_name,
                                    'customer_phone' => $b->customer_phone,
                                    'customer_email' => $b->customer_email ?? '-',
                                    'package_name' => $b->package?->name ?? 'Paket Jeep',
                                    'trip_date' => $b->trip_date ? $b->trip_date->translatedFormat('d F Y') : '-',
                                    'slot_label' => ($b->slot ? substr((string)$b->slot->start_time, 0, 5) . ' - ' . substr((string)$b->slot->end_time, 0, 5) . ' WIB' : '09:00 - 11:00 WIB'),
                                    'slot_time' => ($b->slot ? substr((string)$b->slot->start_time, 0, 5) . ' - ' . substr((string)$b->slot->end_time, 0, 5) . ' WIB' : '09:00 - 11:00 WIB'),
                                    'slot_name' => ($b->slot ? trim(preg_replace('/\s*\(\d{2}:\d{2}\s*WIB\)/i', '', (string)$b->slot->slot_label)) : 'Sesi'),
                                    'jeep_quantity' => $b->jeep_quantity,
                                    'passenger_count' => $b->passenger_count,
                                    'is_pickup' => strtolower((string)$b->arrival_type) === 'pickup',
                                    'arrival_type' => strtolower((string)$b->arrival_type) === 'pickup' ? 'Jemput di Hotel / Alamat' : 'Datang Sendiri ke Basecamp',
                                    'pickup_zone' => $b->pickupZone?->name ?? ($b->pickup_distance_km ? 'Antar-Jemput (' . number_format($b->pickup_distance_km, 1) . ' km)' : 'Layanan Antar-Jemput'),
                                    'pickup_address' => $b->pickup_address ?? '-',
                                    'payment_mode' => $b->payment_mode === 'DP' ? 'Uang Muka (DP 30%)' : 'Pembayaran Penuh (100%)',
                                    'payment_channel' => $b->payment_channel,
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
                                ];

                                $cleanSlotTime = $b->slot ? substr((string)$b->slot->start_time, 0, 5) . ' - ' . substr((string)$b->slot->end_time, 0, 5) . ' WIB' : '09:00 - 11:00 WIB';
                                $cleanSlotName = $b->slot ? trim(preg_replace('/\s*\(\d{2}:\d{2}\s*WIB\)/i', '', (string)$b->slot->slot_label)) : 'Sesi';

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
                                    'slot_time' => $cleanSlotTime,
                                    'slot_name' => $cleanSlotName,
                                    'slot_label' => $cleanSlotTime . ' • ' . $cleanSlotName,
                                ];
                            @endphp
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="py-3 px-4 text-center whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <!-- 1. Ikon Pensil: Atur / Ganti Driver -->
                                        @if($b->booking_status !== 'CANCELLED')
                                        <button type="button" data-assign='@json($assignData)' onclick="openAssignModal(this)" class="p-1.5 rounded-lg border border-slate-200 bg-white hover:bg-emerald-50 hover:border-emerald-300 text-slate-600 hover:text-emerald-700 text-xs font-semibold shadow-2xs transition-all cursor-pointer" title="{{ $assign ? 'Ganti Driver Paguyuban' : 'Atur / Pilih Driver Paguyuban' }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                        </button>
                                        @endif

                                        <!-- 2. Ikon Mata: Detail Informasi Pemesan -->
                                        <button type="button" data-booking='@json($detailData)' onclick="openBookingDetailModal(this)" class="p-1.5 rounded-lg border border-slate-200 bg-white hover:bg-blue-50 hover:border-blue-300 text-slate-600 hover:text-blue-700 text-xs font-semibold shadow-2xs transition-all cursor-pointer" title="Lihat detail lengkap pemesan">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </button>

                                        @if($b->booking_status === 'DP_PAID')
                                        <form action="{{ route('admin.settle', $b->id) }}" method="POST"
                                              data-confirm-title="Konfirmasi Pelunasan Loket"
                                              data-confirm-message="Terima pelunasan sisa tagihan untuk booking <strong class='font-bold text-emerald-800'>#{{ $b->booking_code }}</strong> atas nama <strong class='text-slate-800'>{{ $b->customer_name }}</strong> sebesar <strong class='text-emerald-700 font-bold'>Rp {{ number_format($b->remaining_amount ?? 0, 0, ',', '.') }}</strong>?"
                                              data-confirm-type="success"
                                              data-confirm-text="Ya, Terima Pelunasan"
                                              data-confirm-cancel="Batal">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-[10px] shadow-xs cursor-pointer" title="Pelunasan Kasir Loket">
                                                Pelunasan
                                            </button>
                                        </form>
                                        @endif

                                        <!-- 3. Ikon Silang: Batalkan Booking -->
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
                                        <span class="text-slate-500 text-[11px]">{{ $b->trip_date ? $b->trip_date->translatedFormat('d/m/Y') : '-' }} &bull; {{ $b->slot ? substr((string)$b->slot->start_time, 0, 5) : '09:00' }} WIB</span>
                                        @if($b->booking_status !== 'CANCELLED')
                                        <button type="button" data-reschedule='@json($rescheduleData)' onclick="openRescheduleModal(this)" class="p-1 rounded-md bg-slate-100 hover:bg-emerald-50 border border-slate-200 hover:border-emerald-300 text-slate-500 hover:text-emerald-700 inline-flex items-center justify-center cursor-pointer transition-colors shadow-2xs" title="Ganti Jadwal / Reschedule">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="font-medium text-slate-700">{{ $b->jeep_quantity }} Jeep ({{ $b->passenger_count }} Org)</span>
                                </td>
                                <td class="py-3 px-4">
                                    @if($b->booking_status === 'FULL_PAID')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                        LUNAS
                                    </span>
                                    @elseif($b->booking_status === 'DP_PAID')
                                    <div>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">
                                            DP TERBAYAR
                                        </span>
                                        <span class="block text-[10px] text-rose-600 font-bold mt-0.5">
                                            Sisa: Rp {{ number_format($b->remaining_amount, 0, ',', '.') }}
                                        </span>
                                    </div>
                                    @elseif($b->booking_status === 'CANCELLED')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800">
                                        DIBATALKAN
                                    </span>
                                    @elseif($b->booking_status === 'COMPLETED')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-teal-100 text-teal-800">
                                        SELESAI
                                    </span>
                                    @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-800">
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
                                        <span class="font-bold text-slate-800 text-xs flex items-center gap-1.5 truncate">
                                            <svg class="w-3.5 h-3.5 text-emerald-700 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                            {{ $assign->driver?->full_name ?? 'Auto Paguyuban' }}
                                        </span>
                                        @if($assign->driver?->phone_number)
                                        <div class="text-[11px] text-slate-500">
                                            HP: {{ $assign->driver->phone_number }}
                                        </div>
                                        @endif
                                        @if($b->customer_notes)
                                        <div class="p-1 px-1.5 rounded-lg bg-amber-50 border border-amber-200/90 text-[10px] text-amber-900 flex items-start gap-1 mt-1">
                                            <span class="shrink-0 font-bold">Catatan:</span>
                                            <span class="truncate font-semibold" title="{{ $b->customer_notes }}">{{ Str::limit($b->customer_notes, 28) }}</span>
                                        </div>
                                        @endif
                                    </div>
                                    @else
                                    <div class="space-y-1">
                                        @if($b->booking_status === 'CANCELLED')
                                        <span class="text-slate-400 text-xs italic">-</span>
                                        @elseif($b->booking_status === 'PENDING')
                                        <div class="text-[11px] text-slate-500 font-medium">
                                            <span>Auto saat bayar</span>
                                        </div>
                                        @else
                                        <div class="text-[11px] text-amber-800 font-semibold">
                                            <span>Belum Ditugaskan</span>
                                        </div>
                                        @endif
                                        @if($b->customer_notes)
                                        <div class="p-1 px-1.5 rounded-lg bg-amber-50 border border-amber-200/90 text-[10px] text-amber-900 flex items-start gap-1 mt-1">
                                            <span class="shrink-0 font-bold">Catatan:</span>
                                            <span class="truncate font-semibold" title="{{ $b->customer_notes }}">{{ Str::limit($b->customer_notes, 28) }}</span>
                                        </div>
                                        @endif
                                    </div>
                                    @endif
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

            <!-- MODAL DETAIL LENGKAP INFORMASI PEMESAN (CLEAN MINIMALIST SHEET) -->
            <div id="modalBookingDetail" onclick="if(event.target === this) closeBookingDetailModal()" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4 sm:p-6 hidden transition-opacity duration-200">
                <div class="bg-white rounded-2xl max-w-xl w-full max-h-[90vh] flex flex-col shadow-2xl border border-slate-200 overflow-hidden text-slate-800">
                    
                    <!-- Header: Bersih & Tenang -->
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-white shrink-0">
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-xs text-slate-400 font-medium">Reservasi</span>
                                <span id="detail_booking_code" class="font-mono font-bold text-slate-900 text-base"></span>
                                <button type="button" onclick="copyDetailText('detail_booking_code', this)" class="text-slate-400 hover:text-emerald-600 transition p-1" title="Salin Kode">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                </button>
                                <span id="detail_status_badge"></span>
                            </div>
                            <p id="detail_created_at" class="text-[11px] text-slate-400 mt-0.5"></p>
                        </div>
                        <button type="button" onclick="closeBookingDetailModal()" class="w-8 h-8 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-600 flex items-center justify-center text-xl transition cursor-pointer">&times;</button>
                    </div>

                    <!-- Modal Body (Bersih, Berjarak Nyaman, Mudah Dipindai) -->
                    <div class="p-6 space-y-5 overflow-y-auto">

                        <!-- 1. Kontak & Pemesan -->
                        <div>
                            <h4 class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Informasi Pemesan</h4>
                            <div class="bg-slate-50/70 rounded-xl p-3.5 border border-slate-100 space-y-2">
                                <div class="flex items-center justify-between flex-wrap gap-2">
                                    <div>
                                        <span id="detail_customer_name" class="font-bold text-slate-900 text-sm sm:text-base block"></span>
                                        <span id="detail_customer_email" class="text-xs text-slate-500 block"></span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span id="detail_customer_phone" class="font-mono text-xs font-semibold text-slate-700 bg-white px-2.5 py-1 rounded-lg border border-slate-200"></span>
                                        <button type="button" onclick="copyDetailText('detail_customer_phone', this)" class="text-slate-400 hover:text-emerald-600 text-xs p-1" title="Salin HP">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                        </button>
                                        <a id="btnQuickWa" href="#" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-xs transition">
                                            <span>WhatsApp</span>
                                        </a>
                                    </div>
                                </div>

                                <!-- Catatan Wisatawan (Hanya tampil jika ada) -->
                                <div id="detail_notes_container" class="pt-2 border-t border-slate-200/60 hidden">
                                    <span class="text-[10px] text-amber-800 font-bold block uppercase tracking-wider">Catatan Pemesan:</span>
                                    <p id="detail_customer_notes" class="text-xs text-slate-700 italic mt-0.5"></p>
                                </div>
                            </div>
                        </div>

                        <!-- 2. Jadwal & Penugasan Armada -->
                        <div>
                            <h4 class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Trip & Armada</h4>
                            <div class="border border-slate-100 rounded-xl overflow-hidden divide-y divide-slate-100 text-xs">
                                <div class="flex items-center justify-between p-3 bg-white">
                                    <span class="text-slate-400 font-medium">Paket Wisata</span>
                                    <span id="detail_package_name" class="font-bold text-slate-900 text-right"></span>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-slate-50/50">
                                    <span class="text-slate-400 font-medium">Jadwal & Sesi</span>
                                    <div class="text-right">
                                        <span id="detail_trip_date" class="font-semibold text-slate-800 block"></span>
                                        <span id="detail_slot_label" class="text-[11px] text-slate-500 block"></span>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-white">
                                    <span class="text-slate-400 font-medium">Armada & Kapasitas</span>
                                    <span id="detail_armada_pax" class="font-medium text-slate-800 text-right"></span>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-slate-50/50">
                                    <span class="text-slate-400 font-medium">Armada Jeep Ditugaskan</span>
                                    <span id="detail_jeep_name" class="font-semibold text-slate-900 text-right"></span>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-white">
                                    <span class="text-slate-400 font-medium">Driver Paguyuban</span>
                                    <span id="detail_driver_name" class="font-bold text-emerald-800 text-right"></span>
                                </div>
                            </div>
                        </div>

                        <!-- 3. Titik Kumpul / Kedatangan -->
                        <div>
                            <h4 class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Lokasi Kedatangan</h4>
                            <span id="detail_arrival_type" class="hidden"></span>

                            <!-- Kondisi A: Antar-Jemput -->
                            <div id="detail_pickup_container" class="hidden p-3.5 bg-amber-50/70 rounded-xl border border-amber-200/80 text-xs space-y-1">
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-amber-950">Layanan Antar-Jemput (Pickup Hotel / Alamat)</span>
                                    <a id="detail_pickup_maps_btn" href="#" target="_blank" rel="noopener noreferrer" class="hidden font-semibold text-emerald-700 hover:text-emerald-900 text-xs hover:underline">
                                        Buka Google Maps &rarr;
                                    </a>
                                </div>
                                <p class="text-slate-700">
                                    <strong id="detail_pickup_zone" class="text-slate-900 font-semibold"></strong> &bull; <span id="detail_pickup_address"></span>
                                </p>
                            </div>

                            <!-- Kondisi B: Datang Sendiri ke Basecamp -->
                            <div id="detail_basecamp_container" class="hidden p-3.5 bg-slate-50 rounded-xl border border-slate-200 text-xs space-y-1">
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-slate-900">Datang Sendiri ke Basecamp Paguyuban</span>
                                    <a href="https://maps.google.com/?q=Basecamp+Jeep+Wukirsari+Adventure+Cangkringan+Sleman" target="_blank" rel="noopener noreferrer" class="font-semibold text-emerald-700 hover:text-emerald-900 text-xs hover:underline">
                                        Buka Rute Basecamp &rarr;
                                    </a>
                                </div>
                                <p class="text-slate-500">
                                    Basecamp Jeep Wukirsari: Dusun Pusung, Wukirsari, Cangkringan, Sleman. Wisatawan berkumpul langsung di meeting point.
                                </p>
                            </div>
                        </div>

                        <!-- 4. Rincian Tagihan & Kasir Loket -->
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Pembayaran & Status Kasir</h4>
                                <span id="detail_payment_mode_badge" class="text-[11px] font-medium text-slate-500"></span>
                            </div>
                            
                            <div class="border border-slate-100 rounded-xl overflow-hidden divide-y divide-slate-100 text-xs">
                                <div class="flex justify-between p-3 bg-white">
                                    <span class="text-slate-400 font-medium">Biaya Paket</span>
                                    <span id="detail_package_subtotal" class="font-medium text-slate-700"></span>
                                </div>
                                <div class="flex justify-between p-3 bg-white">
                                    <span class="text-slate-400 font-medium">Biaya Antar-Jemput</span>
                                    <span id="detail_pickup_fee" class="font-medium text-slate-700"></span>
                                </div>
                                <div class="flex justify-between p-3 bg-slate-50/70 font-semibold">
                                    <span class="text-slate-700">Total Tagihan</span>
                                    <span id="detail_total_amount" class="text-slate-900 text-sm font-bold"></span>
                                </div>
                                <div class="flex justify-between p-3 bg-white items-center">
                                    <span class="text-slate-500 font-medium">Metode / Saluran Bayar</span>
                                    <span id="detail_payment_channel" class="font-bold text-slate-800 text-xs bg-slate-100 px-2.5 py-1 rounded-lg border border-slate-200"></span>
                                </div>
                                <div class="flex justify-between p-3 bg-emerald-50/40">
                                    <span class="text-emerald-800 font-medium">Telah Dibayar (Transfer)</span>
                                    <span id="detail_paid_amount" class="text-emerald-700 font-bold text-sm"></span>
                                </div>
                                <div id="detail_remaining_box" class="flex justify-between p-3.5 items-center">
                                    <div>
                                        <span id="detail_remaining_label" class="block font-bold"></span>
                                        <span id="detail_remaining_sublabel" class="text-[10px] block mt-0.5"></span>
                                    </div>
                                    <span id="detail_remaining_amount" class="text-base font-extrabold"></span>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Footer: Bersih & Fungsional -->
                    <div class="px-6 py-3.5 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between shrink-0">
                        <div class="flex items-center gap-2">
                            <a id="btnWhatsappCustomer" href="#" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-xs transition">
                                <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                                <span>Hubungi WhatsApp</span>
                            </a>
                            <button type="button" id="btnDetailReschedule" onclick="openRescheduleFromDetail()" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-amber-500 hover:bg-amber-600 text-white font-medium text-xs transition cursor-pointer">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span>Ubah Jadwal</span>
                            </button>
                        </div>
                        <button type="button" onclick="closeBookingDetailModal()" class="px-4 py-2 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-medium text-xs transition cursor-pointer">
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
                                    <svg class="w-3.5 h-3.5 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
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
                                    <option value="random">Auto-Alokasi Paguyuban (Acak / Giliran Driver)</option>
                                    @foreach($drivers as $d)
                                    <option value="{{ $d->id }}">{{ $d->full_name }} (HP: {{ $d->phone_number }})</option>
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
                        <!-- Modal Header: Bersih & Nyaman Dilihat -->
                        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-white">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-800 flex items-center justify-center font-bold">
                                    <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-slate-800 text-base">Ubah Jadwal Booking (Reschedule)</h3>
                                    <p class="text-xs text-slate-500">Pindahkan sesi tanggal & jam tanpa ribet</p>
                                </div>
                            </div>
                            <button type="button" onclick="closeRescheduleModal()" class="w-8 h-8 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-700 flex items-center justify-center font-bold text-lg cursor-pointer transition-colors">&times;</button>
                        </div>

                        <!-- Modal Body -->
                        <div class="p-6 space-y-4 text-xs">
                            <!-- Card Info Booking Saat Ini: Rapi, Terstruktur 2 Kolom -->
                            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200/80 space-y-3">
                                <div class="flex items-center justify-between pb-2.5 border-b border-slate-200/60">
                                    <div class="flex items-center gap-2">
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Booking:</span>
                                        <span id="reschedule_booking_code" class="font-mono font-bold text-slate-800 text-xs px-2 py-0.5 bg-white rounded-md border border-slate-200 shadow-2xs"></span>
                                    </div>
                                    <span id="reschedule_pax_badge" class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800"></span>
                                </div>

                                <div class="grid grid-cols-2 gap-3 text-xs">
                                    <div>
                                        <span class="text-[10px] text-slate-400 block mb-0.5">Nama Pemesan</span>
                                        <span id="reschedule_customer_name" class="font-bold text-slate-800 block truncate"></span>
                                    </div>
                                    <div>
                                        <span class="text-[10px] text-slate-400 block mb-0.5">Paket Wisata</span>
                                        <span id="reschedule_package_name" class="font-semibold text-slate-700 block truncate"></span>
                                    </div>
                                    <div>
                                        <span class="text-[10px] text-slate-400 block mb-0.5">Tanggal Trip Saat Ini</span>
                                        <span id="reschedule_current_date" class="font-bold text-slate-800 block"></span>
                                    </div>
                                    <div>
                                        <span class="text-[10px] text-slate-400 block mb-0.5">Sesi Jam Saat Ini</span>
                                        <span id="reschedule_current_time" class="font-bold text-emerald-800 block"></span>
                                        <span id="reschedule_current_slot_name" class="text-[10px] text-slate-500 block"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Pilih Tanggal Baru -->
                            <div>
                                <label class="block font-bold text-slate-700 mb-1.5 text-xs">
                                    Pilih Tanggal Baru
                                </label>
                                <input type="date" name="trip_date" id="reschedule_date_input" required min="{{ date('Y-m-d') }}" onchange="onRescheduleDateChange(this.value)" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-600 font-semibold cursor-pointer shadow-2xs">
                            </div>

                            <!-- Pilih Sesi Jam Baru -->
                            <div>
                                <div class="flex items-center justify-between mb-1.5">
                                    <label class="block font-bold text-slate-700 text-xs">
                                        Pilih Sesi Jam Baru
                                    </label>
                                    <span id="reschedule_slot_loading" class="text-[10px] text-emerald-700 font-semibold hidden animate-pulse">Memeriksa kuota sesi...</span>
                                </div>
                                <select name="slot_id" id="reschedule_slot_select" required onchange="onRescheduleSlotChange()" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-600 font-medium cursor-pointer shadow-2xs">
                                    @foreach($slots as $s)
                                    @php
                                        $sTime = substr((string)$s->start_time, 0, 5);
                                        $eTime = substr((string)$s->end_time, 0, 5);
                                        $cleanName = trim(preg_replace('/\s*\(\d{2}:\d{2}\s*WIB\)/i', '', (string)$s->slot_label)) ?: ('Sesi ' . $s->id);
                                        $timeRange = $sTime . ' - ' . $eTime . ' WIB';
                                    @endphp
                                    <option value="{{ $s->id }}" data-remaining="{{ $s->max_jeeps }}" data-label="{{ $cleanName }}" data-time="{{ $timeRange }}">
                                        {{ $timeRange }} &mdash; {{ $cleanName }} (Sisa {{ $s->max_jeeps }} Jeep)
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Warning Box jika Slot Bentrok / Penuh -->
                            <div id="reschedule_clash_box" class="p-3.5 bg-rose-50 border border-rose-200 rounded-2xl space-y-2 hidden">
                                <div class="flex items-start gap-2.5">
                                    <div class="w-6 h-6 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center shrink-0 mt-0.5 font-bold">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    </div>
                                    <div class="text-[11px] text-rose-900 leading-relaxed">
                                        <strong class="font-bold block text-rose-800">Peringatan Kuota Sesi Penuh / Tidak Cukup:</strong>
                                        <span id="reschedule_clash_text">Sesi jam ini sudah penuh atau sisa kuota armada tidak mencukupi untuk booking ini.</span>
                                    </div>
                                </div>
                                <label class="flex items-center gap-2 cursor-pointer pt-2 border-t border-rose-200/80">
                                    <input type="checkbox" name="force" value="1" id="reschedule_force_checkbox" class="w-4 h-4 rounded text-rose-600 focus:ring-rose-500 cursor-pointer">
                                    <span class="text-[11px] font-bold text-rose-900">Tetap Jadwalkan (Override Kuota Paguyuban)</span>
                                </label>
                            </div>

                            <!-- Alasan Perubahan Jadwal -->
                            <div>
                                <label class="block font-bold text-slate-700 mb-1.5 text-xs">
                                    Alasan Perubahan Jadwal
                                </label>
                                <select name="reason" id="reschedule_reason_select" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-600 font-medium cursor-pointer mb-2 shadow-2xs">
                                    <option value="Permintaan Reschedule dari Wisatawan">Permintaan Reschedule dari Wisatawan</option>
                                    <option value="Bentrok Jadwal / Overbooking Sesi">Bentrok Jadwal / Overbooking Sesi</option>
                                    <option value="Kondisi Cuaca / Jalur Wisata Ditutup Sementara">Kondisi Cuaca / Jalur Wisata Ditutup Sementara</option>
                                    <option value="Penyesuaian Operasional Armada Loket">Penyesuaian Operasional Armada Loket</option>
                                    <option value="Lainnya">Alasan Lainnya</option>
                                </select>
                                <input type="text" name="admin_note" id="reschedule_admin_note" placeholder="Catatan tambahan (opsional, misal: 'Pindah atas permintaan tamu via WA')" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-white text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-600 shadow-2xs">
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 flex items-center justify-end gap-2.5">
                            <button type="button" onclick="closeRescheduleModal()" class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 text-slate-700 font-semibold text-xs transition-colors cursor-pointer shadow-2xs">
                                Batal
                            </button>
                            <button type="submit" id="btnSubmitReschedule" class="px-5 py-2.5 rounded-xl bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs shadow-xs hover:shadow transition-all cursor-pointer">
                                Simpan Jadwal Baru
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- JAVASCRIPT UNTUK MODAL DETAIL PEMESAN & MODAL PENUGASAN DRIVER -->
            <script>
                let currentDetailBookingRawData = null;

                // Salin teks ke clipboard helper
                function copyDetailText(elementId, btn) {
                    const el = document.getElementById(elementId);
                    if (!el) return;
                    const text = el.textContent || el.innerText;
                    if (!navigator.clipboard) {
                        return;
                    }
                    navigator.clipboard.writeText(text.trim()).then(() => {
                        const originalHtml = btn.innerHTML;
                        btn.innerHTML = '<svg class="w-3.5 h-3.5 text-emerald-600 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>';
                        btn.classList.add('text-emerald-700');
                        setTimeout(() => {
                            btn.innerHTML = originalHtml;
                            btn.classList.remove('text-emerald-700');
                        }, 1400);
                    }).catch(err => {
                        console.error('Failed to copy:', err);
                    });
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

                    // Identitas Pemesan
                    document.getElementById('detail_booking_code').textContent = data.booking_code;
                    document.getElementById('detail_customer_name').textContent = data.customer_name;
                    document.getElementById('detail_customer_phone').textContent = data.customer_phone;
                    document.getElementById('detail_customer_email').textContent = (data.customer_email && data.customer_email !== '-') ? data.customer_email : 'Tidak ada email';
                    document.getElementById('detail_created_at').textContent = 'Dipesan pada ' + (data.created_at || '-');

                    const initialEl = document.getElementById('detail_customer_initial');
                    if (initialEl) {
                        initialEl.textContent = (data.customer_name || 'W').trim().charAt(0).toUpperCase();
                    }

                    // Catatan Khusus Wisatawan
                    const notesContainer = document.getElementById('detail_notes_container');
                    if (notesContainer) {
                        if (data.customer_notes && data.customer_notes.trim() !== '' && data.customer_notes !== '-') {
                            notesContainer.classList.remove('hidden');
                            document.getElementById('detail_customer_notes').textContent = '"' + data.customer_notes + '"';
                        } else {
                            notesContainer.classList.add('hidden');
                        }
                    }

                    // Paket & Jadwal Pelaksanaan
                    document.getElementById('detail_package_name').textContent = data.package_name;
                    document.getElementById('detail_trip_date').textContent = data.trip_date;
                    document.getElementById('detail_slot_label').textContent = data.slot_label;
                    document.getElementById('detail_armada_pax').textContent = (data.jeep_quantity || 1) + ' Unit Jeep • ' + (data.passenger_count || 4) + ' Orang Penumpang';
                    document.getElementById('detail_jeep_name').textContent = data.jeep_name || 'Belum Ditugaskan';
                    document.getElementById('detail_driver_name').textContent = data.driver_name || 'Dialokasikan Otomatis';

                    // Logistik Kedatangan (Basecamp vs Pickup)
                    const isPickup = Boolean(data.is_pickup);
                    const pickupContainer = document.getElementById('detail_pickup_container');
                    const basecampContainer = document.getElementById('detail_basecamp_container');
                    const mapsBtn = document.getElementById('detail_pickup_maps_btn');

                    if (isPickup) {
                        if (pickupContainer) pickupContainer.classList.remove('hidden');
                        if (basecampContainer) basecampContainer.classList.add('hidden');

                        const zoneEl = document.getElementById('detail_pickup_zone');
                        if (zoneEl) zoneEl.textContent = data.pickup_zone || 'Layanan Antar-Jemput';

                        const addrEl = document.getElementById('detail_pickup_address');
                        if (addrEl) addrEl.textContent = (data.pickup_address && data.pickup_address !== '-') ? data.pickup_address : 'Alamat hotel / villa belum diisi';

                        if (mapsBtn) {
                            if (data.maps_link) {
                                mapsBtn.href = data.maps_link;
                                mapsBtn.classList.remove('hidden');
                            } else {
                                mapsBtn.classList.add('hidden');
                            }
                        }
                    } else {
                        if (pickupContainer) pickupContainer.classList.add('hidden');
                        if (basecampContainer) basecampContainer.classList.remove('hidden');
                    }

                    // Rincian Pembayaran & Status Kasir
                    const modeBadge = document.getElementById('detail_payment_mode_badge');
                    if (modeBadge) {
                        modeBadge.textContent = data.payment_mode || 'Pembayaran Penuh';
                    }
                    document.getElementById('detail_package_subtotal').textContent = 'Rp ' + (data.package_subtotal || '0');
                    document.getElementById('detail_pickup_fee').textContent = 'Rp ' + (data.pickup_fee || '0');
                    document.getElementById('detail_total_amount').textContent = 'Rp ' + (data.total_amount || '0');
                    document.getElementById('detail_paid_amount').textContent = 'Rp ' + (data.paid_amount || '0');
                    const channelEl = document.getElementById('detail_payment_channel');
                    if (channelEl) {
                        channelEl.textContent = data.payment_channel || 'Transfer Bank / Midtrans';
                    }

                    // Card Sisa Pelunasan di Loket
                    const remainingBox = document.getElementById('detail_remaining_box');
                    const remainingLabel = document.getElementById('detail_remaining_label');
                    const remainingAmount = document.getElementById('detail_remaining_amount');
                    const remainingSublabel = document.getElementById('detail_remaining_sublabel');

                    const cleanRemaining = String(data.remaining_amount || '').replace(/[^0-9]/g, '');
                    const isFullyPaid = cleanRemaining === '0' || cleanRemaining === '' || data.booking_status === 'FULL_PAID';

                    if (isFullyPaid) {
                        if (remainingBox) {
                            remainingBox.className = 'flex justify-between p-3.5 items-center bg-emerald-50 text-emerald-950 border-t border-emerald-100';
                        }
                        if (remainingLabel) {
                            remainingLabel.className = 'block font-bold text-xs text-emerald-900';
                            remainingLabel.textContent = 'Sisa Pelunasan di Loket';
                        }
                        if (remainingAmount) {
                            remainingAmount.className = 'text-base font-black text-emerald-700';
                            remainingAmount.textContent = 'Rp 0';
                        }
                        if (remainingSublabel) {
                            remainingSublabel.className = 'text-[11px] text-emerald-700 block mt-0.5';
                            remainingSublabel.textContent = 'Lunas 100% (Tidak ada tagihan loket)';
                        }
                    } else {
                        if (remainingBox) {
                            remainingBox.className = 'flex justify-between p-3.5 items-center bg-rose-50 text-rose-950 border-t border-rose-200';
                        }
                        if (remainingLabel) {
                            remainingLabel.className = 'block font-bold text-xs text-rose-900';
                            remainingLabel.textContent = 'Sisa Wajib Ditagih di Loket';
                        }
                        if (remainingAmount) {
                            remainingAmount.className = 'text-base font-black text-rose-700';
                            remainingAmount.textContent = 'Rp ' + data.remaining_amount;
                        }
                        if (remainingSublabel) {
                            remainingSublabel.className = 'text-[11px] text-rose-600 block mt-0.5 font-medium';
                            remainingSublabel.textContent = 'Wajib ditagih sebelum tour berangkat';
                        }
                    }

                    // Status Badge
                    const badgeContainer = document.getElementById('detail_status_badge');
                    if (badgeContainer) {
                        if (data.booking_status === 'FULL_PAID') {
                            badgeContainer.innerHTML = '<span class="px-2 py-0.5 rounded-md text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">Lunas 100%</span>';
                        } else if (data.booking_status === 'DP_PAID') {
                            badgeContainer.innerHTML = '<span class="px-2 py-0.5 rounded-md text-[11px] font-semibold bg-amber-50 text-amber-800 border border-amber-200">DP Terbayar</span>';
                        } else if (data.booking_status === 'COMPLETED') {
                            badgeContainer.innerHTML = '<span class="px-2 py-0.5 rounded-md text-[11px] font-semibold bg-blue-50 text-blue-700 border border-blue-200">Trip Selesai</span>';
                        } else if (data.booking_status === 'CANCELLED') {
                            badgeContainer.innerHTML = '<span class="px-2 py-0.5 rounded-md text-[11px] font-semibold bg-rose-50 text-rose-700 border border-rose-200">Dibatalkan</span>';
                        } else {
                            badgeContainer.innerHTML = '<span class="px-2 py-0.5 rounded-md text-[11px] font-semibold bg-slate-100 text-slate-700 border border-slate-200">Menunggu</span>';
                        }
                    }

                    // Tombol Hubungi WhatsApp
                    const waBtn = document.getElementById('btnWhatsappCustomer');
                    const quickWaBtn = document.getElementById('btnQuickWa');
                    let phone = (data.customer_phone || '').replace(/[^0-9]/g, '');
                    if (phone.startsWith('0')) {
                        phone = '62' + phone.substring(1);
                    }
                    if (phone) {
                        const waUrl = 'https://wa.me/' + phone + '?text=' + encodeURIComponent('Halo Kak ' + data.customer_name + ', kami dari Paguyuban Jeep Wukirsari Adventure mengenai reservasi Anda dengan kode booking: ' + data.booking_code);
                        if (waBtn) {
                            waBtn.href = waUrl;
                            waBtn.classList.remove('hidden');
                        }
                        if (quickWaBtn) {
                            quickWaBtn.href = waUrl;
                            quickWaBtn.classList.remove('hidden');
                        }
                    } else {
                        if (waBtn) waBtn.classList.add('hidden');
                        if (quickWaBtn) quickWaBtn.classList.add('hidden');
                    }

                    // Tombol Reschedule dari Detail (jika status cancelled, sembunyikan)
                    const rescheduleBtn = document.getElementById('btnDetailReschedule');
                    if (rescheduleBtn) {
                        if (data.booking_status === 'CANCELLED') {
                            rescheduleBtn.classList.add('hidden');
                        } else {
                            rescheduleBtn.classList.remove('hidden');
                        }
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
                            package_name: source.package_name || 'Paket Jeep',
                            jeep_quantity: source.jeep_quantity || 1,
                            passenger_count: source.passenger_count || 4,
                            trip_date: source.raw_trip_date || source.trip_date || '',
                            trip_date_formatted: source.trip_date || '',
                            slot_id: source.slot_id || '',
                            slot_time: source.slot_time || '',
                            slot_name: source.slot_name || '',
                            slot_label: source.slot_label || 'Sesi Jam'
                        };
                    } else {
                        return;
                    }

                    currentRescheduleData = data;

                    const form = document.getElementById('formRescheduleBooking');
                    form.action = "{{ url('admin/bookings') }}/" + data.id + "/reschedule";

                    // Update UI Card Ringkasan
                    const codeEl = document.getElementById('reschedule_booking_code');
                    if (codeEl) codeEl.textContent = '#' + (data.booking_code || '-');

                    const nameEl = document.getElementById('reschedule_customer_name');
                    if (nameEl) nameEl.textContent = data.customer_name || '-';

                    const pkgEl = document.getElementById('reschedule_package_name');
                    if (pkgEl) pkgEl.textContent = data.package_name || 'Paket Jeep';

                    const paxEl = document.getElementById('reschedule_pax_badge');
                    if (paxEl) paxEl.textContent = (data.jeep_quantity || 1) + ' Jeep (' + (data.passenger_count || 4) + ' Orang)';

                    const dateEl = document.getElementById('reschedule_current_date');
                    if (dateEl) dateEl.textContent = data.trip_date_formatted || data.trip_date || '-';

                    // Parse waktu & nama sesi yang bersih
                    let sTime = data.slot_time || '';
                    let sName = data.slot_name || '';
                    if (!sTime && data.slot_label) {
                        if (data.slot_label.includes('•')) {
                            const parts = data.slot_label.split('•');
                            sTime = parts[0].trim();
                            sName = parts[1] ? parts[1].trim() : '';
                        } else {
                            sTime = data.slot_label;
                        }
                    }
                    sTime = sTime.replace(/:00\b/g, '');
                    sName = sName.replace(/\(\d{2}:\d{2}\s*WIB\)/gi, '').trim();

                    const timeEl = document.getElementById('reschedule_current_time');
                    if (timeEl) timeEl.textContent = sTime || '-';

                    const slotNameEl = document.getElementById('reschedule_current_slot_name');
                    if (slotNameEl) slotNameEl.textContent = sName || '';

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
                                    opt.textContent = `${s.time_range} — ${s.slot_label} (Penuh: 0 Jeep)`;
                                } else if (s.remaining_jeeps < needed) {
                                    opt.textContent = `${s.time_range} — ${s.slot_label} (Sisa ${s.remaining_jeeps} Jeep - Kurang)`;
                                } else {
                                    opt.textContent = `${s.time_range} — ${s.slot_label} (Sisa ${s.remaining_jeeps} Jeep)`;
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
                        closeBookingDetailModal();
                        closeAssignModal();
                        closeRescheduleModal();
                    }
                });
            </script>
        </main>
</x-admin-layout>

