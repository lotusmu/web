<?php

use App\Enums\Game\CharacterClass;
use App\Models\Utility\VipPackage;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use App\Actions\Member\UpgradeAccountLevel;

new #[Layout('layouts.guest')] class extends Component {
//
}; ?>

<flux:main container class="space-y-40">
    <x-page-header
        title="title"
        kicker="Guild"
        description="description"
    />
</flux:main>
