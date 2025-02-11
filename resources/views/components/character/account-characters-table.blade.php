@props(['characters'])

<flux:table>
    <flux:columns>
        <flux:column>{{ __('Name') }}</flux:column>
        <flux:column>{{ __('Class') }}</flux:column>
        <flux:column>{{ __('Level') }}</flux:column>
        <flux:column>{{ __('Resets') }}</flux:column>
        <flux:column>{{ __('Guild') }}</flux:column>
    </flux:columns>

    <flux:rows>
        @foreach($characters as $character)
            <flux:row :key="$character->Name">
                <flux:cell>
                    <flux:link variant="ghost"
                               :href="route('character', ['name' => $character->Name])"
                               wire:navigate.hover>
                        {{ $character->Name }}
                    </flux:link>
                </flux:cell>

                <x-rankings.table.cells.character-class :character="$character"/>

                <flux:cell>{{ $character->cLevel }}</flux:cell>
                <flux:cell>{{ $character->ResetCount }}</flux:cell>

                <flux:cell>
                    <x-guild-identity :guild-member="$character->guildMember"/>
                </flux:cell>
            </flux:row>
        @endforeach
    </flux:rows>
</flux:table>
