@props(['character'])

<div class="flex flex-col space-y-4 w-full">
    <x-character.detail-row :label="__('Name')" :value="$character->Name"/>

    <x-character.detail-row :label="__('Class')" :value="$character->Class->getLabel()"/>

    <x-character.detail-row :label="__('Level')" :value="$character->cLevel"/>

    <x-character.detail-row :label="__('Resets')" :value="$character->ResetCount"/>

    <x-character.detail-row :label="__('Location')" :value="$character->MapNumber->getLabel()"/>

    <flux:separator variant="subtle"/>

    @if($character->guildMember)
        <div class="grid grid-cols-3 gap-4">
            <flux:text>{{ __('Guild') }}</flux:text>
            <x-guild-identity :guild-member="$character->guildMember"/>
        </div>

        <div class="grid grid-cols-3 gap-4">
            <flux:text>{{ __('Position') }}</flux:text>
            <x-guild-member :guild-member="$character->guildMember" class="col-span-2"/>
        </div>
    @endif

    <flux:separator variant="subtle"/>

    <x-character.detail-row :label="__('HoF Wins')" :value="$character->HofWins"/>

    <x-character.detail-row :label="__('Quests')" :value="$character->getQuestCountAttribute()"/>

    <x-character.detail-row :label="__('Event Score')" :value="number_format($character->EventScore)"/>

    <x-character.detail-row :label="__('Hunt Score')" :value="number_format($character->HunterScore)"/>

    <flux:separator variant="subtle"/>

    <x-character.detail-row :label="__('Strength')" :value="number_format($character->Strength)"/>

    <x-character.detail-row :label="__('Agility')" :value="number_format($character->Dexterity)"/>

    <x-character.detail-row :label="__('Vitality')" :value="number_format($character->Vitality)"/>

    <x-character.detail-row :label="__('Energy')" :value="number_format($character->Energy)"/>

    @if($character->Leadership > 0)
        <x-character.detail-row :label="__('Command')" :value="number_format($character->Leadership)"/>
    @endif
</div>
