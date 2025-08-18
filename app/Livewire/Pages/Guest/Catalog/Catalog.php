<?php

namespace App\Livewire\Pages\Guest\Catalog;

use App\Livewire\BaseComponent;

class Catalog extends BaseComponent
{
    protected function getViewName(): string
    {
        return 'pages.guest.catalog.index';
    }

    protected function getLayoutType(): string
    {
        return 'guest';
    }
}
