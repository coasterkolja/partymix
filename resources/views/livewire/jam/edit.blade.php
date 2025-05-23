<x-card>
    <div class="flex justify-between gap-2 items-center">
        <flux:heading size="xl">{{ __('jam.settings') }}</flux:heading>

        <div class="flex items-center gap-2">
            <flux:tooltip content="Zurück">
                <flux:button icon="arrow-left" variant="ghost" size="sm" href="{{ route('jams', $jam) }}"
                    wire:navigate />
            </flux:tooltip>
        </div>
    </div>

    <flux:subheading>{{ __('jam.settings_subheading') }}</flux:subheading>

    <div class="mt-4 flex flex-col gap-2">
        <flux:subheading>{{ __('jam.playlists') }}</flux:subheading>

        <form wire:submit.prevent="addPlaylist" class="flex gap-2">
            <flux:field class="grow">
                <flux:input wire:model="playlistUrl" type="string" :placeholder="__('jam.playlist_url')" />
                <flux:error name="playlistUrl" />
            </flux:field>
            <flux:button type="submit" icon="plus" />
        </form>

        <div class="flex flex-col gap-3 border-1 border-zinc-200 dark:border-zinc-600 rounded-lg p-2">
            @if ($jam->playlists->isEmpty())
                <div class="text-sm text-muted text-center">{{ __('jam.no_playlists_added') }}</div>
            @else
                @foreach ($jam->playlists as $playlist)
                    <div class="flex items-center justify-between" wire:key="{{ $playlist->id }}">
                        <div class="flex items-center gap-3">
                            <img src="{{ $playlist->image }}" class="rounded-md h-12 aspect-square" />
                            <div class="flex items-center">{{ $playlist->name }}</div>
                        </div>
                        <flux:button size="sm" variant="danger" wire:click="removePlaylist('{{ $playlist->id }}')"
                            icon="trash" />
                    </div>
                @endforeach
            @endif
        </div>

        <flux:description>Playlists, aus denen Songs automatisch gespielt werden (optional)</flux:description>
    </div>

    <div class="mt-4 flex flex-col gap-2">
        <flux:subheading>{{ __('jam.cooldown') }}</flux:subheading>

        <form wire:submit.prevent="setCooldown" class="flex gap-2">
            <flux:field class="grow">
                <flux:input wire:model="cooldown" type="number" placeholder="Cooldown (Minuten)" />
                <flux:error name="cooldown" />
            </flux:field>
            <flux:button type="submit" variant="primary" icon="check" />
        </form>

        <flux:description>Zeit bis ein Song, der gespielt wurde, erneut gespielt werden darf.
            Standard: 60 Minuten</flux:description>
    </div>

    {{-- <flux:input placeholder="Cooldown (Minuten)" type="number" value="{{ $jam->cooldown }}" wire:model.live="cooldown" /> --}}

    {{-- <flux:button wire:click="save" variant="primary" class="w-full mt-4">{{ __('jam.save') }}</flux:button> --}}
</x-card>
