<?php

namespace App\Livewire\Pages\Guest\Rankings;

use App\Livewire\BaseComponent;

class Index extends BaseComponent
{
    #[\Livewire\Attributes\Url]
    public string $tab = 'players';

    #[\Livewire\Attributes\Url]
    public string $type = 'general';

    public function mount()
    {
        if (! in_array($this->tab, ['players', 'guilds'])) {
            $this->tab = 'players';
        }

        if (! in_array($this->type, ['general', 'weekly'])) {
            $this->type = 'general';
        }
    }

    protected function getViewName(): string
    {
        return 'pages.guest.rankings.index';
    }

    protected function getLayoutType(): string
    {
        return 'guest';
    }
}
