<div>
    <x-card>
        <div class="flex justify-between gap-2 items-center">
            <flux:heading size="xl">{{ __('jam.queue') }}</flux:heading>
            <div class="flex items-center gap-2">
                <flux:modal.trigger name="qr-code">
                    <flux:tooltip content="QR Code anzeigen">
                        <flux:button icon="qr-code" variant="ghost" size="sm" />
                    </flux:tooltip>
                </flux:modal.trigger>

                <flux:tooltip content="Verlauf">
                    <flux:button href="{{ route('jams.history', $jam) }}" icon="clock" variant="ghost" size="sm"
                        wire:navigate />
                </flux:tooltip>

                @host($jam)
                    <flux:tooltip content="Settings">
                        <flux:button href="{{ route('jams.edit', $jam) }}" icon="cog-6-tooth" variant="ghost" size="sm"
                            wire:navigate />
                    </flux:tooltip>

                    <flux:tooltip content="Warteschlange bearbeiten">
                        <flux:button href="{{ route('jams.queue', $jam) }}" icon="queue-list" variant="ghost" size="sm"
                            wire:navigate />
                    </flux:tooltip>
                @endhost
            </div>
        </div>

        <div
            class="flex mt-4 flex-col gap-3 border-1 border-zinc-200 dark:border-zinc-600 rounded-lg p-2 max-h-96 overflow-y-auto">
            @if ($jam->queue->isEmpty())
                <div class="text-sm text-muted text-center">{{ __('jam.queue_empty') }}</div>
            @else
                @foreach ($jam->queue as $song)
                    <x-queue-item :song="$song" />
                @endforeach
            @endif
        </div>

        <flux:modal.trigger name="search-song">
            <flux:button class="w-full mt-4">{{ __('jam.wish') }}</flux:button>
        </flux:modal.trigger>

        <livewire:search-modal :$jam />
        <flux:modal name="qr-code">
            <flux:heading size="lg" class="mb-4">{{ __('jam.qr_code') }}</flux:heading>
            <img src="{{ asset('storage/qr-codes/' . $jam->id . '.svg') }}" alt="{{ __('jam.qr_code') }}" />
        </flux:modal>
    </x-card>

    <x-playing :jam="$jam" />
</div>
