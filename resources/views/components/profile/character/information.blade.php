@props(['character'])

<div>
    <flux:heading size="lg" class="mb-2">
        {{ __('General Information') }}
    </flux:heading>

    <flux:separator class="mb-8"/>

    <div
        class="flex items-start justify-start sm:space-x-8 max-sm:flex-col max-sm:space-y-8">
        <div class="min-w-64 max-sm:min-w-48">
            <img src="{{ asset($character->Class->getBigImage()) }}"
                 alt="{{ $character->Class->getLabel() }}"
                 class="w-64 h-64 max-sm:w-48 max-sm:h-48 object-cover">
        </div>

        <x-profile.character.details :character="$character"/>
    </div>
</div>
