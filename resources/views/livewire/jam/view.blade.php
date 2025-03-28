<div>
    <x-card>
        <flux:heading size="xl">Warteschlange</flux:heading>

        <div class="flex mt-4 flex-col gap-3 border-1 border-zinc-200 dark:border-zinc-600 rounded-lg p-2">
            @if ($jam->queue->isEmpty())
                <div class="text-sm text-muted text-center">Warteschlange ist leer</div>
            @else
                @foreach ($jam->queue()->orderBy('queued_songs.created_at')->get() as $song)
                    <div class="flex items-center gap-3" wire:key="{{ $song->id }}">
                        <img src="{{ $song->image }}" class="rounded-md h-12 aspect-square" />
                        <div class="flex flex-col gap-1">
                            <span>{{ $song->name }}</span>
                            <span class="text-sm text-muted">{{ $song->artist }}</span>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <flux:modal.trigger name="search-song">
            <flux:button class="w-full mt-4">Song wünschen</flux:button>
        </flux:modal.trigger>

        <livewire:search-modal :$jam />
    </x-card>
    @if ($jam->currentSong)
        <x-card class="mt-4">
            <div class="flex items-center gap-3">
                <img src="{{ $jam->currentSong->image }}" class="rounded-md h-12 aspect-square" />
                <div class="flex flex-col gap-1">
                    <span>{{ $jam->currentSong->name }}</span>
                    <span class="text-sm text-muted">{{ $jam->currentSong->artist }}</span>
                </div>
            </div>
        </x-card>
    @endif
</div>
