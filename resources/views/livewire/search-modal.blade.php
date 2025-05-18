<flux:modal name="search-song">
    <flux:heading size="xl">{{ __('jam.search_song') }}</flux:heading>
    <flux:subheading>{{ __('jam.queue_cooldown', ['time' => $jam->cooldownTimeHuman()]) }}</flux:subheading>

    <flux:separator class="my-4" />

    <flux:input wire:model.live.debounce.300ms="query" icon="magnifying-glass" :placeholder="__('jam.search_placeholder')" clearable />

    <div class="flex flex-col gap-3 mt-4 border-1 border-zinc-200 dark:border-zinc-600 rounded-lg p-2">
        @if ($results->isEmpty())
            <div class="text-sm text-muted text-center">{{ __('jam.no_results') }}</div>
        @else
            @foreach ($results as $song)
                @php
                    $addHandler = !$song->isOnCooldown ? 'wire:click="addToQueue(`'.$song->id.'`)"' : '';
                @endphp
                <div @class(['flex', 'items-cenet', 'gap-3', 'opacity-50' => $song->isOnCooldown, 'cursor-pointer' => !$song->isOnCooldown, 'select-none']) wire:key="{{ $song->id }}" {!! $addHandler !!}>
                    <img src="{{ $song->image }}" class="rounded-md h-12 aspect-square" />
                    <div class="flex flex-col gap-1 grow">
                        <span>{{ $song->name }}</span>
                        <span class="text-sm text-muted-foreground">{{ $song->artist }}</span>
                    </div>
                    @if ($song->isOnCooldown)
                        <x-cooldown :progress="$song->cooldown" />
                    @endif
                </div>
            @endforeach
        @endif
    </div>
</flux:modal>
