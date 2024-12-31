<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
//
}; ?>

<main class="space-y-40">
    <!-- Hero section -->
    <section class="relative isolate overflow-hidden">
        <x-grid-pattern position="top_left" :border-top="false"/>

        {{-- Dark theme beams --}}
        <picture class="absolute inset-0 h-full w-full -z-20">
            <source
                srcset="{{ asset('/images/beams/hero-dark.avif') }}"
                type="image/avif"
                class="hidden dark:block">
            <source
                srcset="{{ asset('/images/beams/hero-dark.webp') }}"
                type="image/webp"
                class="hidden dark:block">
            <img
                src="{{ asset('/images/beams/hero-dark.png') }}"
                alt="Dark background beams"
                class="hidden dark:block h-full w-full bg-bottom bg-no-repeat [mask-image:linear-gradient(to_top,transparent_0%,white_0%)]"
                loading="eager"
                fetchpriority="low"
                decoding="async">
        </picture>

        {{-- Light theme beams --}}
        <picture class="absolute inset-0 h-full w-full -z-20">
            <source
                srcset="{{ asset('/images/beams/hero-light.avif') }}"
                type="image/avif"
                class="dark:hidden">
            <source
                srcset="{{ asset('/images/beams/hero-light.webp') }}"
                type="image/webp"
                class="dark:hidden">
            <img
                src="{{ asset('/images/beams/hero-light.jpg') }}"
                alt="Light background beams"
                class="dark:hidden h-full w-full bg-bottom bg-no-repeat [mask-image:linear-gradient(to_top,transparent_0%,white_20%)]"
                loading="eager"
                fetchpriority="low"
                decoding="async">
        </picture>

        <div class="mx-auto max-w-7xl px-6 pt-10 lg:flex lg:px-8 lg:pt-40">
            <div class="mx-auto max-w-2xl flex-shrink-0 lg:mx-0 lg:max-w-xl lg:pt-8">
                <livewire:pages.guest.home.updates-banner/>

                <flux:heading level="1"
                              class="mt-10 !font-black !text-4xl sm:!text-5xl lg:!text-6xl tracking-tight">
                    Embark on a divine conquest
                </flux:heading>
                <flux:subheading size="xl" class="mt-6">
                    Through mystical lands where legendary warriors rise and immortal battles echo across time.
                </flux:subheading>
                <div class="mt-10 flex items-center gap-x-6">
                    <flux:button variant="primary" icon="arrow-down-tray">
                        {{ __('Play for Free') }}
                    </flux:button>
                    <flux:button variant="ghost" icon-trailing="arrow-long-right"
                                 href="https://wiki.lotusmu.org" target="_blank">
                        {{ __('Learn more') }}
                    </flux:button>
                </div>
            </div>

            {{-- Dark theme hero image --}}
            <div class="hidden dark:flex w-full justify-end ml-56 sm:ml-0 mt-0 lg:-mt-24 -z-10">
                <picture>
                    <source srcset="{{ asset('images/hero/hero_dark.avif') }}" type="image/avif">
                    <source srcset="{{ asset('images/hero/hero_dark.webp') }}" type="image/webp">
                    <img src="{{ asset('images/hero/hero_dark.png') }}"
                         class="max-w-[36rem] lg:max-w-[48rem] xl:max-w-[56rem]"
                         alt="Wizard character from game Mu Online"
                         loading="eager"
                         fetchpriority="high"
                         decoding="async">
                </picture>
            </div>

            {{-- Light theme hero image --}}
            <div class="flex dark:hidden w-full justify-end ml-24 sm:ml-0 mt-0 lg:-mt-24 -z-10">
                <picture>
                    <source srcset="{{ asset('images/hero/hero_light.avif') }}" type="image/avif">
                    <source srcset="{{ asset('images/hero/hero_light.webp') }}" type="image/webp">
                    <img src="{{ asset('images/hero/hero_light.png') }}"
                         class="max-w-[36rem] lg:max-w-[48rem] xl:max-w-[56rem]"
                         alt="Elf character from game Mu Online"
                         loading="eager"
                         fetchpriority="high"
                         decoding="async">
                </picture>
            </div>
        </div>
    </section>

    <!-- News Grid Container -->
    <livewire:pages.guest.home.news-section/>

    <!-- Features -->
    <section class="relative isolate mt-32 px-6 py-32 sm:mt-56 sm:py-40 lg:px-8">
        <x-grid-pattern position="top_right"/>

        {{-- Dark theme beams --}}
        <div
            class="absolute inset-x-0 top-10 -z-10 hidden dark:flex transform-gpu justify-center overflow-hidden blur-3xl"
            aria-hidden="true">
            <div
                class="aspect-[1108/632] w-[69.25rem] flex-none bg-gradient-to-r from-[#80caff] via-[#4f46e5] to-[#ec4899] opacity-20"
                style="clip-path: polygon(73.6% 51.7%, 91.7% 11.8%, 100% 46.4%, 97.4% 82.2%, 92.5% 84.9%, 75.7% 64%, 55.3% 47.5%, 46.5% 49.4%, 45% 62.9%, 50.3% 87.2%, 21.3% 64.1%, 0.1% 100%, 5.4% 51.1%, 21.4% 63.9%, 58.9% 0.2%, 73.6% 51.7%)"
                loading="lazy"
                decoding="async">
            </div>
        </div>

        {{-- Light theme beams --}}
        <div
            class="absolute inset-x-0 top-10 -z-10 dark:hidden flex transform-gpu justify-center overflow-hidden blur-3xl"
            aria-hidden="true">
            <div
                class="aspect-[1108/632] w-[69.25rem] flex-none bg-gradient-to-r from-[#2563eb] via-[#4f46e5] to-[#e11d48] opacity-30"
                style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"
                loading="lazy"
                decoding="async">
            </div>
        </div>

        <!-- Content -->
        <div class="mx-auto max-w-3xl">
            <!-- Header Section -->
            <div class="text-center mb-12 space-y-6">
                <div class="flex justify-center">
                    <div class="rounded-full bg-blue-600/10 p-3">
                        <flux:icon.squares-plus class="h-6 w-6 text-blue-400"/>
                    </div>
                </div>

                <p class="text-blue-400 !mt-3">Features</p>

                <flux:heading level="2" class="!font-bold tracking-tight !text-3xl sm:!text-4xl">
                    Ancient powers reimagined for modern gameplay.
                </flux:heading>

                <flux:text size="lg" class="mx-auto max-w-2xl leading-8">
                    Built with balance in mind, Lotus Mu brings fresh mechanics to the classic MU Online formula.
                    Every feature is carefully crafted to enhance gameplay without compromising the core experience
                    that made the game legendary.
                </flux:text>

                <flux:link href="#" variant="ghost" class="text-blue-400 mt-6 inline-flex items-center gap-2">
                    Learn more
                    <flux:icon.arrow-right class="w-4 h-4"/>
                </flux:link>
            </div>

            <flux:tab.group>
                <flux:tabs variant="pills" class="max-sm:flex-col justify-center">
                    <flux:tab name="tab1" icon="swords">Evolving Items</flux:tab>
                    <flux:tab name="tab2" icon="scroll">Progressive Quests</flux:tab>
                    <flux:tab name="tab3" icon="sparkles">Crafting System</flux:tab>
                </flux:tabs>

                <flux:tab.panel name="tab1">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Visual Demo -->
                        <div class="bg-white/5 rounded-lg p-6 space-y-4">
                            <div class="w-80 h-8 bg-white/10 rounded"></div>
                            <div class="w-72 h-8 bg-white/10 rounded"></div>
                            <div class="w-64 h-8 bg-white/10 rounded"></div>
                            <div class="w-56 h-8 bg-white/10 rounded"></div>
                            <div class="w-48 h-8 bg-white/10 rounded"></div>
                        </div>

                        <!-- Code Preview -->
                        <flux:card class="!bg-zinc-900/50 !border-white/10 !p-4">
                            Tab 1
                        </flux:card>
                    </div>
                </flux:tab.panel>
                <flux:tab.panel name="tab2">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Visual Demo -->
                        <div class="bg-white/5 rounded-lg p-6 space-y-4">
                            <div class="w-80 h-8 bg-white/10 rounded"></div>
                            <div class="w-72 h-8 bg-white/10 rounded"></div>
                            <div class="w-64 h-8 bg-white/10 rounded"></div>
                            <div class="w-56 h-8 bg-white/10 rounded"></div>
                            <div class="w-48 h-8 bg-white/10 rounded"></div>
                        </div>

                        <!-- Code Preview -->
                        <flux:card class="!bg-zinc-900/50 !border-white/10 !p-4">
                            Tab 2
                        </flux:card>
                    </div>
                </flux:tab.panel>
                <flux:tab.panel name="tab3">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Visual Demo -->
                        <div class="bg-white/5 rounded-lg p-6 space-y-4">
                            <div class="w-80 h-8 bg-white/10 rounded"></div>
                            <div class="w-72 h-8 bg-white/10 rounded"></div>
                            <div class="w-64 h-8 bg-white/10 rounded"></div>
                            <div class="w-56 h-8 bg-white/10 rounded"></div>
                            <div class="w-48 h-8 bg-white/10 rounded"></div>
                        </div>

                        <!-- Code Preview -->
                        <flux:card class="!bg-zinc-900/50 !border-white/10 !p-4">
                            Tab 3
                        </flux:card>
                    </div>
                </flux:tab.panel>
            </flux:tab.group>
        </div>
    </section>

    <!-- CTA section -->
    <div class="relative isolate mt-32 px-6 py-32 sm:mt-56 sm:py-40 lg:px-8">
        <x-grid-pattern position="top_left"/>

        <picture class="absolute inset-0 h-full w-full -z-20">
            <source
                srcset="{{ asset('/images/beams/cta-dark.avif') }}"
                type="image/avif"
                class="hidden dark:block">
            <source
                srcset="{{ asset('/images/beams/cta-dark.webp') }}"
                type="image/webp"
                class="hidden dark:block">
            <img
                src="{{ asset('/images/beams/cta-dark.png') }}"
                alt="Dark background beams"
                class="hidden dark:block h-full w-full bg-bottom bg-no-repeat"
                loading="lazy"
                decoding="async">
        </picture>

        <picture class="absolute inset-0 h-full w-full -z-20">
            <source
                srcset="{{ asset('/images/beams/cta-light.avif') }}"
                type="image/avif"
                class="dark:hidden">
            <source
                srcset="{{ asset('/images/beams/cta-light.webp') }}"
                type="image/webp"
                class="dark:hidden">
            <img
                src="{{ asset('/images/beams/cta-light.png') }}"
                alt="Light background beams"
                class="dark:hidden h-full w-full bg-bottom bg-no-repeat [mask-image:linear-gradient(to_top,transparent_0%,white_20%)]"
                loading="lazy"
                decoding="async">
        </picture>

        <div class="mx-auto max-w-2xl text-center space-y-6">
            <flux:heading level="2" class="!font-bold tracking-tight !text-3xl sm:!text-4xl">
                Build your dream character.
            </flux:heading>

            <flux:text size="lg" class="mx-auto max-w-2xl leading-8">
                Join the Lotus Mu Community as Mu Online enters a new era of gameplay. Experience our signature
                progressive item system, rich quest system, and an innovative crafting system that rewards
                creativity.
            </flux:text>

            <div class="flex items-center justify-center gap-x-6 !mt-12">
                <flux:button variant="primary" :href="route('register')">
                    {{ __('Register Now') }}
                </flux:button>
                <flux:button variant="ghost" icon-trailing="arrow-long-right" href="#">
                    {{ __('Join Discord') }}
                </flux:button>
            </div>
        </div>
    </div>
</main>
