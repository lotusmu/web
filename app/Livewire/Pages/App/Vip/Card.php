<?php

namespace App\Livewire\Pages\App\Vip;

use App\Livewire\BaseComponent;
use App\Models\Utility\VipPackage;
use Livewire\Attributes\Computed;

class Card extends BaseComponent
{
    public VipPackage $package;

    public bool $isFeatured = false;

    #[Computed]
    public function label(): string
    {
        return $this->package->level->getLabel();
    }

    protected function getViewName(): string
    {
        return 'pages.app.vip.card';
    }

    protected function getLayoutType(): string
    {
        return 'app';
    }
}
