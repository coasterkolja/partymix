<x-card>
    <flux:heading size="xl">{{ __('jam.welcome_title') }}</flux:heading>
    <flux:subheading>{{ __('jam.welcome') }}</flux:subheading>

    <form wire:submit="join" class="mt-4 flex gap-2">
        <flux:field class="grow">
            <flux:input wire:model="jamId" type="string" x-mask="9999-9999" placeholder="Jam ID" />
            <flux:error name="jamId" />
        </flux:field>
        <flux:button type="submit" variant="primary">{{ __('jam.join') }}</flux:button>
    </form>

    <flux:separator :text="__('jam.or')" class="my-4" />

    <flux:button href="{{ route('jams.auth') }}" class="w-full">{{ __('jam.create_jam') }}</flux:button>
</x-card>
