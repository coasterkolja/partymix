<x-card>
    <flux:heading size="xl">Spotify PartyMix</flux:heading>
    <flux:subheading>Join a Jam or create a new one</flux:subheading>

    <form wire:submit="join" class="mt-4 flex gap-2">
        <flux:field class="grow">
            <flux:input wire:model="jamId" type="string" x-mask="9999-9999" placeholder="Jam ID" />
            <flux:error name="jamId" />
        </flux:field>
        <flux:button type="submit" variant="primary">Join</flux:button>
    </form>

    <flux:separator text="or" class="my-4" />

    <flux:button wire:click="create" class="w-full">Create a Jam</flux:button>
</x-card>
