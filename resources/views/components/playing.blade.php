@props([
    'jam' => null,
])

@if ($jam->currentSong)
    <x-card class="mt-4 flex justify-between items-center">
        <div class="flex items-center gap-3">
            <img src="{{ $jam->currentSong->image }}" class="rounded-md h-12 aspect-square" />
            <div class="flex flex-col gap-1">
                <span>{{ str($jam->currentSong->name)->limit(35) }}</span>
                <span class="text-sm text-muted">{{ $jam->currentSong->artist }}</span>
            </div>
        </div>

        @host($jam)
            <flux:button wire:click="skip" icon="skip-forward" class="aspect-square" />
        @endhost
    </x-card>
@endif
