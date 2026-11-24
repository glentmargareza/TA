@props([
    'action' => '',
    'method' => 'GET',
    'resetUrl' => null,
    'hasReset' => false,
    'grid' => true,
    'gridCols' => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-4',
    'actionCols' => 'flex items-end gap-2',
    'submitLabel' => 'Filter',
    'submitIcon' => null,
    'liveSearch' => true,
    'tableTarget' => null,
    'paginationTarget' => null,
])

{{-- Alias universal untuk <x-admin.filter-card /> --}}
<x-admin.filter-card 
    :action="$action"
    :method="$method"
    :resetUrl="$resetUrl"
    :hasReset="$hasReset"
    :grid="$grid"
    :gridCols="$gridCols"
    :actionCols="$actionCols"
    :submitLabel="$submitLabel"
    :submitIcon="$submitIcon"
    :liveSearch="$liveSearch"
    :tableTarget="$tableTarget"
    :paginationTarget="$paginationTarget"
    {{ $attributes }}>
    {{ $slot }}
    @if(isset($actions))
        <x-slot:actions>
            {{ $actions }}
        </x-slot:actions>
    @endif
</x-admin.filter-card>
