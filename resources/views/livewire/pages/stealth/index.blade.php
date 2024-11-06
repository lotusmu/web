<?php

use App\Actions\Member\ManageStealthMode;
use App\Models\User\User;
use App\Enums\Utility\OperationType;
use App\Models\Concerns\Taxable;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {
    use Taxable;

    public User $user;

    public function mount(): void
    {
        $this->user          = Auth::user();
        $this->operationType = OperationType::STEALTH;
        $this->initializeTaxable();
    }

    public function enable(ManageStealthMode $action): void
    {
        $action->handle($this->user);

        $this->modal('enable')->close();
    }

    public function extend(ManageStealthMode $action): void
    {
        $action->handle($this->user, 'extend');

        $this->modal('extend')->close();
    }
}; ?>

<div class="space-y-6">
    <header>
        <flux:heading size="xl">
            {{ __('Stealth Mode') }}
        </flux:heading>

        <x-flux::subheading>
            {{ __('Stealth Mode conceals your character\'s information from public view.') }}
        </x-flux::subheading>
    </header>


    <div class="flex max-md:flex-col gap-6 w-full">
        <livewire:pages.stealth.normal-mode-card/>
        <livewire:pages.stealth.stealth-mode-card/>
    </div>

    <flux:card class="space-y-6">
        <div class="flex max-sm:flex-col max-sm:space-y-2 items-start w-full">
            <div class="space-y-4">
                <flux:heading class="flex items-center gap-2">
                    <flux:icon.eye-slash/>
                    <span>{{__('Stealth Mode')}}</span>
                </flux:heading>

                <div class="flex gap-2 items-baseline">
                    <div
                        class="flex items-center gap-2 text-3xl md:text-4xl font-semibold text-zinc-800 dark:text-white">
                        {{ number_format($this->getCost()) }}
                    </div>
                    <div
                        class="text-zinc-400 dark:text-zinc-300 font-medium text-base">{{ __($this->getResourceType()) }}</div>
                </div>
            </div>

            <flux:spacer/>

            <flux:badge
                variant="pill"
                icon="calendar-days"
                :color="$user->hasActiveStealth() ? 'green' : 'orange'"
            >
                @if($user->hasActiveStealth())
                    {{ __('Active until :date', ['date' => $user->stealth->expires_at->format('M d Y, H:i')]) }}
                @else
                    {{ __(':duration days', ['duration' => $this->getDuration()]) }}
                @endif
            </flux:badge>
        </div>

        <div>
            <flux:modal.trigger :name="$user->hasActiveStealth() ? 'extend' : 'enable'">
                <flux:button variant="primary" icon-trailing="chevron-right" class="w-full">
                    {{ $user->hasActiveStealth()
                        ? __('Extend :duration Days', ['duration' => $this->getDuration()])
                        : __('Enable') }}
                </flux:button>
            </flux:modal.trigger>
        </div>

        <flux:modal
            :name="$user->hasActiveStealth() ? 'extend' : 'enable'"
            class="min-w-[26rem] space-y-6"
        >
            <div>
                <flux:heading size="lg">
                    {{ $user->hasActiveStealth() ? __('Extend Stealth Mode') : __('Enable Stealth Mode?') }}
                </flux:heading>

                <flux:subheading>
                    {{ $user->hasActiveStealth()
                        ? __('Your stealth mode period will be extended for :duration more days.', ['duration' => $this->getDuration()])
                        : __('Your account information will be hidden for :duration days.', ['duration' => $this->getDuration()])
                    }}
                </flux:subheading>
            </div>

            <div>
                <flux:text class="flex gap-1">
                    {{ __('Price:') }}
                    <flux:heading>{{ number_format($this->getCost()) }} {{ __($this->getResourceType()) }}</flux:heading>
                </flux:text>
                <flux:text class="flex gap-1">
                    {{ __('Period:') }}
                    <flux:heading>{{ $this->getDuration() }} {{ __('days') }}</flux:heading>
                </flux:text>
            </div>

            <div class="flex gap-2">
                <flux:spacer/>

                <flux:modal.close>
                    <flux:button variant="ghost">{{ __('Cancel') }}</flux:button>
                </flux:modal.close>

                <flux:button
                    type="button"
                    variant="primary"
                    wire:click="{{ $user->hasActiveStealth() ? 'extend' : 'enable' }}"
                >
                    {{ $user->hasActiveStealth() ? __('Extend') : __('Enable') }}
                </flux:button>
            </div>
        </flux:modal>
    </flux:card>
</div>
