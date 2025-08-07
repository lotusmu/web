<div class="space-y-6">
    <header>
        <flux:heading size="xl">
            {{ __('Event Entries') }}
        </flux:heading>

        <x-flux::subheading>
            {{ __('Review the number of times you\'ve registered for events with attendance limitations.') }}
        </x-flux::subheading>
    </header>

    @if ($this->characters->isNotEmpty())
        <div class="flex max-sm:flex-col items-start gap-8 w-full">
            <flux:card class="w-full space-y-6">
                <flux:heading size="lg">
                    {{ __('Blood Castle') }}
                </flux:heading>
                <flux:table>
                    <flux:rows>
                        @foreach ($this->characters as $character)
                            <flux:row :key="$character->Name">
                                <flux:cell class="flex items-center gap-3">
                                    <flux:avatar size="xs" src="{{ asset($character->Class->getImagePath()) }}"/>
                                    <span>{{ $character->Name }}</span>
                                </flux:cell>

                                <flux:cell variant="strong">
                                    {!! $this->getEntryText($this->getEntryCount($character, self::EVENT_TYPE_BLOOD_CASTLE), $this->maxEntries()) !!}
                                </flux:cell>
                            </flux:row>
                        @endforeach
                    </flux:rows>
                </flux:table>
            </flux:card>

            <flux:card class="w-full space-y-6">
                <flux:heading size="lg">
                    {{ __('Devil Square') }}
                </flux:heading>
                <flux:table>
                    <flux:rows>
                        @foreach ($this->characters as $character)
                            <flux:row :key="$character->Name">
                                <flux:cell class="flex items-center gap-3">
                                    <flux:avatar size="xs" src="{{ asset($character->Class->getImagePath()) }}"/>
                                    <span>{{ $character->Name }}</span>
                                </flux:cell>

                                <flux:cell variant="strong">
                                    {!! $this->getEntryText($this->getEntryCount($character, self::EVENT_TYPE_DEVIL_SQUARE), $this->maxEntries()) !!}
                                </flux:cell>
                            </flux:row>
                        @endforeach
                    </flux:rows>
                </flux:table>
            </flux:card>
        </div>
    @else
        <flux:text>
            {{ __('No characters found.') }}
        </flux:text>
    @endif
</div>