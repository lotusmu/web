<?php

namespace App\Livewire\Pages\Guest\Profile;

use App\Actions\Guild\GetGuildProfile;
use App\Livewire\BaseComponent;
use App\Models\Game\Guild;
use Livewire\Attributes\Computed;

class GuildProfile extends BaseComponent
{
    public ?string $name = null;

    public function mount(?string $name = null): void
    {
        $this->name = $name;
    }

    #[Computed]
    public function profile(): ?Guild
    {
        return app(GetGuildProfile::class)->handle($this->name);
    }

    protected function getViewName(): string
    {
        return 'pages.guest.profile.guild';
    }

    protected function getLayoutType(): string
    {
        return 'guest';
    }
}
