<flux:modal name="search-song">
    <flux:heading size="xl">Song suchen</flux:heading>
    <flux:subheading>Füge einen Song in die Warteschlange hinzu. Songs die bereits gespielt wurden sind nach {{ $jam->cooldownTimeHuman() }} wieder verfügbar.</flux:subheading>
    
    <flux:separator class="my-4" />
    
    <flux:input wire:model.live.debounce.300ms="query" icon="magnifying-glass" placeholder="Suchen..." clearable />
    
    <div class="flex flex-col gap-3 mt-4 border-1 border-zinc-200 dark:border-zinc-600 rounded-lg p-2">
        @if ($results->isEmpty())
            <div class="text-sm text-muted text-center">Noch keine Ergebnisse</div>
        @else
            @foreach ($results as $song)
                <div class="flex items-center gap-3" wire:key="{{ $song->id }}" wire:click="addToQueue('{{ $song->id }}')">
                    <img src="{{ $song->image }}" class="rounded-md h-12 aspect-square" />
                    <div class="flex flex-col gap-1">
                        <span>{{ $song->name }}</span>
                        <span class="text-sm text-muted-foreground">{{ $song->artist }}</span>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</flux:modal>