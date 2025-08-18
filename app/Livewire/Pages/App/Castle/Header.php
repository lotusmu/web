<?php

namespace App\Livewire\Pages\App\Castle;

use App\Livewire\BaseComponent;
use App\Models\Game\Guild;

class Header extends BaseComponent
{
    public ?Guild $guild = null;

    public function mount(Guild $guild)
    {
        $this->guild = $guild;
    }

    protected function getViewName(): string
    {
        return 'pages.app.castle.header';
    }

    protected function getLayoutType(): string
    {
        return 'app';
    }
}
