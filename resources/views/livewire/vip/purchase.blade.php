<?php

use App\Enums\Game\AccountLevel;
use App\Models\User\User;
use App\Models\Utility\VipPackage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {
    public User $user;

    public function mount(): void
    {
        $this->user = auth()->user();
        
        if ($this->user->member->AccountLevel !== AccountLevel::Regular) {
            Redirect::route('vip');
        }
    }

    #[Computed]
    public function packages()
    {
        return VipPackage::orderBy('sort_order', 'asc')->get();
    }
}; ?>

<div class="space-y-8">
    <header>
        <flux:heading size="xl">
            {{ __('Upgrade Your Account') }}
        </flux:heading>

        <flux:subheading>
            {{ __('Get a head start and accelerate your progress with our premium packages.') }}
        </flux:subheading>
    </header>

    <div class="grid sm:grid-cols-2 gap-4">
        @foreach ($this->packages as $index => $package)
            <x-vip.package-card
                :$package
                :is-featured="$loop->first"
            />
        @endforeach
    </div>
</div>
