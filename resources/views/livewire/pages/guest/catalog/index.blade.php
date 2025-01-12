<?php

use App\Models\Content\Download;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
//
}; ?>

<flux:main container class="space-y-40">
    <x-page-hero
        title="Your path, your possibilities"
        kicker="Catalog"
        description="Explore our selection of convenience features and enhancements designed to complement your gameplay experience. See detailed information about everything available in the game."
    />

    <section class="flex w-full flex-col xl:flex-row xl:max-w-none max-w-md gap-6 xl:gap-0 mx-auto">
        <div
            class="flex-1 w-full p-2 flex flex-col gap-2 rounded-2xl border border-zinc-200 dark:border-zinc-700/75 bg-zinc-100 dark:bg-zinc-900 xl:mt-10 xl:pr-0 xl:border-r-0 xl:rounded-r-none">
            <div
                class="h-full rounded-lg shadow-sm p-6 md:p-8 flex flex-col bg-white dark:bg-zinc-800 xl:rounded-r-none">

                <div class="flex items-start w-full mb-8">
                    <div class="space-y-4">
                        <div class="flex items-center gap-1 text-zinc-800 dark:text-white font-medium">
                            <flux:icon.fire class="text-orange-500 dark:text-orange-400"/>
                            <span>Bronze</span>
                        </div>

                        <div class="flex gap-2 items-baseline">
                            <div
                                class="flex items-center gap-2 text-3xl md:text-4xl font-semibold text-zinc-800 dark:text-white">
                                90
                            </div>
                            <div class="text-zinc-400 dark:text-zinc-300 font-medium text-base">tokens</div>
                        </div>

                        <flux:subheading size="sm" class="!mt-1">3 days</flux:subheading>
                    </div>

                    <flux:spacer/>
                </div>

                <div class="grid gap-3 mb-8">
                    <x-vip.benefits-list/>
                </div>

                <div class="flex-1" data-flux-spacer=""></div>

                <flux:button
                    variant="filled"
                    icon-trailing="chevron-right"
                    class="w-full"
                >
                    {{__('Upgrade to Bronze')}}
                </flux:button>
            </div>
        </div>

        <div
            class="flex-1 p-2 flex flex-col gap-2 rounded-2xl border-2 border-zinc-800 dark:border-zinc-200 bg-zinc-100 dark:bg-zinc-900 xl:-mb-4">
            <div class="h-full rounded-lg shadow-sm p-6 md:p-8 flex flex-col bg-white dark:bg-zinc-800  xl:pb-12">
                <div class="flex items-start w-full mb-8">
                    <div class="space-y-4">
                        <div class="flex items-center gap-1 text-zinc-800 dark:text-white font-medium">
                            <flux:icon.fire class="text-yellow-500 dark:text-yellow-400"/>
                            <span>Gold</span>
                        </div>

                        <div class="flex gap-2 items-baseline">
                            <div
                                class="flex items-center gap-2 text-3xl md:text-4xl font-semibold text-zinc-800 dark:text-white">
                                450
                            </div>
                            <div class="text-zinc-400 dark:text-zinc-300 font-medium text-base">tokens</div>
                        </div>

                        <flux:subheading size="sm" class="!mt-1">30 days</flux:subheading>
                    </div>

                    <flux:spacer/>

                    <flux:badge icon="fire" color="orange">
                        Best Value · 50% off
                    </flux:badge>
                </div>

                <div class="grid gap-3 mb-8">
                    <x-vip.benefits-list/>
                </div>

                <div class="flex-1" data-flux-spacer=""></div>

                <flux:button
                    variant="primary"
                    icon-trailing="chevron-right"
                    class="w-full"
                >
                    {{__('Upgrade to Gold')}}
                </flux:button>
            </div>
        </div>

        <div
            class="flex-1 p-2 flex flex-col gap-2 rounded-2xl border border-zinc-200 dark:border-zinc-700/75 bg-zinc-100 dark:bg-zinc-900 xl:mt-10 xl:rounded-l-none xl:border-l-0 xl:pl-0">
            <div
                class="h-full rounded-lg shadow-sm p-6 md:p-8 flex flex-col bg-white dark:bg-zinc-800 xl:rounded-l-none">

                <div class="flex items-start w-full mb-8">
                    <div class="space-y-4">
                        <div class="flex items-center gap-1 text-zinc-800 dark:text-white font-medium">
                            <flux:icon.fire class="text-zinc-500 dark:text-zinc-400"/>
                            <span>Silver</span>
                        </div>

                        <div class="flex gap-2 items-baseline">
                            <div
                                class="flex items-center gap-2 text-3xl md:text-4xl font-semibold text-zinc-800 dark:text-white">
                                140
                            </div>
                            <div class="text-zinc-400 dark:text-zinc-300 font-medium text-base">tokens</div>
                        </div>

                        <flux:subheading size="sm" class="!mt-1">7 days</flux:subheading>
                    </div>

                    <flux:spacer/>
                </div>

                <div class="grid gap-3 mb-8">
                    <x-vip.benefits-list/>
                </div>
                <div class="flex-1" data-flux-spacer=""></div>

                <flux:button
                    variant="filled"
                    icon-trailing="chevron-right"
                    class="w-full"
                >
                    {{__('Upgrade to Silver')}}
                </flux:button>
            </div>
        </div>
    </section>
</flux:main>

