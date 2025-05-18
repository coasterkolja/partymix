@props([
    'song' => null,
    'editing' => false,
])

@php
    $draggingAllowed = $editing && config('partymix.queue.editable');
    $sortable = $draggingAllowed ? 'x-sort:item="' . $song->id . '"' : '';
@endphp

<div class="flex justify-between items-center" wire:key="{{ $song->id }}" {{ $sortable }}>
    <div class="flex items-center gap-3">
        <img src="{{ $song->image }}" class="rounded-md h-12 aspect-square" />
        <div class="flex flex-col gap-1">
            <span>{{ $song->name }}</span>
            <span class="text-sm text-muted">{{ $song->artist }}</span>
        </div>
    </div>

    @if($draggingAllowed)
        <div x-sort:handle class="cursor-grab">
            <flux:icon.bars-3 />
        </div>
    @endif

    @if ($editing)
        <flux:button wire:click="remove('{{ $song->id }}')" icon="x-mark" variant="ghost" size="sm" />
    @endif
</div>