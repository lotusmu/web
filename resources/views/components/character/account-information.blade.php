@props(['character', 'accountLevel', 'accountCharacters'])

<div>
    <flux:heading size="lg" class="mb-2">
        {{ __('Account Information') }}
    </flux:heading>

    <flux:separator class="mb-8"/>

    <div class="flex items-center justify-evenly mb-8">
        <x-character.account-stats
            :character="$character"
            :account-level="$accountLevel"
        />
    </div>

    <x-character.account-characters-table
        :characters="$accountCharacters"
    />
</div>
