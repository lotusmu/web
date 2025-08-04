<?php

namespace App\Livewire\Pages\Guest\Legal;

use App\Livewire\BaseComponent;

class Refund extends BaseComponent
{
    //

    protected function getViewName(): string
    {
        return 'pages.guest.legal.refund';
    }

    protected function getLayoutType(): string
    {
        return 'guest';
    }
}