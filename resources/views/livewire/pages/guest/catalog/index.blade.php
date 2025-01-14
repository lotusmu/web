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

    <section class="isolate">
        <div class="text-center mb-12 space-y-4">
            <div class="flex justify-center">
                <div class="rounded-full bg-[color-mix(in_oklab,_var(--color-compliment),_transparent_90%)] p-3">
                    <flux:icon.wand-sparkles class="h-6 w-6 text-[var(--color-compliment-content)]"/>
                </div>
            </div>

            <p class="text-[var(--color-compliment-content)] !mt-2">
                {{ __('Enhancements') }}
            </p>

            <flux:heading size="2xl" level="2" class="max-w-3xl mx-auto">
                {{ __('Buffs & Boosts') }}
            </flux:heading>

            <flux:subheading class="mx-auto max-w-2xl leading-8">
                {{ __('Ancient powers adapted for modern battles. Enhance your gameplay with carefully balanced buffs that
                respect the core experience.') }}
            </flux:subheading>
        </div>

        <flux:card>
            <flux:tab.group>
                <flux:tabs variant="segmented" wire:model="tab" class="w-full max-sm:hidden">
                    <flux:tab name="7" icon="clock">7 days</flux:tab>
                    <flux:tab name="14" icon="clock">14 days</flux:tab>
                    <flux:tab name="30" icon="clock">30 days</flux:tab>
                </flux:tabs>

                <flux:tabs variant="segmented" size="sm" wire:model="tab" class="w-full sm:hidden">
                    <flux:tab name="7">7 days</flux:tab>
                    <flux:tab name="14">14 days</flux:tab>
                    <flux:tab name="30">30 days</flux:tab>
                </flux:tabs>

                <flux:tab.panel name="7">
                    <div
                        class="grid max-sm:justify-self-center grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-16">
                        <div class="flex items-start gap-2">
                            <img src="{{ asset('images/catalog/buffs/1.webp') }}"
                                 class="w-24 h-24 object-contain">

                            <div class="flex flex-col space-y-2 min-h-24">
                                <flux:heading>
                                    Scroll of Defense
                                </flux:heading>

                                <flux:text size="sm">
                                    <p>+200 defense</p>
                                    <p>+200 defense</p>
                                    <p>+200 defense</p>
                                </flux:text>

                                <flux:spacer/>

                                <flux:badge variant="pill" color="sky" size="sm" class="mt-auto w-fit">
                                    70 Credits
                                </flux:badge>
                            </div>
                        </div>
                        <div class="flex items-start gap-2">
                            <img src="{{ asset('images/catalog/buffs/2.webp') }}"
                                 class="w-24 h-24 object-contain">

                            <div class="flex flex-col space-y-2 min-h-28">
                                <flux:heading>
                                    Scroll of Defense/
                                </flux:heading>

                                <flux:text size="sm">
                                    <p>+200 defense</p>
                                </flux:text>

                                <flux:spacer/>

                                <flux:badge variant="pill" color="sky" size="sm" class="mt-auto w-fit">
                                    70 Credits
                                </flux:badge>
                            </div>
                        </div>
                        <div class="flex items-start gap-2">
                            <img src="{{ asset('images/catalog/buffs/3.webp') }}"
                                 class="w-24 h-24 object-contain">

                            <div class="flex flex-col space-y-2 min-h-28">
                                <flux:heading>
                                    Scroll of Defense
                                </flux:heading>

                                <flux:text size="sm">
                                    <p>+200 defense</p>
                                    <p>+200 defense</p>
                                </flux:text>

                                <flux:spacer/>

                                <flux:badge variant="pill" color="sky" size="sm" class="mt-auto w-fit">
                                    70 Credits
                                </flux:badge>
                            </div>
                        </div>
                        <div class="flex items-start gap-2">
                            <img src="{{ asset('images/catalog/buffs/5.webp') }}"
                                 class="w-24 h-24 object-contain">

                            <div class="flex flex-col space-y-2 min-h-28">
                                <flux:heading>
                                    Scroll of Defense
                                </flux:heading>

                                <flux:text size="sm">
                                    <p>+200 defense</p>
                                </flux:text>

                                <flux:spacer/>

                                <flux:badge variant="pill" color="sky" size="sm" class="mt-auto w-fit">
                                    70 Credits
                                </flux:badge>
                            </div>
                        </div>
                        <div class="flex items-start gap-2">
                            <img src="{{ asset('images/catalog/buffs/6.webp') }}"
                                 class="w-24 h-24 object-contain">

                            <div class="flex flex-col space-y-2 min-h-28">
                                <flux:heading>
                                    Scroll of Defense
                                </flux:heading>

                                <flux:text size="sm">
                                    <p>+200 defense</p>
                                </flux:text>

                                <flux:spacer/>

                                <flux:badge variant="pill" color="sky" size="sm" class="mt-auto w-fit">
                                    70 Credits
                                </flux:badge>
                            </div>
                        </div>
                        <div class="flex items-start gap-2">
                            <img src="{{ asset('images/catalog/buffs/8.webp') }}"
                                 class="w-24 h-24 object-contain">

                            <div class="flex flex-col space-y-2 min-h-28">
                                <flux:heading>
                                    Scroll of Defense
                                </flux:heading>

                                <flux:text size="sm">
                                    <p>+200 defense</p>
                                </flux:text>

                                <flux:spacer/>

                                <flux:badge variant="pill" color="sky" size="sm" class="mt-auto w-fit">
                                    70 Credits
                                </flux:badge>
                            </div>
                        </div>
                    </div>
                </flux:tab.panel>
                <flux:tab.panel name="14">
                    <div
                        class="grid max-sm:justify-self-center grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-16">
                        <div class="flex items-start gap-2">
                            <img src="{{ asset('images/catalog/buffs/1.webp') }}"
                                 class="w-24 h-24 object-contain">

                            <div class="flex flex-col space-y-2 min-h-24">
                                <flux:heading>
                                    Scroll of Defense
                                </flux:heading>

                                <flux:text size="sm">
                                    <p>+200 defense</p>
                                    <p>+200 defense</p>
                                    <p>+200 defense</p>
                                </flux:text>

                                <flux:spacer/>

                                <flux:badge variant="pill" color="sky" size="sm" class="mt-auto w-fit">
                                    140 Credits
                                </flux:badge>
                            </div>
                        </div>
                        <div class="flex items-start gap-2">
                            <img src="{{ asset('images/catalog/buffs/2.webp') }}"
                                 class="w-24 h-24 object-contain">

                            <div class="flex flex-col space-y-2 min-h-28">
                                <flux:heading>
                                    Scroll of Defense
                                </flux:heading>

                                <flux:text size="sm">
                                    <p>+200 defense</p>
                                </flux:text>

                                <flux:spacer/>

                                <flux:badge variant="pill" color="sky" size="sm" class="mt-auto w-fit">
                                    140 Credits
                                </flux:badge>
                            </div>
                        </div>
                        <div class="flex items-start gap-2">
                            <img src="{{ asset('images/catalog/buffs/3.webp') }}"
                                 class="w-24 h-24 object-contain">

                            <div class="flex flex-col space-y-2 min-h-28">
                                <flux:heading>
                                    Scroll of Defense
                                </flux:heading>

                                <flux:text size="sm">
                                    <p>+200 defense</p>
                                    <p>+200 defense</p>
                                </flux:text>

                                <flux:spacer/>

                                <flux:badge variant="pill" color="sky" size="sm" class="mt-auto w-fit">
                                    140 Credits
                                </flux:badge>
                            </div>
                        </div>
                        <div class="flex items-start gap-2">
                            <img src="{{ asset('images/catalog/buffs/5.webp') }}"
                                 class="w-24 h-24 object-contain">

                            <div class="flex flex-col space-y-2 min-h-28">
                                <flux:heading>
                                    Scroll of Defense
                                </flux:heading>

                                <flux:text size="sm">
                                    <p>+200 defense</p>
                                </flux:text>

                                <flux:spacer/>

                                <flux:badge variant="pill" color="sky" size="sm" class="mt-auto w-fit">
                                    140 Credits
                                </flux:badge>
                            </div>
                        </div>
                        <div class="flex items-start gap-2">
                            <img src="{{ asset('images/catalog/buffs/6.webp') }}"
                                 class="w-24 h-24 object-contain">

                            <div class="flex flex-col space-y-2 min-h-28">
                                <flux:heading>
                                    Scroll of Defense
                                </flux:heading>

                                <flux:text size="sm">
                                    <p>+200 defense</p>
                                </flux:text>

                                <flux:spacer/>

                                <flux:badge variant="pill" color="sky" size="sm" class="mt-auto w-fit">
                                    140 Credits
                                </flux:badge>
                            </div>
                        </div>
                        <div class="flex items-start gap-2">
                            <img src="{{ asset('images/catalog/buffs/8.webp') }}"
                                 class="w-24 h-24 object-contain">

                            <div class="flex flex-col space-y-2 min-h-28">
                                <flux:heading>
                                    Scroll of Defense
                                </flux:heading>

                                <flux:text size="sm">
                                    <p>+200 defense</p>
                                </flux:text>

                                <flux:spacer/>

                                <flux:badge variant="pill" color="sky" size="sm" class="mt-auto w-fit">
                                    140 Credits
                                </flux:badge>
                            </div>
                        </div>
                    </div>
                </flux:tab.panel>
                <flux:tab.panel name="30">
                    <div
                        class="grid max-sm:justify-self-center grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-16">
                        <div class="flex items-start gap-2">
                            <img src="{{ asset('images/catalog/buffs/1.webp') }}"
                                 class="w-24 h-24 object-contain">

                            <div class="flex flex-col space-y-2 min-h-24">
                                <flux:heading>
                                    Scroll of Defense
                                </flux:heading>

                                <flux:text size="sm">
                                    <p>+200 defense</p>
                                    <p>+200 defense</p>
                                    <p>+200 defense</p>
                                </flux:text>

                                <flux:spacer/>

                                <flux:badge variant="pill" color="sky" size="sm" class="mt-auto w-fit">
                                    300 Credits
                                </flux:badge>
                            </div>
                        </div>
                        <div class="flex items-start gap-2">
                            <img src="{{ asset('images/catalog/buffs/2.webp') }}"
                                 class="w-24 h-24 object-contain">

                            <div class="flex flex-col space-y-2 min-h-28">
                                <flux:heading>
                                    Scroll of Defense
                                </flux:heading>

                                <flux:text size="sm">
                                    <p>+200 defense</p>
                                </flux:text>

                                <flux:spacer/>

                                <flux:badge variant="pill" color="sky" size="sm" class="mt-auto w-fit">
                                    300 Credits
                                </flux:badge>
                            </div>
                        </div>
                        <div class="flex items-start gap-2">
                            <img src="{{ asset('images/catalog/buffs/3.webp') }}"
                                 class="w-24 h-24 object-contain">

                            <div class="flex flex-col space-y-2 min-h-28">
                                <flux:heading>
                                    Scroll of Defense
                                </flux:heading>

                                <flux:text size="sm">
                                    <p>+200 defense</p>
                                    <p>+200 defense</p>
                                </flux:text>

                                <flux:spacer/>

                                <flux:badge variant="pill" color="sky" size="sm" class="mt-auto w-fit">
                                    300 Credits
                                </flux:badge>
                            </div>
                        </div>
                        <div class="flex items-start gap-2">
                            <img src="{{ asset('images/catalog/buffs/5.webp') }}"
                                 class="w-24 h-24 object-contain">

                            <div class="flex flex-col space-y-2 min-h-28">
                                <flux:heading>
                                    Scroll of Defense
                                </flux:heading>

                                <flux:text size="sm">
                                    <p>+200 defense</p>
                                </flux:text>

                                <flux:spacer/>

                                <flux:badge variant="pill" color="sky" size="sm" class="mt-auto w-fit">
                                    300 Credits
                                </flux:badge>
                            </div>
                        </div>
                        <div class="flex items-start gap-2">
                            <img src="{{ asset('images/catalog/buffs/6.webp') }}"
                                 class="w-24 h-24 object-contain">

                            <div class="flex flex-col space-y-2 min-h-28">
                                <flux:heading>
                                    Scroll of Defense
                                </flux:heading>

                                <flux:text size="sm">
                                    <p>+200 defense</p>
                                </flux:text>

                                <flux:spacer/>

                                <flux:badge variant="pill" color="sky" size="sm" class="mt-auto w-fit">
                                    300 Credits
                                </flux:badge>
                            </div>
                        </div>
                        <div class="flex items-start gap-2">
                            <img src="{{ asset('images/catalog/buffs/8.webp') }}"
                                 class="w-24 h-24 object-contain">

                            <div class="flex flex-col space-y-2 min-h-28">
                                <flux:heading>
                                    Scroll of Defense
                                </flux:heading>

                                <flux:text size="sm">
                                    <p>+200 defense</p>
                                </flux:text>

                                <flux:spacer/>

                                <flux:badge variant="pill" color="sky" size="sm" class="mt-auto w-fit">
                                    300 Credits
                                </flux:badge>
                            </div>
                        </div>
                    </div>
                </flux:tab.panel>
            </flux:tab.group>

            <flux:separator class="my-16" variant="subtle"/>

            <div
                class="grid max-sm:justify-self-center grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-16">
                <div class="flex items-start gap-2">
                    <img src="{{ asset('images/catalog/buffs/bundle.gif') }}"
                         class="w-20 h-20 object-contain">

                    <div class="flex flex-col space-y-2 min-h-24">
                        <flux:heading>
                            Buff Bundle - 7 days
                        </flux:heading>
                        <flux:text size="sm">
                            <li class="list-disc ml-2">Scroll of Defense</li>
                            <li class="list-disc ml-2">Scroll of Damage</li>
                            <li class="list-disc ml-2">Scroll of Swiftness</li>
                            <li class="list-disc ml-2">Scroll of Defense</li>
                            <li class="list-disc ml-2">Scroll of Damage</li>
                            <li class="list-disc ml-2">Scroll of Swiftness</li>
                        </flux:text>

                        <flux:spacer/>

                        <flux:badge variant="pill" color="sky" size="sm" class="mt-auto w-fit">
                            700 Credits
                        </flux:badge>
                    </div>
                </div>
                <div class="flex items-start gap-2">
                    <img src="{{ asset('images/catalog/buffs/bundle.gif') }}"
                         class="w-20 h-20 object-contain">

                    <div class="flex flex-col space-y-2 min-h-24">
                        <flux:heading>
                            Buff Bundle - 7 days
                        </flux:heading>
                        <flux:text size="sm">
                            <li class="list-disc ml-2">Scroll of Defense</li>
                            <li class="list-disc ml-2">Scroll of Damage</li>
                            <li class="list-disc ml-2">Scroll of Swiftness</li>
                            <li class="list-disc ml-2">Scroll of Defense</li>
                            <li class="list-disc ml-2">Scroll of Damage</li>
                            <li class="list-disc ml-2">Scroll of Swiftness</li>
                        </flux:text>

                        <flux:spacer/>

                        <flux:badge variant="pill" color="sky" size="sm" class="mt-auto w-fit">
                            1400 Credits
                        </flux:badge>
                    </div>
                </div>
                <div class="flex items-start gap-2">
                    <img src="{{ asset('images/catalog/buffs/bundle.gif') }}"
                         class="w-20 h-20 object-contain">

                    <div class="flex flex-col space-y-2 min-h-24">
                        <flux:heading>
                            Buff Bundle - 30 days
                        </flux:heading>
                        <flux:text size="sm">
                            <li class="list-disc ml-2">Scroll of Defense</li>
                            <li class="list-disc ml-2">Scroll of Damage</li>
                            <li class="list-disc ml-2">Scroll of Swiftness</li>
                            <li class="list-disc ml-2">Scroll of Defense</li>
                            <li class="list-disc ml-2">Scroll of Damage</li>
                            <li class="list-disc ml-2">Scroll of Swiftness</li>
                        </flux:text>

                        <flux:spacer/>

                        <flux:badge variant="pill" color="sky" size="sm" class="mt-auto w-fit">
                            3000 Credits
                        </flux:badge>
                    </div>
                </div>
            </div>

            <flux:text size="sm" class="mt-12">
                All items can be found in-game within the Cash Shop.
            </flux:text>
        </flux:card>
    </section>

    <section class="isolate">
        <div class="text-center mb-12 space-y-4">
            <div class="flex justify-center">
                <div class="rounded-full bg-[color-mix(in_oklab,_var(--color-compliment),_transparent_90%)] p-3">
                    <flux:icon.swords class="h-6 w-6 text-[var(--color-compliment-content)]"/>
                </div>
            </div>

            <p class="text-[var(--color-compliment-content)] !mt-2">
                {{ __('Starter Packs') }}
            </p>

            <flux:heading size="2xl" level="2" class="max-w-3xl mx-auto">
                {{ __('Item Bundles') }}
            </flux:heading>

            <flux:subheading class="mx-auto max-w-2xl leading-8">
                {{ __('Curated starter bundles that give you exactly what you need. Nothing more.') }}
            </flux:subheading>
        </div>

        <flux:tab.group>
            <flux:tabs variant="pills" wire:model="tab" class="justify-center">
                <flux:tab name="profile" :accent="false">Profile</flux:tab>
                <flux:tab name="account" :accent="false">Account</flux:tab>
                <flux:tab name="billing" :accent="false">Billing</flux:tab>
            </flux:tabs>

            <flux:tab.panel name="profile">
                <div class="h-[20rem] w-full rounded-xl bg-zinc-300"></div>

            </flux:tab.panel>
            <flux:tab.panel name="account">
                <div class="h-[20rem] w-full rounded-xl bg-zinc-500"></div>

            </flux:tab.panel>
            <flux:tab.panel name="billing">
                <div class="h-[20rem] w-full rounded-xl bg-zinc-700"></div>
            </flux:tab.panel>
        </flux:tab.group>
    </section>

    <section class="isolate">
        <div class="text-center mb-12 space-y-4">
            <div class="flex justify-center">
                <div class="rounded-full bg-[color-mix(in_oklab,_var(--color-compliment),_transparent_90%)] p-3">
                    <flux:icon.beaker class="h-6 w-6 text-[var(--color-compliment-content)]"/>
                </div>
            </div>

            <p class="text-[var(--color-compliment-content)] !mt-2">
                {{ __('Consumables') }}
            </p>

            <flux:heading size="2xl" level="2" class="max-w-3xl mx-auto">
                {{ __('Adventure Supplies.') }}
            </flux:heading>

            <flux:subheading class="mx-auto max-w-2xl leading-8">
                {{ __('Smart conveniences and helpful utilities that make your daily adventures smoother and more enjoyable.') }}
            </flux:subheading>
        </div>

        <flux:tab.group>
            <flux:tabs variant="pills" wire:model="tab" class="justify-center">
                <flux:tab name="profile" :accent="false">Profile</flux:tab>
                <flux:tab name="account" :accent="false">Account</flux:tab>
                <flux:tab name="billing" :accent="false">Billing</flux:tab>
            </flux:tabs>

            <flux:tab.panel name="profile">
                <div class="h-[20rem] w-full rounded-xl bg-zinc-300"></div>

            </flux:tab.panel>
            <flux:tab.panel name="account">
                <div class="h-[20rem] w-full rounded-xl bg-zinc-500"></div>

            </flux:tab.panel>
            <flux:tab.panel name="billing">
                <div class="h-[20rem] w-full rounded-xl bg-zinc-700"></div>
            </flux:tab.panel>
        </flux:tab.group>
    </section>
</flux:main>
