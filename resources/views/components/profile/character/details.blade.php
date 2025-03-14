@props(['character'])

<div class="flex flex-col space-y-4 w-full">
    {{-- Basic Info --}}
    <x-profile.detail-row
        label="{{ __('Name') }}"
        :value="$character->Name"
    />

    <x-profile.detail-row
        label="{{ __('Class') }}"
        :value="$character->Class->getLabel()"
    />

    <x-profile.detail-row
        label="{{ __('Level') }}"
        :value="$character->cLevel"
    />

    <x-profile.detail-row
        label="{{ __('Resets') }}"
        :value="$character->ResetCount"
    />

    <x-profile.detail-row
        label="{{ __('Location') }}"
        :value="$character->getDisplayLocation()"
    />

    {{-- Guild Info --}}
    @if($character->guildMember)
        <flux:separator variant="subtle"/>

        <div class="grid grid-cols-3 gap-4">
            <flux:text>{{ __('Guild') }}</flux:text>
            <div class="col-span-2 text-sm">
                <x-guild-identity :guild-member="$character->guildMember"/>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-4">
            <flux:text>{{ __('Position') }}</flux:text>
            <div class="col-span-2">
                <x-guild-member :guild-member="$character->guildMember"/>
            </div>
        </div>
    @endif

    {{-- Stats Info --}}
    <flux:separator variant="subtle"/>

    <x-profile.detail-row
        label="{{ __('HoF Wins') }}"
        :value="$character->HofWins"
    />

    <x-profile.detail-row
        label="{{ __('Quests') }}"
        :value="$character->getQuestCountAttribute()"
    />

    <x-profile.detail-row
        label="{{ __('Event Score') }}"
        :value="number_format($character->EventScore)"
    />

    <x-profile.detail-row
        label="{{ __('Hunt Score') }}"
        :value="number_format($character->HunterScore)"
    />

    {{-- Character Stats --}}
    <flux:separator variant="subtle"/>

    <x-profile.detail-row
        label="{{ __('Strength') }}"
        :value="$character->getDisplayStrength()"
    />

    <x-profile.detail-row
        label="{{ __('Agility') }}"
        :value="$character->getDisplayDexterity()"
    />

    <x-profile.detail-row
        label="{{ __('Vitality') }}"
        :value="$character->getDisplayVitality()"
    />

    <x-profile.detail-row
        label="{{ __('Energy') }}"
        :value="$character->getDisplayEnergy()"
    />

    @if($character->Leadership > 0)
        <x-profile.detail-row
            label="{{ __('Command') }}"
            :value="$character->getDisplayLeadership()"
        />
    @endif
</div>
