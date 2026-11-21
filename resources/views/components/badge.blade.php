@props([
    'status' => 'PENDING',
    'label' => null,
    'dot' => false,
])

@php
    $styles = [
        'FULL_PAID'             => ['bg' => 'bg-emerald-50 text-emerald-800 border-emerald-200', 'text' => 'Lunas (100%)', 'dotColor' => 'bg-emerald-500'],
        'DP_PAID'               => ['bg' => 'bg-sky-50 text-sky-800 border-sky-200', 'text' => 'DP Terbayar (30%)', 'dotColor' => 'bg-sky-500'],
        'PENDING'               => ['bg' => 'bg-amber-50 text-amber-800 border-amber-200', 'text' => 'Menunggu Bayar', 'dotColor' => 'bg-amber-500'],
        'WAITING_CONFIRMATION'  => ['bg' => 'bg-amber-100 text-amber-900 border-amber-300', 'text' => 'Menunggu Verifikasi', 'dotColor' => 'bg-amber-600'],
        'COMPLETED'             => ['bg' => 'bg-purple-50 text-purple-800 border-purple-200', 'text' => 'Selesai', 'dotColor' => 'bg-purple-500'],
        'CANCELLED'             => ['bg' => 'bg-rose-50 text-rose-800 border-rose-200', 'text' => 'Dibatalkan', 'dotColor' => 'bg-rose-500'],
        'READY'                 => ['bg' => 'bg-emerald-50 text-emerald-800 border-emerald-200', 'text' => 'Siap Jalan', 'dotColor' => 'bg-emerald-500'],
        'ON_TRIP'               => ['bg' => 'bg-blue-50 text-blue-800 border-blue-200', 'text' => 'Sedang Bertugas', 'dotColor' => 'bg-blue-500'],
        'MAINTENANCE'           => ['bg' => 'bg-rose-50 text-rose-800 border-rose-200', 'text' => 'Perbaikan / Rusak', 'dotColor' => 'bg-rose-500'],
        'ACTIVE'                => ['bg' => 'bg-emerald-50 text-emerald-800 border-emerald-200', 'text' => 'Aktif', 'dotColor' => 'bg-emerald-500'],
        'INACTIVE'              => ['bg' => 'bg-slate-100 text-slate-600 border-slate-200', 'text' => 'Non-Aktif', 'dotColor' => 'bg-slate-400'],
    ];

    $cfg = $styles[$status] ?? ['bg' => 'bg-slate-100 text-slate-700 border-slate-200', 'text' => $status, 'dotColor' => 'bg-slate-400'];
    $displayText = $label ?? $cfg['text'];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold border ' . $cfg['bg']]) }}>
    @if($dot)
        <span class="w-1.5 h-1.5 rounded-full {{ $cfg['dotColor'] }}"></span>
    @endif
    <span>{{ $displayText }}</span>
</span>
