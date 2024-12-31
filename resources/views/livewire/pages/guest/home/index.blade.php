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

    <div class="mt-32 sm:mt-56">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-2xl sm:text-center">
                <h2 class="text-base font-semibold leading-7 text-blue-400">Everything you need</h2>
                <flux:heading class="mt-2 !text-3xl font-bold tracking-tight  sm:!text-4xl">No server? No
                    problem.
                </flux:heading>
                <flux:text class="mt-6 !text-lg leading-8 text-zinc-300">Lorem ipsum, dolor sit amet consectetur
                    adipisicing
                    elit. Maiores impedit perferendis suscipit eaque, iste dolor cupiditate blanditiis.
                </flux:text>
            </div>
        </div>
        <div class="relative overflow-hidden pt-16">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <img
                    src="https://tailwindcss.com/_next/image?url=%2F_next%2Fstatic%2Fmedia%2F1.d88bdbd2.png&w=1920&q=90"
                    alt="App screenshot" class="mb-[-12%] rounded-xl shadow-2xl ring-1 ring-white/10" width="2432"
                    height="1442">
                <div class="relative" aria-hidden="true">
                    <div class="absolute -inset-x-20 bottom-0 bg-gradient-to-t from-zinc-900 pt-[7%]"></div>
                </div>
            </div>
        </div>
        <div class="mx-auto mt-16 max-w-7xl px-6 sm:mt-20 md:mt-24 lg:px-8">
            <dl class="mx-auto grid max-w-2xl grid-cols-1 gap-x-6 gap-y-10 text-base leading-7 text-zinc-300 sm:grid-cols-2 lg:mx-0 lg:max-w-none lg:grid-cols-3 lg:gap-x-8 lg:gap-y-16">
                <div class="relative pl-9">
                    <flux:subheading class="inline font-semibold">
                        <svg class="absolute left-1 top-1 h-5 w-5 text-blue-500" viewBox="0 0 20 20"
                             fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                  d="M5.5 17a4.5 4.5 0 01-1.44-8.765 4.5 4.5 0 018.302-3.046 3.5 3.5 0 014.504 4.272A4 4 0 0115 17H5.5zm3.75-2.75a.75.75 0 001.5 0V9.66l1.95 2.1a.75.75 0 101.1-1.02l-3.25-3.5a.75.75 0 00-1.1 0l-3.25 3.5a.75.75 0 101.1 1.02l1.95-2.1v4.59z"
                                  clip-rule="evenodd"/>
                        </svg>
                        Push to deploy.
                    </flux:subheading>
                    <flux:text class="inline">Lorem ipsum, dolor sit amet consectetur adipisicing elit aute id magna.
                    </flux:text>
                </div>
                <div class="relative pl-9">
                    <flux:subheading class="inline font-semibold">
                        <svg class="absolute left-1 top-1 h-5 w-5 text-blue-500" viewBox="0 0 20 20"
                             fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                  d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z"
                                  clip-rule="evenodd"/>
                        </svg>
                        SSL certificates.
                    </flux:subheading>
                    <flux:text class="inline">Anim aute id magna aliqua ad ad non deserunt sunt. Qui irure qui lorem
                        cupidatat commodo.
                    </flux:text>
                </div>
                <div class="relative pl-9">
                    <flux:subheading class="inline font-semibold">
                        <svg class="absolute left-1 top-1 h-5 w-5 text-blue-500" viewBox="0 0 20 20"
                             fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                  d="M15.312 11.424a5.5 5.5 0 01-9.201 2.466l-.312-.311h2.433a.75.75 0 000-1.5H3.989a.75.75 0 00-.75.75v4.242a.75.75 0 001.5 0v-2.43l.31.31a7 7 0 0011.712-3.138.75.75 0 00-1.449-.39zm1.23-3.723a.75.75 0 00.219-.53V2.929a.75.75 0 00-1.5 0V5.36l-.31-.31A7 7 0 003.239 8.188a.75.75 0 101.448.389A5.5 5.5 0 0113.89 6.11l.311.31h-2.432a.75.75 0 000 1.5h4.243a.75.75 0 00.53-.219z"
                                  clip-rule="evenodd"/>
                        </svg>
                        Simple queues.
                    </flux:subheading>
                    <flux:text class="inline">Ac tincidunt sapien vehicula erat auctor pellentesque rhoncus.</flux:text>
                </div>
                <div class="relative pl-9">
                    <flux:subheading class="inline font-semibold">
                        <svg class="absolute left-1 top-1 h-5 w-5 text-blue-500" viewBox="0 0 20 20"
                             fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                  d="M10 2.5c-1.31 0-2.526.386-3.546 1.051a.75.75 0 01-.82-1.256A8 8 0 0118 9a22.47 22.47 0 01-1.228 7.351.75.75 0 11-1.417-.49A20.97 20.97 0 0016.5 9 6.5 6.5 0 0010 2.5zM4.333 4.416a.75.75 0 01.218 1.038A6.466 6.466 0 003.5 9a7.966 7.966 0 01-1.293 4.362.75.75 0 01-1.257-.819A6.466 6.466 0 002 9c0-1.61.476-3.11 1.295-4.365a.75.75 0 011.038-.219zM10 6.12a3 3 0 00-3.001 3.041 11.455 11.455 0 01-2.697 7.24.75.75 0 01-1.148-.965A9.957 9.957 0 005.5 9c0-.028.002-.055.004-.082a4.5 4.5 0 018.996.084V9.15l-.005.297a.75.75 0 11-1.5-.034c.003-.11.004-.219.005-.328a3 3 0 00-3-2.965zm0 2.13a.75.75 0 01.75.75c0 3.51-1.187 6.745-3.181 9.323a.75.75 0 11-1.186-.918A13.687 13.687 0 009.25 9a.75.75 0 01.75-.75zm3.529 3.698a.75.75 0 01.584.885 18.883 18.883 0 01-2.257 5.84.75.75 0 11-1.29-.764 17.386 17.386 0 002.078-5.377.75.75 0 01.885-.584z"
                                  clip-rule="evenodd"/>
                        </svg>
                        Advanced security.
                    </flux:subheading>
                    <flux:text class="inline">Lorem ipsum, dolor sit amet consectetur adipisicing elit aute id magna.
                    </flux:text>
                </div>
                <div class="relative pl-9">
                    <flux:subheading class="inline font-semibold">
                        <svg class="absolute left-1 top-1 h-5 w-5 text-blue-500" viewBox="0 0 20 20"
                             fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                  d="M7.84 1.804A1 1 0 018.82 1h2.36a1 1 0 01.98.804l.331 1.652a6.993 6.993 0 011.929 1.115l1.598-.54a1 1 0 011.186.447l1.18 2.044a1 1 0 01-.205 1.251l-1.267 1.113a7.047 7.047 0 010 2.228l1.267 1.113a1 1 0 01.206 1.25l-1.18 2.045a1 1 0 01-1.187.447l-1.598-.54a6.993 6.993 0 01-1.929 1.115l-.33 1.652a1 1 0 01-.98.804H8.82a1 1 0 01-.98-.804l-.331-1.652a6.993 6.993 0 01-1.929-1.115l-1.598.54a1 1 0 01-1.186-.447l-1.18-2.044a1 1 0 01.205-1.251l1.267-1.114a7.05 7.05 0 010-2.227L1.821 7.773a1 1 0 01-.206-1.25l1.18-2.045a1 1 0 011.187-.447l1.598.54A6.993 6.993 0 017.51 3.456l.33-1.652zM10 13a3 3 0 100-6 3 3 0 000 6z"
                                  clip-rule="evenodd"/>
                        </svg>
                        Powerful API.
                    </flux:subheading>
                    <flux:text class="inline">Anim aute id magna aliqua ad ad non deserunt sunt. Qui irure qui lorem
                        cupidatat commodo.
                    </flux:text>
                </div>
                <div class="relative pl-9">
                    <flux:subheading class="inline font-semibold">
                        <svg class="absolute left-1 top-1 h-5 w-5 text-blue-500" viewBox="0 0 20 20"
                             fill="currentColor" aria-hidden="true">
                            <path
                                d="M4.632 3.533A2 2 0 016.577 2h6.846a2 2 0 011.945 1.533l1.976 8.234A3.489 3.489 0 0016 11.5H4c-.476 0-.93.095-1.344.267l1.976-8.234z"/>
                            <path fill-rule="evenodd"
                                  d="M4 13a2 2 0 100 4h12a2 2 0 100-4H4zm11.24 2a.75.75 0 01.75-.75H16a.75.75 0 01.75.75v.01a.75.75 0 01-.75.75h-.01a.75.75 0 01-.75-.75V15zm-2.25-.75a.75.75 0 00-.75.75v.01c0 .414.336.75.75.75H13a.75.75 0 00.75-.75V15a.75.75 0 00-.75-.75h-.01z"
                                  clip-rule="evenodd"/>
                        </svg>
                        Database backups.
                    </flux:subheading>
                    <flux:text class="inline">Ac tincidunt sapien vehicula erat auctor pellentesque rhoncus.</flux:text>
                </div>
            </dl>
        </div>
    </div>

    <!-- CTA section -->
    <section class="relative isolate mt-32 px-6 py-32 sm:mt-56 sm:py-40 lg:px-8">
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
    </section>
</main>
