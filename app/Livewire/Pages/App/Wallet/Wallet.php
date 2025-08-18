<?php

namespace App\Livewire\Pages\App\Wallet;

use App\Livewire\BaseComponent;

class Wallet extends BaseComponent
{
    #[\Livewire\Attributes\Url]
    public string $tab = 'send-gift';

    protected function getViewName(): string
    {
        return 'pages.app.wallet.index';
    }

    protected function getLayoutType(): string
    {
        return 'app';
    }
}
