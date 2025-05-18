<x-card>
    <flux:heading size="xl">{{ __('jam.create_jam') }}</flux:heading>
    <flux:subheading>{{ __('jam.share_id') }}</flux:subheading>

    <div class="flex gap-2 flex-col mt-4">
        <flux:input value="{{ $jamId }}" readonly copyable />
        <flux:button wire:click="create">{{ __('jam.create') }}</flux:button>
    </div>
</x-card>
