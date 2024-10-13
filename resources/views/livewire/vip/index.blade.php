<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {
//
}; ?>

<div class="space-y-8">
    <header>
        <flux:heading size="xl">
            {{ __('Upgrade Your Account') }}
        </flux:heading>

        <x-flux::subheading>
            {{ __('Get a head start and accelerate your progress with our premium packages.') }}
        </x-flux::subheading>
    </header>

    <div class="grid grid-cols-2 gap-4">
        <div
            class="col-span-2 p-2 flex flex-col gap-2 rounded-2xl border border-zinc-200 dark:border-zinc-700/75 bg-zinc-100 dark:bg-zinc-900">

            <div
                class="flex flex-col space-y-8 h-full rounded-lg shadow-sm p-6 md:p-8 bg-white dark:bg-zinc-800">

                <x-vip.package-tier-header
                    :tokens="450"
                    :duration="30"
                    tier="Gold"
                    is-best-value
                />

                <div class="grid grid-cols-2 gap-3">
                    <x-vip.bonus-list/>
                </div>

                <div>
                    <flux:modal.trigger name="upgrade-to-gold">
                        <flux:button variant="primary" icon-trailing="chevron-right"
                                     class="!text-base !h-12 xl:translate-y-px w-full">
                            Upgrade to Gold
                        </flux:button>
                    </flux:modal.trigger>

                    <flux:modal name="upgrade-to-gold" class="min-w-[22rem] space-y-6">
                        <div>
                            <flux:heading size="lg">Upgrade to Gold?</flux:heading>

                            <flux:subheading>
                                <p>You're about to upgrade your account to Gold.</p>
                                <p>Enjoy exclusive benefits and accelerate your progress!</p>
                            </flux:subheading>
                        </div>

                        <div>
                            <flux:text class="flex gap-1">
                                Price:
                                <flux:heading>450 tokens</flux:heading>
                            </flux:text>
                            <flux:text class="flex gap-1">
                                Duration:
                                <flux:heading>30 days</flux:heading>
                            </flux:text>
                        </div>

                        <div class="flex gap-2">
                            <flux:spacer/>

                            <flux:modal.close>
                                <flux:button variant="ghost">Cancel</flux:button>
                            </flux:modal.close>

                            <flux:button type="submit" variant="primary">Confirm</flux:button>
                        </div>
                    </flux:modal>
                </div>
            </div>
        </div>

        <div
            class="p-2 flex flex-col gap-2 rounded-2xl border border-zinc-200 dark:border-zinc-700/75 bg-zinc-100 dark:bg-zinc-900">
            <div
                class="flex flex-col space-y-8 h-full rounded-lg shadow-sm p-6 md:p-8 bg-white dark:bg-zinc-800">

                <x-vip.package-tier-header
                    :tokens="90"
                    :duration="3"
                    tier="Bronze"
                />

                <div class="mb-8 flex flex-col gap-3 xl:-translate-y-px">
                    <x-vip.bonus-list/>
                </div>

                <flux:button variant="filled" icon-trailing="chevron-right"
                             class="!text-base !h-12 xl:translate-y-px">
                    Upgrade to Bronze
                </flux:button>
            </div>
        </div>

        <div
            class="p-2 flex flex-col gap-2 rounded-2xl border border-zinc-200 dark:border-zinc-700/75 bg-zinc-100 dark:bg-zinc-900">
            <div
                class="flex flex-col space-y-8 h-full rounded-lg shadow-sm p-6 md:p-8 bg-white dark:bg-zinc-800">

                <x-vip.package-tier-header
                    :tokens="140"
                    :duration="7"
                    tier="Silver"
                />

                <div class="mb-8 flex flex-col gap-3 xl:-translate-y-px">
                    <x-vip.bonus-list/>
                </div>

                <flux:button variant="filled" icon-trailing="chevron-right"
                             class="!text-base !h-12 xl:translate-y-px">
                    Upgrade to Silver
                </flux:button>
            </div>
        </div>
    </div>
</div>
