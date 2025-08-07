<?php

namespace App\Livewire\Pages\App\Vip;

use App\Actions\Member\ExtendVipSubscription;
use App\Livewire\BaseComponent;
use App\Models\User\User;
use App\Models\Utility\VipPackage;
use Livewire\Attributes\Computed;

class Vip extends BaseComponent
{
    public User $user;

    public $packageId;

    public function mount(): void
    {
        $this->user = auth()->user();
    }

    #[Computed]
    public function accountLevel(): ?array
    {
        if (! $this->user->hasValidVipSubscription()) {
            return null;
        }

        return [
            'label' => $this->user->member->AccountLevel->getLabel(),
            'color' => $this->user->member->AccountLevel->badgeColor(),
            'expireDate' => $this->user->member->AccountExpireDate,
        ];
    }

    #[Computed]
    public function packages()
    {
        return VipPackage::orderBy('level', 'asc')->get();
    }

    public function extend(ExtendVipSubscription $action): void
    {
        $package = VipPackage::findOrFail($this->packageId);

        if ($action->handle($this->user, $package)) {
            $this->modal('extend-subscription')->close();

            $this->reset('packageId');
        }
    }

    protected function getViewName(): string
    {
        return 'pages.app.vip.index';
    }

    protected function getLayoutType(): string
    {
        return 'app';
    }
}
