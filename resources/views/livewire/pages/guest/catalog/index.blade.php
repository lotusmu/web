<?php

use App\Models\Utility\VipPackage;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use App\Actions\Member\UpgradeAccountLevel;

new #[Layout('layouts.guest')] class extends Component {
//
}; ?>

<flux:main container class="space-y-40">
    <x-page-hero
        title="Your path, your possibilities"
        kicker="Catalog"
        description="Explore our selection of convenience features and enhancements designed to complement your gameplay experience. See detailed information about everything available in the game."
    />

    <livewire:pages.guest.catalog.vip.list/>
</flux:main>
