<?php

namespace App\Livewire\Pages\App\Profile;

use App\Livewire\BaseComponent;

class Profile extends BaseComponent
{
    #[\Livewire\Attributes\Url]
    public string $tab = 'email';

    protected function getViewName(): string
    {
        return 'pages.app.profile.index';
    }

    protected function getLayoutType(): string
    {
        return 'app';
    }
}
