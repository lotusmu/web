@props(['character'])

<div>
    <flux:heading size="lg" class="mb-2">
        {{ __('General Information') }}
    </flux:heading>

    <flux:separator class="mb-8"/>

    <div class="flex items-start justify-start space-x-8">
        <div class="min-w-64">
            <img src="{{ asset($character->Class->getBigImage()) }}"
                 alt="Character Class Image"
                 class="w-64 h-64 object-cover">
        </div>

        <x-character.details :character="$character"/>
    </div>
</div>
