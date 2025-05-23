<div>
    <x-card>
        <div class="flex justify-between gap-2 items-center">
            <flux:heading size="xl">{{ __('jam.history') }}</flux:heading>
            <div class="flex items-center gap-2">
                <flux:tooltip content="Zurück">
                    <flux:button href="{{ route('jams', $jam) }}" icon="arrow-left" variant="ghost" size="sm"
                        wire:navigate />
                </flux:tooltip>
            </div>
        </div>

        <div
            class="flex mt-4 flex-col gap-3 border-1 border-zinc-200 dark:border-zinc-600 rounded-lg p-2 max-h-96 overflow-y-auto">
            @if ($jam->history->isEmpty())
                <div class="text-sm text-muted text-center">{{ __('jam.history_empty') }}</div>
            @else
                @foreach ($jam->history as $song)
                    <x-queue-item :song="$song" />
                @endforeach
            @endif
        </div>
    </x-card>

    <x-playing :jam="$jam" />
</div>
