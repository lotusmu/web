<flux:main container>
    <flux:card class="max-w-2xl mx-auto space-y-8">
        @if($this->profile)
            <x-profile.character.information :character="$this->profile"/>

            <x-profile.character.account
                :character="$this->profile"
                :account-level="$this->accountLevel"
                :account-characters="$this->accountCharacters"
            />
        @else
            <flux:text>{{ __('Character not found or has been deleted.') }}</flux:text>
        @endif
    </flux:card>
</flux:main>