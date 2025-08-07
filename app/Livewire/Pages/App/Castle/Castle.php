<?php

namespace App\Livewire\Pages\App\Castle;

use App\Livewire\BaseComponent;
use App\Models\Game\CastleData;
use App\Models\Game\Guild;
use Livewire\Attributes\Computed;

class Castle extends BaseComponent
{
    public ?Guild $guild = null;

    public ?CastleData $castle = null;

    public function mount()
    {
        $this->castle = CastleData::first();

        $this->guild = Guild::where('G_Name', $this->castle->OWNER_GUILD)->first();
    }

    #[Computed]
    public function canWithdraw(): bool
    {
        return auth()->user()->isCastleLord($this->castle);
    }

    #[Computed]
    public function treasury(): int
    {
        return $this->castle->MONEY;
    }

    #[Computed]
    public function storeTax(): int
    {
        return $this->castle->store_tax;
    }

    #[Computed]
    public function goblinTax(): int
    {
        return $this->castle->goblinTax;
    }

    #[Computed]
    public function huntZoneTax(): int
    {
        return $this->castle->huntZoneTax;
    }

    protected function getViewName(): string
    {
        return 'pages.app.castle.index';
    }

    protected function getLayoutType(): string
    {
        return 'app';
    }
}
