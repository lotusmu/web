<?php

use App\Actions\Character\GetCharacterAccountCharacters;
use App\Actions\Character\GetCharacterProfile;
use App\Enums\Game\AccountLevel;
use App\Enums\Game\CharacterClass;
use App\Models\Game\Character;
use App\Models\Game\Guild;
use Illuminate\Support\Collection;
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
    public function character(): ?Character
    {
        return app(GetCharacterProfile::class)->handle($this->name);
    }


    #[Computed]
    public function accountCharacters()
    {
        if ( ! $this->character) {
            return collect();
        }

        return app(GetCharacterAccountCharacters::class)
            ->handle($this->character->AccountID, $this->name);
    }


    #[Computed]
    public function accountLevel(): ?array
    {
        return app(GetCharacterProfile::class)
            ->getAccountLevelDetails($this->character?->member->AccountLevel);
    }
}; ?>

<flux:main container>
    <flux:card class="max-w-2xl mx-auto space-y-8">
        @if($this->character)
            <x-character.general-information :character="$this->character"/>
            <x-character.account-information
                :character="$this->character"
                :account-level="$this->accountLevel"
                :account-characters="$this->accountCharacters"
            />
        @else
            <flux:text>
                Character not found or has been deleted.
            </flux:text>
        @endif
    </flux:card>
</flux:main>
