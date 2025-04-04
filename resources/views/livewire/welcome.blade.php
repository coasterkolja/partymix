<x-card>
    <flux:heading size="xl">Spotify PartyMix</flux:heading>
    <flux:subheading>Tritt einem Jam bei oder erstelle einen neuen</flux:subheading>

    <form wire:submit="join" class="mt-4 flex gap-2">
        <flux:field class="grow">
            <flux:input wire:model="jamId" type="string" x-mask="9999-9999" placeholder="Jam ID" />
            <flux:error name="jamId" />
        </flux:field>
        <flux:button type="submit" variant="primary">Join</flux:button>
    </form>

    <flux:separator text="or" class="my-4" />

    <flux:button href="{{ route('jams.auth') }}" class="w-full">Create a Jam</flux:button>
</x-card>
