<?php

namespace App\Livewire\Pages\Guest\Rankings\Spotlight;

use App\Livewire\BaseComponent;
use App\Models\Game\CastleData;
use App\Models\Game\Guild;
use Livewire\Attributes\Computed;

class Guilds extends BaseComponent
{
    public ?CastleData $castle = null;

    public function mount()
    {
        $this->castle = CastleData::first();
    }

    #[Computed]
    public function guild()
    {
        return Guild::query()
            ->select([
                'G_Name',
                'G_Mark',
                'G_Master',
                'CS_Wins',
            ])
            ->where('G_Name', $this->castle->OWNER_GUILD)
            ->withCount('members')
            ->first();
    }

    protected function getViewName(): string
    {
        return 'pages.guest.rankings.spotlight.guilds';
    }

    protected function getLayoutType(): string
    {
        return 'guest';
    }
}
