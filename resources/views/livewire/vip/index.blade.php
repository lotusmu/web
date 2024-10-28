<?php

use App\Actions\Member\ExtendVipSubscription;
use App\Enums\Game\AccountLevel;
use App\Models\User\User;
use App\Models\Utility\VipPackage;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {
    public User $user;

    public $packageId;

    public function mount(): void
    {
        $this->user = auth()->user();

        if ($this->user->member->AccountLevel === AccountLevel::Regular) {
            Redirect::route('vip.purchase');
        }
    }

    #[Computed]
    public function accountLevel(): ?array
    {
        $level = $this->user->member->AccountLevel;
        if ($level === AccountLevel::Regular) {
            return null;
        }

        return [
                'label'      => $this->user->member->AccountLevel->getLabel(),
                'color'      => $this->user->member->AccountLevel->badgeColor(),
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
}; ?>

<div class="space-y-8">
    <header class="flex items-center max-md:flex-col-reverse max-md:items-start max-md:gap-4">
        <div>
            <flux:heading size="xl">
                {{ __('Account Level') }}
            </flux:heading>

            <flux:subheading>
                {{ __('Upgrade your account, or extend your VIP subscription for continued benefits.') }}
            </flux:subheading>
        </div>

        <flux:spacer/>

        <flux:modal.trigger name="extend-subscription">
            <flux:button size="sm" icon-trailing="chevron-right">
                {{__('Extend Now')}}
            </flux:button>
        </flux:modal.trigger>
    </header>

    <flux:card class="space-y-8">
        <div class="flex items-center">
            <div>
                <flux:heading size="lg">
                    {{__('Current Tier')}}
                </flux:heading>
                <flux:subheading>
                    Active until {{ $this->accountLevel['expireDate']->format('F d, Y H:i') }}
                </flux:subheading>
            </div>

            <flux:spacer/>

            <flux:badge icon="fire" size="lg" color="{{ $this->accountLevel['color'] }}" inset="top bottom">
                {{ $this->accountLevel['label'] }}
            </flux:badge>
        </div>

        <div>
            <flux:heading class="mb-4">
                {{__('Benefits included')}}
            </flux:heading>

            <div class="grid gap-3 sm:grid-cols-2">
                <x-vip.benefits-list/>
            </div>
        </div>
    </flux:card>

    <flux:modal name="extend-subscription" class="md:w-96 space-y-6">
        <div>
            <flux:heading size="lg">Extend Your Subscription</flux:heading>
            <flux:subheading>Choose a package to extend your VIP.</flux:subheading>
        </div>

        <form wire:submit="extend" class="space-y-6">
            <flux:select wire:model="packageId" variant="listbox" placeholder="{{__('Choose package...')}}">
                @foreach($this->packages as $package)
                    <flux:option value="{{$package->id}}">
                        {{$package->duration}} days ({{$package->cost}} tokens)
                    </flux:option>
                @endforeach
            </flux:select>

            <div class="flex gap-2">
                <flux:spacer/>

                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>

                <flux:button type="submit" variant="primary">Confirm</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
