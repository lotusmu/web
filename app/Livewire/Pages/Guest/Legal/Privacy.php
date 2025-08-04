<?php

namespace App\Livewire\Pages\Guest\Legal;

use App\Livewire\BaseComponent;

class Privacy extends BaseComponent
{
    //

    protected function getViewName(): string
    {
        return 'pages.guest.legal.privacy';
    }

    protected function getLayoutType(): string
    {
        return 'guest';
    }
}