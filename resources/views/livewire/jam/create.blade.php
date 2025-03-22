<x-card>
    <flux:heading size="xl">Jam erstellen</flux:heading>
    <flux:subheading>Teile die Jam ID mit deinen Freunden</flux:subheading>

    <div class="flex gap-2 flex-col mt-4">
        <flux:input value="{{ $jamId }}" readonly copyable />
        <flux:button wire:click="create">Erstellen</flux:button>
    </div>
</x-card>
