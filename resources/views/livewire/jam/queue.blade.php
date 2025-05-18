<div>
    <x-card>
        <div class="flex justify-between gap-2 items-center">
            <flux:heading size="xl">{{ __('jam.edit_queue') }}</flux:heading>
            <flux:button icon="arrow-left" variant="ghost" site="sm" href="{{ route('jams', $jam) }}" />
        </div>

        <div class="flex mt-4 flex-col gap-3 border-1 border-zinc-200 dark:border-zinc-600 rounded-lg p-2" {{ !$jam->queue->isEmpty() && config('partymix.queue.editable') ? 'x-sort' : '' }}>
            @if ($jam->queue->isEmpty())
                <div class="text-sm text-muted text-center">{{ __('jam.queue_empty') }}</div>
            @else
                @foreach ($jam->queue()->orderBy('queued_songs.created_at')->get() as $song)
                    <x-queue-item :song="$song" :editing="true" />
                @endforeach
            @endif
        </div>
    </x-card>

    @if ($jam->currentSong)
        <x-card class="mt-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <img src="{{ $jam->currentSong->image }}" class="rounded-md h-12 aspect-square" />
                <div class="flex flex-col gap-1">
                    <span>{{ $jam->currentSong->name }}</span>
                    <span class="text-sm text-muted">{{ $jam->currentSong->artist }}</span>
                </div>
            </div>

            @host($jam)
                <flux:button wire:click="skip" icon="skip-forward" class="aspect-square" />
            @endhost
        </x-card>
    @endif
</div>
