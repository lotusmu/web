<?php

namespace App\Livewire\Pages\Guest\Catalog\Vip;

use App\Livewire\BaseComponent;
use App\Models\Utility\VipPackage;
use Flux;
use Livewire\Attributes\Computed;

class VipCard extends BaseComponent
{
    public VipPackage $package;

    public bool $isFeatured = false;

    #[Computed]
    public function label(): string
    {
        return cache()->remember(
            "package.label.{$this->package->id}",
            now()->addDay(),
            fn () => $this->package->level->getLabel()
        );
    }

    public function upgrade()
    {
        if (! auth()->check()) {
            session()->put('url.intended', route('vip.purchase'));

            return $this->redirect(route('login'));
        }

        Flux::modal('upgrade-to-'.strtolower($this->label))->show();
    }

    protected function getViewName(): string
    {
        return 'pages.guest.catalog.vip.card';
    }

    protected function getLayoutType(): string
    {
        return 'guest';
    }
}
