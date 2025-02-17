<?php

use App\Models\Utility\VipPackage;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use App\Actions\Member\UpgradeAccountLevel;

new class extends Component {
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
}; ?>


<section class="flex w-full flex-col lg:flex-row lg:max-w-none max-w-md gap-6 lg:gap-0 mx-auto">
    @if($this->packages->isNotEmpty())
        @foreach($this->packages as $package)
            <livewire:pages.guest.catalog.vip.card
                :$package
                :is-featured="$package->is_best_value"
                :wire:key="'package-' . $package->id"
            />
        @endforeach
    @endif
</section>
