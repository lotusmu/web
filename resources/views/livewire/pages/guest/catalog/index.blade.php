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
    <x-page-hero
        title="Your path, your possibilities"
        kicker="Catalog"
        description="Explore our selection of convenience features and enhancements designed to complement your gameplay experience. See detailed information about everything available in the game."
    />

    <livewire:pages.guest.catalog.vip.list/>

    <!-- Buffs -->
    <livewire:pages.guest.catalog.buffs lazy/>

    <!-- Item Bundles -->
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
            <flux:tabs variant="pills" wire:model="tab" class="flex overflow-auto sm:mx-0 sm:justify-center">
                <flux:tab name="{{ CharacterClass::DarkKnight->value }}"
                          :accent="false">
                    {{ CharacterClass::DarkKnight->getLabel() }}
                </flux:tab>
                <flux:tab name="{{ CharacterClass::DarkWizard->value }}"
                          :accent="false">
                    {{ CharacterClass::DarkWizard->getLabel() }}
                </flux:tab>
                <flux:tab name="{{ CharacterClass::FairyElf->value }}"
                          :accent="false">
                    {{ CharacterClass::FairyElf->getLabel() }}
                </flux:tab>
                <flux:tab name="{{ CharacterClass::MagicGladiator->value }}"
                          :accent="false">
                    {{ CharacterClass::MagicGladiator->getLabel() }}
                </flux:tab>
                <flux:tab name="{{ CharacterClass::DarkLord->value }}"
                          :accent="false">
                    {{ CharacterClass::DarkLord->getLabel() }}
                </flux:tab>
            </flux:tabs>

            <!-- DK -->
            <flux:tab.panel name="{{ CharacterClass::DarkKnight->value }}">
                <div class="flex items-center justify-center max-xl:flex-col gap-8 w-full">
                    <flux:card class="flex w-full max-md:flex-col max-md:items-center max-md:space-y-8">
                        <figure
                            class="overflow-hidden max-w-xs w-full text-center">
                            <img
                                src="{{ asset('images/catalog/items/bundles/knight/brass.webp') }}"
                                class="max-w-[65%] mx-auto p-8 rounded-tl-xl  rounded-tr-xl border-t-[3px] border-r-[3px] border-l-[3px] border-zinc-200 dark:border-white/30"
                            />

                            <figcaption>
                                <flux:heading level="3" size="xl"
                                              class="w-full py-1.5 uppercase tracking-widest !font-light">
                                    Dark <span class="font-black">Knight</span>
                                </flux:heading>

                                <flux:text
                                    class="max-w-[65%] mx-auto py-1.5 uppercase rounded-br-xl rounded-bl-xl border-b-[3px] border-r-[3px] border-l-[3px] border-zinc-200 dark:border-white/30">
                                    Tier 1
                                </flux:text>
                            </figcaption>
                        </figure>

                        <div class="flex flex-col space-y-6 w-full">
                            <flux:heading level="3" size="lg">
                                Bundle Contents
                            </flux:heading>

                            <div class="space-y-2">
                                <flux:card class="flex items-center gap-2 py-2">
                                    <flux:icon.sword variant="mini"/>
                                    <flux:text>
                                        {{ __('Light Spear') }}
                                    </flux:text>
                                </flux:card>

                                <flux:card class="flex items-center gap-2 py-2">
                                    <flux:icon.shield variant="mini"/>
                                    <flux:text>
                                        {{ __('Brass Set') }}
                                    </flux:text>
                                </flux:card>

                            </div>

                            <flux:separator variant="subtle"/>

                            <div class="space-y-2">
                                <flux:subheading size="sm">
                                    Equipment Options:
                                </flux:subheading>
                                <div class="flex items-center flex-wrap whitespace-nowrap gap-2">
                                    <flux:badge size="sm" icon="plus-circle" color="yellow">
                                        7
                                    </flux:badge>
                                    <flux:badge size="sm" icon="chevron-double-up" color="blue">
                                        Additional +4
                                    </flux:badge>
                                    <flux:badge size="sm" icon="star" color="blue">
                                        Luck
                                    </flux:badge>
                                    <flux:badge size="sm" icon="sword" color="blue">
                                        Weapon Skill
                                    </flux:badge>
                                </div>
                            </div>
                            <flux:spacer/>
                            <div>
                                <flux:badge variant="pill" size="sm" color="teal">
                                    400 credits
                                </flux:badge>
                            </div>
                        </div>
                    </flux:card>

                    <flux:card class="flex w-full max-md:flex-col max-md:items-center max-md:space-y-8">
                        <figure
                            class="overflow-hidden max-w-xs w-full text-center">
                            <img
                                src="{{ asset('images/catalog/items/bundles/knight/brass.webp') }}"
                                class="max-w-[65%] mx-auto p-8 rounded-tl-xl  rounded-tr-xl border-t-[3px] border-r-[3px] border-l-[3px] border-zinc-200 dark:border-white/30"
                            />

                            <figcaption>
                                <flux:heading level="3" size="xl"
                                              class="w-full py-1.5 uppercase tracking-widest !font-light">
                                    Fairy <span class="font-black">Elf</span>
                                </flux:heading>

                                <flux:text
                                    class="max-w-[65%] mx-auto py-1.5 uppercase rounded-bl-xl rounded-br-xl border-b-[3px] border-r-[3px] border-l-[3px] border-zinc-200 dark:border-white/30">
                                    Tier 1
                                </flux:text>
                            </figcaption>
                        </figure>

                        <div class="flex flex-col space-y-6 w-full">
                            <flux:heading level="3" size="lg">
                                Bundle Contents
                            </flux:heading>

                            <div class="space-y-2">
                                <flux:card class="flex items-center gap-2 py-2">
                                    <flux:icon.sword variant="mini"/>
                                    <flux:text>
                                        {{ __('Light Spear') }}
                                    </flux:text>
                                </flux:card>

                                <flux:card class="flex items-center gap-2 py-2">
                                    <flux:icon.shield variant="mini"/>
                                    <flux:text>
                                        {{ __('Brass Set') }}
                                    </flux:text>
                                </flux:card>

                            </div>

                            <flux:separator variant="subtle"/>

                            <div class="space-y-2">
                                <flux:subheading size="sm">
                                    Equipment Options:
                                </flux:subheading>
                                <div class="flex items-center flex-wrap whitespace-nowrap gap-2">
                                    <flux:badge size="sm" icon="plus-circle" color="yellow">
                                        7
                                    </flux:badge>
                                    <flux:badge size="sm" icon="chevron-double-up" color="blue">
                                        Additional +4
                                    </flux:badge>
                                    <flux:badge size="sm" icon="star" color="blue">
                                        Luck
                                    </flux:badge>
                                    <flux:badge size="sm" icon="sword" color="blue">
                                        Weapon Skill
                                    </flux:badge>
                                </div>
                            </div>
                            <flux:spacer/>
                            <div>
                                <flux:badge variant="pill" size="sm" color="teal">
                                    400 credits
                                </flux:badge>
                            </div>
                        </div>
                    </flux:card>
                </div>

                <flux:text size="sm" class="mt-12 text-center">
                    All items can be found in-game within the Cash Shop.
                </flux:text>
            </flux:tab.panel>

            <!-- Dw -->
            <flux:tab.panel name="{{ CharacterClass::DarkWizard->value }}">
                <flux:card>

                </flux:card>
            </flux:tab.panel>

            <!-- FE -->
            <flux:tab.panel name="{{ CharacterClass::FairyElf->value }}">
                <flux:card>

                </flux:card>
            </flux:tab.panel>

            <!-- MG -->
            <flux:tab.panel name="{{ CharacterClass::MagicGladiator->value }}">
                <flux:card>

                </flux:card>
            </flux:tab.panel>

            <!-- DL -->
            <flux:tab.panel name="{{ CharacterClass::DarkLord->value }}">
                <flux:card>

                </flux:card>
            </flux:tab.panel>
        </flux:tab.group>
    </section>

    <!-- Consumables -->
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

        <flux:card>
            <div
                class="grid max-sm:justify-self-center grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-16">
                <div class="flex items-start gap-2">
                    <img src="{{ asset('images/catalog/tickets/blood-castle.webp') }}"
                         class="w-8 h-8 object-contain">

                    <div class="flex flex-col space-y-2 min-h-24">
                        <flux:heading>
                            Blood Castle Ticket
                        </flux:heading>

                        <flux:text size="sm">
                            <p>
                                Entrance ticket which allows your participation in Blood Castle.
                            </p>
                            <p>
                                5 charges per ticket
                            </p>
                        </flux:text>

                        <flux:spacer/>

                        <flux:badge variant="pill" color="teal" size="sm" class="mt-auto w-fit">
                            70 Credits
                        </flux:badge>
                    </div>
                </div>
                <div class="flex items-start gap-2">
                    <img src="{{ asset('images/catalog/tickets/magic-backpack.webp') }}"
                         class="w-8 h-8 object-contain">

                    <div class="flex flex-col space-y-2 min-h-28">
                        <flux:heading>
                            Magic Backpack
                        </flux:heading>

                        <flux:text size="sm">
                            <p>
                                This item expands your inventory.
                            </p>
                        </flux:text>

                        <flux:spacer/>

                        <flux:badge variant="pill" color="teal" size="sm" class="mt-auto w-fit">
                            70 Credits
                        </flux:badge>
                    </div>
                </div>
                <div class="flex items-start gap-2">
                    <img src="{{ asset('images/catalog/tickets/devil-square.webp') }}"
                         class="w-8 h-8 object-contain">

                    <div class="flex flex-col space-y-2 min-h-28">
                        <flux:heading>
                            Devil Square Ticket
                        </flux:heading>

                        <flux:text size="sm">
                            <p>
                                Entrance ticket which allows your participation in Blood Castle.
                            </p>
                            <p>
                                5 charges per ticket
                            </p></flux:text>

                        <flux:spacer/>

                        <flux:badge variant="pill" color="teal" size="sm" class="mt-auto w-fit">
                            70 Credits
                        </flux:badge>
                    </div>
                </div>
                <div class="flex items-start gap-2">
                    <img src="{{ asset('images/catalog/tickets/magic-backpack.webp') }}"
                         class="w-8 h-8 object-contain">

                    <div class="flex flex-col space-y-2 min-h-28">
                        <flux:heading>
                            Magic Backpack
                        </flux:heading>

                        <flux:text size="sm">
                            <p>
                                This item expands your inventory.
                            </p>
                        </flux:text>

                        <flux:spacer/>

                        <flux:badge variant="pill" color="teal" size="sm" class="mt-auto w-fit">
                            70 Credits
                        </flux:badge>
                    </div>
                </div>
                <div class="flex items-start gap-2">
                    <img src="{{ asset('images/catalog/tickets/talisman-of-chaos-assembly.webp') }}"
                         class="w-8 h-8 object-contain">

                    <div class="flex flex-col space-y-2 min-h-28">
                        <flux:heading>
                            Talisman of Chaos Assembly
                        </flux:heading>

                        <flux:text size="sm">
                            <p>
                                Prevents items from disappearing in failing Chaos Combination.
                            </p>
                        </flux:text>

                        <flux:spacer/>

                        <flux:badge variant="pill" color="teal" size="sm" class="mt-auto w-fit">
                            70 Credits
                        </flux:badge>
                    </div>
                </div>
                <div class="flex items-start gap-2">
                    <img src="{{ asset('images/catalog/tickets/blood-castle.webp') }}"
                         class="w-8 h-8 object-contain">

                    <div class="flex flex-col space-y-2 min-h-24">
                        <flux:heading>
                            Blood Castle Ticket
                        </flux:heading>

                        <flux:text size="sm">
                            <p>
                                Entrance ticket which allows your participation in Blood Castle.
                            </p>
                            <p>
                                5 charges per ticket
                            </p>
                        </flux:text>

                        <flux:spacer/>

                        <flux:badge variant="pill" color="teal" size="sm" class="mt-auto w-fit">
                            70 Credits
                        </flux:badge>
                    </div>
                </div>
                <div class="flex items-start gap-2">
                    <img src="{{ asset('images/catalog/tickets/devil-square.webp') }}"
                         class="w-8 h-8 object-contain">

                    <div class="flex flex-col space-y-2 min-h-28">
                        <flux:heading>
                            Devil Square Ticket
                        </flux:heading>

                        <flux:text size="sm">
                            <p>
                                Entrance ticket which allows your participation in Blood Castle.
                            </p>
                            <p>
                                5 charges per ticket
                            </p></flux:text>

                        <flux:spacer/>

                        <flux:badge variant="pill" color="teal" size="sm" class="mt-auto w-fit">
                            70 Credits
                        </flux:badge>
                    </div>
                </div>
                <div class="flex items-start gap-2">
                    <img src="{{ asset('images/catalog/tickets/devil-square.webp') }}"
                         class="w-8 h-8 object-contain">

                    <div class="flex flex-col space-y-2 min-h-28">
                        <flux:heading>
                            Devil Square Ticket
                        </flux:heading>

                        <flux:text size="sm">
                            <p>
                                Entrance ticket which allows your participation in Blood Castle.
                            </p>
                            <p>
                                5 charges per ticket
                            </p></flux:text>

                        <flux:spacer/>

                        <flux:badge variant="pill" color="teal" size="sm" class="mt-auto w-fit">
                            70 Credits
                        </flux:badge>
                    </div>
                </div>
                <div class="flex items-start gap-2">
                    <img src="{{ asset('images/catalog/tickets/magic-backpack.webp') }}"
                         class="w-8 h-8 object-contain">

                    <div class="flex flex-col space-y-2 min-h-28">
                        <flux:heading>
                            Magic Backpack
                        </flux:heading>

                        <flux:text size="sm">
                            <p>
                                This item expands your inventory.
                            </p>
                        </flux:text>

                        <flux:spacer/>

                        <flux:badge variant="pill" color="teal" size="sm" class="mt-auto w-fit">
                            70 Credits
                        </flux:badge>
                    </div>
                </div>
                <div class="flex items-start gap-2">
                    <img src="{{ asset('images/catalog/tickets/talisman-of-chaos-assembly.webp') }}"
                         class="w-8 h-8 object-contain">

                    <div class="flex flex-col space-y-2 min-h-28">
                        <flux:heading>
                            Talisman of Chaos Assembly
                        </flux:heading>

                        <flux:text size="sm">
                            <p>
                                Prevents items from disappearing in failing Chaos Combination.
                            </p>
                        </flux:text>

                        <flux:spacer/>

                        <flux:badge variant="pill" color="teal" size="sm" class="mt-auto w-fit">
                            70 Credits
                        </flux:badge>
                    </div>
                </div>
                <div class="flex items-start gap-2">
                    <img src="{{ asset('images/catalog/tickets/blood-castle.webp') }}"
                         class="w-8 h-8 object-contain">

                    <div class="flex flex-col space-y-2 min-h-24">
                        <flux:heading>
                            Blood Castle Ticket
                        </flux:heading>

                        <flux:text size="sm">
                            <p>
                                Entrance ticket which allows your participation in Blood Castle.
                            </p>
                            <p>
                                5 charges per ticket
                            </p>
                        </flux:text>

                        <flux:spacer/>

                        <flux:badge variant="pill" color="teal" size="sm" class="mt-auto w-fit">
                            70 Credits
                        </flux:badge>
                    </div>
                </div>
                <div class="flex items-start gap-2">
                    <img src="{{ asset('images/catalog/tickets/talisman-of-chaos-assembly.webp') }}"
                         class="w-8 h-8 object-contain">

                    <div class="flex flex-col space-y-2 min-h-28">
                        <flux:heading>
                            Talisman of Chaos Assembly
                        </flux:heading>

                        <flux:text size="sm">
                            <p>
                                Prevents items from disappearing in failing Chaos Combination.
                            </p>
                        </flux:text>

                        <flux:spacer/>

                        <flux:badge variant="pill" color="teal" size="sm" class="mt-auto w-fit">
                            70 Credits
                        </flux:badge>
                    </div>
                </div>
            </div>


            <flux:text size="sm" class="mt-12">
                All items can be found in-game within the Cash Shop.
            </flux:text>
        </flux:card>
    </section>
</flux:main>
