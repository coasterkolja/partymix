<div>
    <x-card>
        <div class="flex justify-between gap-2 items-center">
            <flux:heading size="xl">{{ __('jam.edit_queue') }}</flux:heading>

            <flux:tooltip content="Zurück">
                <flux:button icon="arrow-left" variant="ghost" site="sm" href="{{ route('jams', $jam) }}" />
            </flux:tooltip>
        </div>

        <div class="flex mt-4 flex-col gap-3 border-1 border-zinc-200 dark:border-zinc-600 rounded-lg p-2 max-h-96 overflow-y-auto"
            {{ !$jam->queue->isEmpty() && config('partymix.queue.editable') ? 'x-sort' : '' }}>
            @if ($jam->queue->isEmpty())
                <div class="text-sm text-muted text-center">{{ __('jam.queue_empty') }}</div>
            @else
                @foreach ($jam->queue()->orderBy('queued_songs.created_at')->get() as $song)
                    <x-queue-item :song="$song" :editing="true" />
                @endforeach
            @endif
        </div>
    </x-card>

    <x-playing :jam="$jam" />
</div>
