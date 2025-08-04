<?php

namespace App\Livewire\Pages\Guest\Catalog\Vip;

use App\Models\Utility\VipPackage;
use App\Livewire\BaseComponent;
use Livewire\Attributes\Computed;
use App\Actions\Member\UpgradeAccountLevel;
use Flux;

class VipList extends BaseComponent
{
    #[Computed]
    public function packages()
    {
        return VipPackage::all()->sortBy('catalog_order');
    }

    public function purchase($packageId, UpgradeAccountLevel $action): void
    {
        $package = VipPackage::findOrFail($packageId);

        Flux::modal('upgrade-to-'.strtolower($package->level->getLabel()))->close();

        if ($action->handle(auth()->user(), $package)) {
            $this->redirect(route('vip'), navigate: true);
        }
    }

    protected function getViewName(): string
    {
        return 'pages.guest.catalog.vip.list';
    }

    protected function getLayoutType(): string
    {
        return 'guest';
    }
}
