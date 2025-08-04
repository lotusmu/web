<?php

namespace App\Livewire\Pages\Guest\Legal;

use App\Livewire\BaseComponent;

class Guidelines extends BaseComponent
{
    //

    protected function getViewName(): string
    {
        return 'pages.guest.legal.guidelines';
    }

    protected function getLayoutType(): string
    {
        return 'guest';
    }
}