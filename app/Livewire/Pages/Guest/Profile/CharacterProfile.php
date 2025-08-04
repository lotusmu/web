<?php

namespace App\Livewire\Pages\Guest\Profile;

use App\Actions\Character\GetAccountCharacters;
use App\Actions\Character\GetCharacterProfile;
use App\Livewire\BaseComponent;
use App\Models\Game\Character;
use Livewire\Attributes\Computed;

class CharacterProfile extends BaseComponent
{
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
        if ( ! $this->profile || $this->profile->shouldHideInformation()) {
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

    protected function getViewName(): string
    {
        return 'pages.guest.profile.character';
    }

    protected function getLayoutType(): string
    {
        return 'guest';
    }
}
