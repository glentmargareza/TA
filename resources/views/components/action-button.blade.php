@props([
    'type' => 'view', // 'view' | 'edit' | 'delete' | 'print' | 'settle'
    'title' => '',
])

@php
    $configs = [
        'view' => [
            'icon' => 'eye',
            'classes' => 'border-slate-200 bg-white hover:bg-blue-50 hover:border-blue-300 text-slate-600 hover:text-blue-700',
            'defaultTitle' => 'Lihat Detail',
        ],
        'edit' => [
            'icon' => 'pencil',
            'classes' => 'border-slate-200 bg-white hover:bg-emerald-50 hover:border-emerald-300 text-slate-600 hover:text-emerald-700',
            'defaultTitle' => 'Ubah Data',
        ],
        'delete' => [
            'icon' => 'x-mark',
            'classes' => 'border-slate-200 bg-white hover:bg-rose-50 hover:border-rose-300 text-slate-400 hover:text-rose-600',
            'defaultTitle' => 'Batalkan / Hapus',
        ],
        'print' => [
            'icon' => 'printer',
            'classes' => 'border-slate-200 bg-white hover:bg-slate-100 hover:border-slate-300 text-slate-600 hover:text-slate-900',
            'defaultTitle' => 'Cetak',
        ],
        'settle' => [
            'icon' => 'cash',
            'classes' => 'border-emerald-200 bg-emerald-50 hover:bg-emerald-100 hover:border-emerald-400 text-emerald-800',
            'defaultTitle' => 'Pelunasan Loket',
        ],
    ];

    $config = $configs[$type] ?? $configs['view'];
@endphp

<button type="button" 
        title="{{ $title ?: $config['defaultTitle'] }}" 
        {{ $attributes->merge(['class' => 'p-1.5 rounded-lg border text-xs shadow-2xs transition-all cursor-pointer ' . $config['classes']]) }}>
    <x-icon :name="$config['icon']" class="w-4 h-4" />
</button>
