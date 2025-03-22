<x-card>
    <flux:heading size="xl">Einstellungen</flux:heading>
    <flux:subheading>Diese Einstellungen können jederzeit geändert werden</flux:subheading>

    <div class="mt-4 flex flex-col gap-2">
        <form wire:submit.prevent="addPlaylist" class="flex gap-2">
            <flux:field class="grow">
                <flux:input wire:model="playlistUrl" type="string" placeholder="Playlist Link" />
                <flux:error name="playlistUrl" />
            </flux:field>
            <flux:button type="submit" icon="plus" />
        </form>

        <div class="flex flex-col gap-3 border-1 border-zinc-200 dark:border-zinc-600 rounded-lg p-2">
            @if ($jam->playlists->isEmpty())
                <div class="text-sm text-muted text-center">Keine Playlists hinzugefügt</div>
            @else
                @foreach ($jam->playlists as $playlist)
                    <div class="flex items-center justify-between" wire:key="{{ $playlist->id }}">
                        <div class="flex items-center gap-3">
                            <img src="{{ $playlist->image }}" class="rounded-md h-12 aspect-square" />
                            <div class="flex items-center">{{ $playlist->name }}</div>
                        </div>
                        <flux:button size="sm" variant="danger" wire:click="removePlaylist('{{ $playlist->id }}')" icon="trash" />
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    <flux:button wire:click="save" variant="primary" class="w-full mt-4">Speichern</flux:button>
</x-card>
