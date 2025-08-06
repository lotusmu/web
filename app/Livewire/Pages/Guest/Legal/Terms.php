<?php

namespace App\Livewire\Pages\Guest\Legal;

use App\Livewire\BaseComponent;

class Terms extends BaseComponent
{
    //

    protected function getViewName(): string
    {
        return 'pages.guest.legal.terms';
    }

    protected function getLayoutType(): string
    {
        return 'guest';
    }
}
