<x-admin-layout title="Notifikasi & Aktivitas Operasional | Admin Jeep Wukirsari">

    <!-- Header Bersih & Ringkas -->
    <x-admin.header title="Notifikasi & Aktivitas" subtitle="Pemberitahuan transaksi pembayaran, alokasi armada, dan mutasi driver secara real-time.">
        <x-slot:actions>
            @if($unreadNotificationsCount > 0)
            <form action="{{ route('admin.notifications.markAllRead') }}" method="POST">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold shadow-2xs transition-all cursor-pointer active:scale-95">
                    <x-icon name="check" class="w-4 h-4 text-emerald-400" />
                    <span>Tandai Semua Dibaca ({{ $unreadNotificationsCount }})</span>
                </button>
            </form>
            @endif
        </x-slot:actions>
    </x-admin.header>

    <main class="p-4 sm:p-8 space-y-4 sm:space-y-5 flex-1 max-w-5xl w-full mx-auto">

        @php
            \Carbon\Carbon::setLocale('id');
            $totalAll = $totalActivities ?? ($activities->total() ?? 0);
            $counts = $typeCounts ?? [];
            $currentType = $type ?? 'ALL';
        @endphp

        <!-- FILTER KATEGORI (SIMPEL & BISA DIGESER DI HP) -->
        <div class="bg-slate-200/70 p-1 rounded-2xl flex items-center gap-1 overflow-x-auto no-scrollbar scroll-smooth">
            <!-- Semua -->
            <a href="{{ route('admin.notifications', ['type' => 'ALL']) }}"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold transition-all shrink-0 {{ $currentType === 'ALL' ? 'bg-white text-slate-900 shadow-2xs font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-white/50' }}">
                <span>Semua</span>
                <span class="text-[11px] {{ $currentType === 'ALL' ? 'text-emerald-700 font-bold' : 'text-slate-400' }}">{{ $totalAll }}</span>
            </a>

            <!-- Pembayaran Masuk -->
            <a href="{{ route('admin.notifications', ['type' => 'PAYMENT_SUCCESS']) }}"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold transition-all shrink-0 {{ $currentType === 'PAYMENT_SUCCESS' ? 'bg-white text-slate-900 shadow-2xs font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-white/50' }}">
                <span>Pembayaran</span>
                <span class="text-[11px] {{ $currentType === 'PAYMENT_SUCCESS' ? 'text-emerald-700 font-bold' : 'text-slate-400' }}">{{ $counts['PAYMENT_SUCCESS'] ?? 0 }}</span>
            </a>

            <!-- Penugasan Armada -->
            <a href="{{ route('admin.notifications', ['type' => 'AUTO_ASSIGN']) }}"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold transition-all shrink-0 {{ $currentType === 'AUTO_ASSIGN' ? 'bg-white text-slate-900 shadow-2xs font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-white/50' }}">
                <span>Penugasan Armada</span>
                <span class="text-[11px] {{ $currentType === 'AUTO_ASSIGN' ? 'text-blue-700 font-bold' : 'text-slate-400' }}">{{ $counts['AUTO_ASSIGN'] ?? 0 }}</span>
            </a>

            <!-- WhatsApp E-Tiket -->
            <a href="{{ route('admin.notifications', ['type' => 'NOTIFICATION_WA']) }}"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold transition-all shrink-0 {{ $currentType === 'NOTIFICATION_WA' ? 'bg-white text-slate-900 shadow-2xs font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-white/50' }}">
                <span>WhatsApp Tiket</span>
                <span class="text-[11px] {{ $currentType === 'NOTIFICATION_WA' ? 'text-teal-700 font-bold' : 'text-slate-400' }}">{{ $counts['NOTIFICATION_WA'] ?? 0 }}</span>
            </a>

            <!-- Aktivitas Admin -->
            <a href="{{ route('admin.notifications', ['type' => 'ADMIN_ACTION']) }}"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold transition-all shrink-0 {{ $currentType === 'ADMIN_ACTION' ? 'bg-white text-slate-900 shadow-2xs font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-white/50' }}">
                <span>Aktivitas Admin</span>
                <span class="text-[11px] {{ $currentType === 'ADMIN_ACTION' ? 'text-amber-700 font-bold' : 'text-slate-400' }}">{{ $counts['ADMIN_ACTION'] ?? 0 }}</span>
            </a>

            <!-- Pesanan Baru -->
            <a href="{{ route('admin.notifications', ['type' => 'BOOKING_NEW']) }}"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold transition-all shrink-0 {{ $currentType === 'BOOKING_NEW' ? 'bg-white text-slate-900 shadow-2xs font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-white/50' }}">
                <span>Pesanan Baru</span>
                <span class="text-[11px] {{ $currentType === 'BOOKING_NEW' ? 'text-indigo-700 font-bold' : 'text-slate-400' }}">{{ $counts['BOOKING_NEW'] ?? 0 }}</span>
            </a>

            <!-- Pembatalan -->
            <a href="{{ route('admin.notifications', ['type' => 'BOOKING_CANCELLED']) }}"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold transition-all shrink-0 {{ $currentType === 'BOOKING_CANCELLED' ? 'bg-white text-slate-900 shadow-2xs font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-white/50' }}">
                <span>Pembatalan</span>
                <span class="text-[11px] {{ $currentType === 'BOOKING_CANCELLED' ? 'text-rose-700 font-bold' : 'text-slate-400' }}">{{ $counts['BOOKING_CANCELLED'] ?? 0 }}</span>
            </a>

            <!-- Jadwal Penuh -->
            <a href="{{ route('admin.notifications', ['type' => 'SCHEDULE_FULL']) }}"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold transition-all shrink-0 {{ $currentType === 'SCHEDULE_FULL' ? 'bg-white text-slate-900 shadow-2xs font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-white/50' }}">
                <span>Jadwal Penuh</span>
                <span class="text-[11px] {{ $currentType === 'SCHEDULE_FULL' ? 'text-orange-700 font-bold' : 'text-slate-400' }}">{{ $counts['SCHEDULE_FULL'] ?? 0 }}</span>
            </a>
        </div>

        <!-- DAFTAR NOTIFIKASI GAYA PESAN LUGAS (SANGAT MUDAH DIBACA) -->
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs overflow-hidden">

            @php
                $groupedActivities = $activities->getCollection()->groupBy(function($item) {
                    return $item->created_at->format('Y-m-d');
                });
            @endphp

            @forelse($groupedActivities as $dateString => $itemsOnDate)
                @php
                    $carbonDate = \Carbon\Carbon::parse($dateString);
                    if ($carbonDate->isToday()) {
                        $labelDate = 'Hari Ini (' . $carbonDate->isoFormat('D MMMM Y') . ')';
                    } elseif ($carbonDate->isYesterday()) {
                        $labelDate = 'Kemarin (' . $carbonDate->isoFormat('D MMMM Y') . ')';
                    } else {
                        $labelDate = $carbonDate->isoFormat('dddd, D MMMM Y');
                    }
                @endphp

                <!-- Header Hari -->
                <div class="px-5 py-2.5 bg-slate-50 border-b border-t first:border-t-0 border-slate-100 flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-700">{{ $labelDate }}</span>
                    <span class="text-[11px] text-slate-400">{{ count($itemsOnDate) }} aktivitas</span>
                </div>

                <div class="divide-y divide-slate-100">
                    @foreach($itemsOnDate as $act)
                        @php
                            $typeIcons = [
                                'PAYMENT_SUCCESS'   => ['bg' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'badge' => 'Pembayaran Masuk', 'badgeBg' => 'bg-emerald-50 text-emerald-800 border-emerald-200', 'icon' => 'check'],
                                'AUTO_ASSIGN'       => ['bg' => 'bg-blue-50 text-blue-700 border-blue-200', 'badge' => 'Armada Ditugaskan', 'badgeBg' => 'bg-blue-50 text-blue-800 border-blue-200', 'icon' => 'car'],
                                'NOTIFICATION_WA'   => ['bg' => 'bg-teal-50 text-teal-700 border-teal-200', 'badge' => 'WhatsApp Terkirim', 'badgeBg' => 'bg-teal-50 text-teal-800 border-teal-200', 'icon' => 'whatsapp'],
                                'ADMIN_ACTION'      => ['bg' => 'bg-amber-50 text-amber-700 border-amber-200', 'badge' => 'Aktivitas Admin', 'badgeBg' => 'bg-amber-50 text-amber-800 border-amber-200', 'icon' => 'user'],
                                'BOOKING_NEW'       => ['bg' => 'bg-indigo-50 text-indigo-700 border-indigo-200', 'badge' => 'Pesanan Baru', 'badgeBg' => 'bg-indigo-50 text-indigo-800 border-indigo-200', 'icon' => 'calendar'],
                                'BOOKING_CANCELLED' => ['bg' => 'bg-rose-50 text-rose-700 border-rose-200', 'badge' => 'Dibatalkan', 'badgeBg' => 'bg-rose-50 text-rose-800 border-rose-200', 'icon' => 'trash'],
                                'SCHEDULE_FULL'     => ['bg' => 'bg-orange-50 text-orange-700 border-orange-200', 'badge' => 'Sesi Penuh', 'badgeBg' => 'bg-orange-50 text-orange-800 border-orange-200', 'icon' => 'clock'],
                            ];
                            $style = $typeIcons[$act->action_type] ?? ['bg' => 'bg-slate-100 text-slate-700 border-slate-200', 'badge' => $act->action_type, 'badgeBg' => 'bg-slate-100 text-slate-700 border-slate-200', 'icon' => 'bell'];
                        @endphp

                        <div class="p-4 sm:p-5 flex items-start gap-3.5 hover:bg-slate-50/70 transition-colors {{ !$act->is_read ? 'bg-emerald-50/20' : '' }}">
                            <div class="w-9 h-9 rounded-xl {{ $style['bg'] }} border flex items-center justify-center shrink-0 shadow-2xs mt-0.5">
                                <x-icon :name="$style['icon']" class="w-4 h-4" />
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center justify-between gap-2 mb-1">
                                    <div class="flex items-center gap-2">
                                        <span class="text-[11px] font-bold px-2 py-0.5 rounded-md border {{ $style['badgeBg'] }}">
                                            {{ $style['badge'] }}
                                        </span>
                                        @if(!$act->is_read)
                                        <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                        @endif
                                    </div>
                                    <span class="text-[11px] text-slate-400 font-mono">
                                        {{ $act->created_at->format('H:i') }} WIB ({{ $act->created_at->diffForHumans() }})
                                    </span>
                                </div>

                                <p class="text-xs text-slate-700 leading-relaxed font-normal">
                                    @if($act->action_type === 'AUTO_ASSIGN')
                                        @php
                                            $parts = explode('Booking #', $act->message);
                                            $driverPart = $parts[0] ?? '';
                                            $code = isset($parts[1]) ? explode(' ', $parts[1])[0] : null;
                                            $codeLink = $code ? route('admin.bookings', ['search' => $code]) : '#';
                                        @endphp
                                        {{ $driverPart }}
                                        @if($code)
                                            Booking <a href="{{ $codeLink }}" class="font-mono font-bold text-slate-900 hover:text-emerald-700 hover:underline">#{{ $code }}</a>.
                                        @endif

                                    @elseif($act->action_type === 'PAYMENT_SUCCESS')
                                        @php
                                            preg_match('/#([A-Z0-9\-]+)/', $act->message, $matches);
                                            $code = $matches[1] ?? null;
                                            $codeLink = $code ? route('admin.bookings', ['search' => $code]) : '#';
                                        @endphp
                                        {{ $act->message }}
                                        @if($code)
                                            <a href="{{ $codeLink }}" class="text-[11px] font-bold text-emerald-800 hover:underline inline-flex items-center gap-1 ml-1.5">
                                                <span>Buka Pesanan</span>
                                                <x-icon name="arrow-right" class="w-3 h-3" />
                                            </a>
                                        @endif

                                    @elseif($act->action_type === 'BOOKING_NEW')
                                        @php
                                            preg_match('/#([A-Z0-9\-]+)/', $act->message, $matches);
                                            $code = $matches[1] ?? null;
                                            $codeLink = $code ? route('admin.bookings', ['search' => $code]) : '#';
                                        @endphp
                                        {{ $act->message }}
                                        @if($code)
                                            <a href="{{ $codeLink }}" class="text-[11px] font-bold text-indigo-700 hover:underline inline-flex items-center gap-1 ml-1.5">
                                                <span>Kelola Pesanan</span>
                                                <x-icon name="arrow-right" class="w-3 h-3" />
                                            </a>
                                        @endif

                                    @else
                                        {{ $act->message }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>

            @empty
                <x-empty-state 
                    title="Tidak Ada Notifikasi pada Kategori Ini" 
                    description="Belum ada riwayat aktivitas terbaru yang sesuai dengan filter yang dipilih." 
                    icon="bell">
                    @if($currentType !== 'ALL')
                    <x-slot:action>
                        <a href="{{ route('admin.notifications', ['type' => 'ALL']) }}" class="text-xs font-semibold text-emerald-700 hover:underline">
                            Kembali ke Semua Aktivitas
                        </a>
                    </x-slot:action>
                    @endif
                </x-empty-state>
            @endforelse

            @if($activities->hasPages())
            <div class="p-4 border-t border-slate-100 flex justify-center bg-slate-50/50">
                {{ $activities->links() }}
            </div>
            @endif
        </div>

    </main>

</x-admin-layout>
