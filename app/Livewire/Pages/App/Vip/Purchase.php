<?php

namespace App\Livewire\Pages\App\Vip;

use App\Actions\Member\UpgradeAccountLevel;
use App\Livewire\BaseComponent;
use App\Models\User\User;
use App\Models\Utility\VipPackage;
use Flux;
use Livewire\Attributes\Computed;

class Purchase extends BaseComponent
{
    public User $user;

    public function mount(): void
    {
        $this->user = auth()->user();
    }

    #[Computed]
    public function packages()
    {
        return VipPackage::orderBy('sort_order', 'asc')->get();
    }

    public function purchase($packageId, UpgradeAccountLevel $action): void
    {
        $package = VipPackage::findOrFail($packageId);

        Flux::modal('upgrade-to-'.strtolower($package->level->getLabel()))->close();

        if ($action->handle($this->user, $package)) {
            $this->redirect(route('vip'), navigate: true);
        }

    }

    protected function getViewName(): string
    {
        return 'pages.app.vip.purchase';
    }

    protected function getLayoutType(): string
    {
        return 'app';
    }
}
