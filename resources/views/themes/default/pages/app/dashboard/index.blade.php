<div class="space-y-6">
    <livewire:pages.app.dashboard.card/>

    <flux:table>
        <flux:columns>
            <flux:column>{{ __('Character') }}</flux:column>
            <flux:column>{{ __('Class') }}</flux:column>
            <flux:column>{{ __('Guild') }}</flux:column>
            <flux:column>{{ __('Kills') }}</flux:column>
            <flux:column>{{ __('Level') }}</flux:column>
            <flux:column sortable :sorted="$sortBy === 'ResetCount'" :direction="$sortDirection"
                         wire:click="sort('ResetCount')">
                {{ __('Resets') }}
            </flux:column>
        </flux:columns>

        <flux:rows>
            @forelse ($this->characters as $character)
                <livewire:pages.app.dashboard.character-row :$character wire:key="{{ $character->Name }}"/>
            @empty
                <flux:row>
                    <flux:cell colspan="6">
                        {{ __('No characters found.') }}
                    </flux:cell>
                </flux:row>
            @endforelse
        </flux:rows>
    </flux:table>
</div>
