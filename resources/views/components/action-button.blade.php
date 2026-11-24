@props([
    'type' => 'view', // 'view' | 'edit' | 'delete' | 'cancel' | 'print' | 'settle' | 'assign' | 'photo' | 'calendar' | 'toggle'
    'title' => '',
    'as' => 'button', // 'button' | 'a' | 'submit'
    'icon' => null,
])

@php
    $configs = [
        'view' => [
            'icon' => 'eye',
            'classes' => 'bg-sky-50 border-sky-200 text-sky-700 hover:bg-sky-100 hover:border-sky-300 hover:text-sky-900',
            'defaultTitle' => 'Lihat Detail',
        ],
        'edit' => [
            'icon' => 'pencil',
            'classes' => 'bg-amber-50 border-amber-200 text-amber-700 hover:bg-amber-100 hover:border-amber-300 hover:text-amber-900',
            'defaultTitle' => 'Ubah Data',
        ],
        'delete' => [
            'icon' => 'trash',
            'classes' => 'bg-rose-50 border-rose-200 text-rose-600 hover:bg-rose-100 hover:border-rose-300 hover:text-rose-800',
            'defaultTitle' => 'Hapus Data',
        ],
        'cancel' => [
            'icon' => 'x-mark',
            'classes' => 'bg-rose-50 border-rose-200 text-rose-600 hover:bg-rose-100 hover:border-rose-300 hover:text-rose-800',
            'defaultTitle' => 'Batalkan',
        ],
        'print' => [
            'icon' => 'printer',
            'classes' => 'bg-slate-100 border-slate-300 text-slate-700 hover:bg-slate-200 hover:border-slate-400 hover:text-slate-900',
            'defaultTitle' => 'Cetak',
        ],
        'settle' => [
            'icon' => 'cash',
            'classes' => 'bg-emerald-50 border-emerald-300 text-emerald-800 hover:bg-emerald-100 hover:border-emerald-400 hover:text-emerald-950',
            'defaultTitle' => 'Pelunasan Kasir Loket',
        ],
        'assign' => [
            'icon' => 'user',
            'classes' => 'bg-emerald-50 border-emerald-200 text-emerald-700 hover:bg-emerald-100 hover:border-emerald-300 hover:text-emerald-900',
            'defaultTitle' => 'Atur Supir / Armada',
        ],
        'photo' => [
            'icon' => 'photo',
            'classes' => 'bg-purple-50 border-purple-200 text-purple-700 hover:bg-purple-100 hover:border-purple-300 hover:text-purple-900',
            'defaultTitle' => 'Kelola Foto / Media',
        ],
        'calendar' => [
            'icon' => 'calendar',
            'classes' => 'bg-indigo-50 border-indigo-200 text-indigo-700 hover:bg-indigo-100 hover:border-indigo-300 hover:text-indigo-900',
            'defaultTitle' => 'Atur Jadwal',
        ],
        'toggle' => [
            'icon' => 'check',
            'classes' => 'bg-emerald-50 border-emerald-200 text-emerald-700 hover:bg-emerald-100 hover:border-emerald-300 hover:text-emerald-900',
            'defaultTitle' => 'Ubah Status',
        ],
    ];

    $config = $configs[$type] ?? $configs['view'];
    $iconName = $icon ?? $config['icon'];
    $tooltip = $title ?: $config['defaultTitle'];
    $baseClasses = 'p-1.5 rounded-xl border text-xs shadow-2xs transition-all cursor-pointer inline-flex items-center justify-center shrink-0 ' . $config['classes'];
@endphp

@if($as === 'a')
    <a title="{{ $tooltip }}" {{ $attributes->merge(['class' => $baseClasses]) }}>
        <x-icon :name="$iconName" class="w-4 h-4" />
    </a>
@elseif($as === 'submit')
    <button type="submit" title="{{ $tooltip }}" {{ $attributes->merge(['class' => $baseClasses]) }}>
        <x-icon :name="$iconName" class="w-4 h-4" />
    </button>
@else
    <button type="button" title="{{ $tooltip }}" {{ $attributes->merge(['class' => $baseClasses]) }}>
        <x-icon :name="$iconName" class="w-4 h-4" />
    </button>
@endif
