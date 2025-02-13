@props(['guild'])

<div class="space-y-8">
    <div>
        <flux:heading size="lg" class="mb-2">
            {{ __('Guild Members') }}
        </flux:heading>
        <flux:separator/>
    </div>

    <flux:table>
        <flux:columns>
            <flux:column>#</flux:column>
            <flux:column>{{ __('Name') }}</flux:column>
            <flux:column>{{ __('Class') }}</flux:column>
            <flux:column>{{ __('Level') }}</flux:column>
            <flux:column>{{ __('Resets') }}</flux:column>
            <flux:column>{{ __('Position') }}</flux:column>
        </flux:columns>

        <flux:rows>
            @foreach($guild->members as $member)
                <flux:row :key="$member->Name">
                    <flux:cell>{{ $loop->iteration }}.</flux:cell>
                    <flux:cell>
                        <flux:link variant="ghost"
                                   :href="route('character', ['name' => $member->Name])"
                                   wire:navigate.hover>
                            {{ $member->Name }}
                        </flux:link>
                    </flux:cell>

                    @if($member->character)
                        <x-rankings.table.cells.character-class :character="$member->character"/>
                        <flux:cell>{{ $member->character->cLevel }}</flux:cell>
                        <flux:cell>{{ $member->character->ResetCount }}</flux:cell>
                    @else
                        <flux:cell>
                            <x-empty-cell/>
                        </flux:cell>
                        <flux:cell>
                            <x-empty-cell/>
                        </flux:cell>
                        <flux:cell>
                            <x-empty-cell/>
                        </flux:cell>
                    @endif

                    <flux:cell>
                        <x-guild-member :guild-member="$member"/>
                    </flux:cell>
                </flux:row>
            @endforeach
        </flux:rows>
    </flux:table>
</div>
