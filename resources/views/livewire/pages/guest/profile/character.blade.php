<?php

use App\Actions\Character\GetAccountCharacters;
use App\Actions\Character\GetCharacterProfile;
use App\Models\Game\Character;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public ?string $name = null;

    public function mount(?string $name = null): void
    {
        $this->name = $name;
    }

    #[Computed]
    public function profile(): ?Character
    {
        return app(GetCharacterProfile::class)->handle($this->name);
    }

    #[Computed]
    public function accountCharacters()
    {
        if ( ! $this->profile) {
            return collect();
        }

        return app(GetAccountCharacters::class)
            ->handle($this->profile->AccountID, $this->name);
    }

    #[Computed]
    public function accountLevel(): ?array
    {
        if ( ! $this->profile?->member?->AccountLevel) {
            return null;
        }

        return [
            'label' => $this->profile->member->AccountLevel->getLabel(),
            'color' => $this->profile->member->AccountLevel->badgeColor(),
        ];
    }
}; ?>

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
