<?php

namespace App\Livewire\Pages\App\Profile;

use App\Livewire\BaseComponent;

class Appearance extends BaseComponent
{
    //

    protected function getViewName(): string
    {
        return 'pages.app.profile.appearance';
    }

    protected function getLayoutType(): string
    {
        return 'app';
    }
}
