<?php

namespace App\Livewire\Pages\App\Castle;

use App\Livewire\BaseComponent;
use Livewire\Attributes\On;

class Treasury extends BaseComponent
{
    public int $treasury = 0;

    public function mount(int $treasury)
    {
        $this->treasury = $treasury;
    }

    #[On('treasury-updated')]
    public function updateTreasury(int $treasury): void
    {
        $this->treasury = $treasury;
    }

    protected function getViewName(): string
    {
        return 'pages.app.castle.treasury';
    }

    protected function getLayoutType(): string
    {
        return 'app';
    }
}
