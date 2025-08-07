<?php

namespace App\Livewire\Pages\App\Partners;

use App\Livewire\BaseComponent;

class Partners extends BaseComponent
{
    protected function getViewName(): string
    {
        return 'pages.app.partners.index';
    }

    protected function getLayoutType(): string
    {
        return 'app';
    }
}
