<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
//
}; ?>

<div>
    <main class="space-y-40">
        <!-- Hero section -->
        <div class="relative isolate overflow-hidden">
            <svg
                class="absolute inset-0 -z-10 h-full w-full stroke-black/10 dark:stroke-white/10 [mask-image:radial-gradient(75%_100%_at_top_left,white,transparent)]"
                aria-hidden="true">
                <defs>
                    <pattern id="983e3e4c-de6d-4c3f-8d64-b9761d1534cc" width="200" height="200" x="50%" y="-1"
                             patternUnits="userSpaceOnUse">
                        <path d="M.5 200V.5H200" fill="none"/>
                    </pattern>
                </defs>
                <svg x="50%" y="-1" class="overflow-visible fill-zinc-800/10 dark:fill-zinc-800/20">
                    <path d="M-200 0h201v201h-201Z M600 0h201v201h-201Z M-400 600h201v201h-201Z M200 800h201v201h-201Z"
                          stroke-width="0"/>
                </svg>
                <rect width="100%" height="100%" stroke-width="0" fill="url(#983e3e4c-de6d-4c3f-8d64-b9761d1534cc)"/>
            </svg>
            <div
                class="absolute left-[calc(50%-4rem)] top-10 -z-10 transform-gpu blur-3xl sm:left-[calc(50%-18rem)] lg:left-48 lg:top-[calc(50%-30rem)] xl:left-[calc(50%-24rem)]"
                aria-hidden="true">
                <div class="aspect-[1108/632] w-[69.25rem] bg-gradient-to-r from-[#80caff] to-[#4f46e5] opacity-20"
                     style="clip-path: polygon(73.6% 51.7%, 91.7% 11.8%, 100% 46.4%, 97.4% 82.2%, 92.5% 84.9%, 75.7% 64%, 55.3% 47.5%, 46.5% 49.4%, 45% 62.9%, 50.3% 87.2%, 21.3% 64.1%, 0.1% 100%, 5.4% 51.1%, 21.4% 63.9%, 58.9% 0.2%, 73.6% 51.7%)"></div>
            </div>
            <div class="mx-auto max-w-7xl px-6 pt-10 lg:flex lg:px-8 lg:pt-40">
                <div class="mx-auto max-w-2xl flex-shrink-0 lg:mx-0 lg:max-w-xl lg:pt-8">
                    <div class="mt-24 sm:mt-32 lg:mt-16">
                        <a href="#" class="inline-flex space-x-6">
                            <flux:badge variant="pill" color="indigo">Latest updates</flux:badge>
                            <flux:text class="flex items-center">
                                Just shipped v1.24
                                <flux:icon.chevron-right variant="mini"/>
                            </flux:text>
                        </a>
                    </div>
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
                        <flux:button variant="ghost" icon-trailing="chevron-right"
                                     href="https://wiki.lotusmu.org" target="_blank">
                            {{ __('Learn More') }}
                        </flux:button>
                    </div>
                </div>

                {{--                Dark theme hero image--}}
                <div class="hidden dark:flex w-full justify-end ml-56 sm:ml-0 mt-0 lg:-mt-24 -z-10">
                    <img src="{{ asset('images/hero/wizard_transparent.png') }}"
                         class="max-w-[36rem] lg:max-w-[48rem] xl:max-w-[56rem]">
                </div>
                {{--                Light theme hero image--}}
                <div class="flex dark:hidden w-full justify-end ml-24 sm:ml-0 mt-0 lg:-mt-24 -z-10">
                    <img src="{{ asset('images/hero/elf_transparent.png') }}"
                         class="max-w-[36rem] lg:max-w-[48rem] xl:max-w-[56rem]">
                </div>
            </div>
        </div>

        <!-- News Grid Container -->
        <div class="max-w-7xl mx-auto px-4">
            <!-- News Header -->
            <div class="flex justify-between items-center mb-8">
                <flux:heading size="xl" level="2">
                    {{ __('Latest News') }}
                </flux:heading>
                <flux:link variant="subtle" href="#" class="flex items-center gap-2">
                    {{ __('View All') }}
                    <flux:icon.arrow-right variant="mini"/>
                </flux:link>
            </div>

            <!-- News Cards Grid -->
            <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <article class="relative group">
                    <a href="#" class="absolute inset-0 z-10">
                        <span class="sr-only">Read full article about {{__('New Character Skin Release')}}</span>
                    </a>
                    <flux:card class="overflow-hidden !p-0">
                        <div class="relative overflow-hidden">
                            <img src="https://placehold.co/600x400" alt="News thumbnail"
                                 class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-105">
                            <div
                                class="absolute inset-0 bg-black opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                        </div>
                        <div class="space-y-4 p-6">
                            <flux:subheading>26/11/2024</flux:subheading>
                            <flux:heading size="lg" level="2" accent>
                                {{__('New Character Skin Release')}}
                            </flux:heading>
                            <flux:text>
                                Experience a new dimension of gameplay with our latest
                                character skin update. Unleash your potential with enhanced visuals and effects.
                            </flux:text>
                        </div>
                    </flux:card>
                </article>
                <article class="relative group">
                    <a href="#" class="absolute inset-0 z-10">
                        <span class="sr-only">Read full article about {{__('New Character Skin Release')}}</span>
                    </a>
                    <flux:card class="overflow-hidden !p-0">
                        <div class="relative overflow-hidden">
                            <img src="https://placehold.co/600x400" alt="News thumbnail"
                                 class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-105">
                            <div
                                class="absolute inset-0 bg-black opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                        </div>
                        <div class="space-y-4 p-6">
                            <flux:subheading>26/11/2024</flux:subheading>
                            <flux:heading size="lg" level="2" accent>
                                {{__('New Character Skin Release')}}
                            </flux:heading>
                            <flux:text>
                                Experience a new dimension of gameplay with our latest
                                character skin update. Unleash your potential with enhanced visuals and effects.
                            </flux:text>
                        </div>
                    </flux:card>
                </article>
                <article class="relative group">
                    <a href="#" class="absolute inset-0 z-10">
                        <span class="sr-only">Read full article about {{__('New Character Skin Release')}}</span>
                    </a>
                    <flux:card class="overflow-hidden !p-0">
                        <div class="relative overflow-hidden">
                            <img src="https://placehold.co/600x400" alt="News thumbnail"
                                 class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-105">
                            <div
                                class="absolute inset-0 bg-black opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                        </div>
                        <div class="space-y-4 p-6">
                            <flux:subheading>26/11/2024</flux:subheading>
                            <flux:heading size="lg" level="2" accent>
                                {{__('New Character Skin Release')}}
                            </flux:heading>
                            <flux:text>
                                Experience a new dimension of gameplay with our latest
                                character skin update. Unleash your potential with enhanced visuals and effects.
                            </flux:text>
                        </div>
                    </flux:card>
                </article>
            </section>
        </div>

        <!-- Features -->
        <div class="relative isolate mt-32 px-6 py-32 sm:mt-56 sm:py-40 lg:px-8">
            <svg
                class="absolute inset-0 -z-10 h-full w-full stroke-black/10 dark:stroke-white/10 [mask-image:radial-gradient(100%_100%_at_top_right,white,transparent)]"
                aria-hidden="true">
                <defs>
                    <pattern id="1d4240dd-898f-445f-932d-e2872fd12de3" width="200" height="200" x="50%" y="0"
                             patternUnits="userSpaceOnUse">
                        <path d="M.5 200V.5H200" fill="none"/>
                    </pattern>
                </defs>
                <svg x="50%" y="0" class="overflow-visible fill-zinc-800/20">
                    <path d="M-200 0h201v201h-201Z M600 0h201v201h-201Z M-400 600h201v201h-201Z M200 800h201v201h-201Z"
                          stroke-width="0"/>
                </svg>
                <rect width="100%" height="100%" stroke-width="0" fill="url(#1d4240dd-898f-445f-932d-e2872fd12de3)"/>
            </svg>
            <div class="absolute inset-x-0 top-10 -z-10 flex transform-gpu justify-center overflow-hidden blur-3xl"
                 aria-hidden="true">
                <div
                    class="aspect-[1108/632] w-[69.25rem] flex-none bg-gradient-to-r from-[#80caff] to-[#4f46e5] opacity-20"
                    style="clip-path: polygon(73.6% 51.7%, 91.7% 11.8%, 100% 46.4%, 97.4% 82.2%, 92.5% 84.9%, 75.7% 64%, 55.3% 47.5%, 46.5% 49.4%, 45% 62.9%, 50.3% 87.2%, 21.3% 64.1%, 0.1% 100%, 5.4% 51.1%, 21.4% 63.9%, 58.9% 0.2%, 73.6% 51.7%)">
                </div>
            </div>

            <!-- Content -->
            <div class="mx-auto max-w-3xl">
                <!-- Header Section -->
                <div class="text-center mb-16">
                    <div class="flex justify-center mb-6">
                        <div class="rounded-full bg-blue-600/10 p-3">
                            <flux:icon.squares-plus class="h-6 w-6 text-blue-400"/>
                        </div>
                    </div>
                    <p class="text-blue-400 mb-3">Features</p>
                    <flux:heading size="xl" level="2" class="text-white mb-6">
                        Ancient powers reimagined for modern gameplay.
                    </flux:heading>
                    <flux:text class="max-w-2xl mx-auto text-zinc-400">
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
                    <flux:tabs class="!border-0 justify-center">
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
        </div>

        <!-- Logo cloud -->
        <div class="mx-auto mt-8 max-w-7xl px-6 sm:mt-16 lg:px-8">
            <h2 class="text-center text-lg font-semibold leading-8 text-white">The world’s most innovative companies use
                our app</h2>
            <div
                class="mx-auto mt-10 grid max-w-lg grid-cols-4 items-center gap-x-8 gap-y-10 sm:max-w-xl sm:grid-cols-6 sm:gap-x-10 lg:mx-0 lg:max-w-none lg:grid-cols-5">
                <img class="col-span-2 max-h-12 w-full object-contain lg:col-span-1"
                     src="https://tailwindui.com/img/logos/158x48/transistor-logo-white.svg" alt="Transistor"
                     width="158" height="48">
                <img class="col-span-2 max-h-12 w-full object-contain lg:col-span-1"
                     src="https://tailwindui.com/img/logos/158x48/reform-logo-white.svg" alt="Reform" width="158"
                     height="48">
                <img class="col-span-2 max-h-12 w-full object-contain lg:col-span-1"
                     src="https://tailwindui.com/img/logos/158x48/tuple-logo-white.svg" alt="Tuple" width="158"
                     height="48">
                <img class="col-span-2 max-h-12 w-full object-contain sm:col-start-2 lg:col-span-1"
                     src="https://tailwindui.com/img/logos/158x48/savvycal-logo-white.svg" alt="SavvyCal" width="158"
                     height="48">
                <img class="col-span-2 col-start-2 max-h-12 w-full object-contain sm:col-start-auto lg:col-span-1"
                     src="https://tailwindui.com/img/logos/158x48/statamic-logo-white.svg" alt="Statamic" width="158"
                     height="48">
            </div>
        </div>

        <!-- Feature section -->
        <div class="mx-auto mt-32 max-w-7xl px-6 sm:mt-56 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <h2 class="text-base font-semibold leading-7 text-indigo-400">Deploy faster</h2>
                <p class="mt-2 text-3xl font-bold tracking-tight text-white sm:text-4xl">Everything you need to deploy
                    your app</p>
                <p class="mt-6 text-lg leading-8 text-zinc-300">Lorem ipsum dolor sit amet consect adipisicing elit.
                    Possimus magnam voluptatum cupiditate veritatis in accusamus quisquam.</p>
            </div>
            <div class="mx-auto mt-16 max-w-2xl sm:mt-20 lg:mt-24 lg:max-w-none">
                <dl class="grid max-w-xl grid-cols-1 gap-x-8 gap-y-16 lg:max-w-none lg:grid-cols-3">
                    <div class="flex flex-col">
                        <dt class="text-base font-semibold leading-7 text-white">
                            <div class="mb-6 flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-500">

                            </div>
                            Server monitoring
                        </dt>
                        <dd class="mt-1 flex flex-auto flex-col text-base leading-7 text-zinc-300">
                            <p class="flex-auto">Non quo aperiam repellendus quas est est. Eos aut dolore aut ut sit
                                nesciunt. Ex tempora quia. Sit nobis consequatur dolores incidunt.</p>
                            <p class="mt-6">
                                <a href="#" class="text-sm font-semibold leading-6 text-indigo-400">Learn more <span
                                        aria-hidden="true">→</span></a>
                            </p>
                        </dd>
                    </div>
                    <div class="flex flex-col">
                        <dt class="text-base font-semibold leading-7 text-white">
                            <div class="mb-6 flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-500">

                            </div>
                            Collaborate
                        </dt>
                        <dd class="mt-1 flex flex-auto flex-col text-base leading-7 text-zinc-300">
                            <p class="flex-auto">Vero eum voluptatem aliquid nostrum voluptatem. Vitae esse natus. Earum
                                nihil deserunt eos quasi cupiditate. A inventore et molestiae natus.</p>
                            <p class="mt-6">
                                <a href="#" class="text-sm font-semibold leading-6 text-indigo-400">Learn more <span
                                        aria-hidden="true">→</span></a>
                            </p>
                        </dd>
                    </div>
                    <div class="flex flex-col">
                        <dt class="text-base font-semibold leading-7 text-white">
                            <div class="mb-6 flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-500">

                            </div>
                            Task scheduling
                        </dt>
                        <dd class="mt-1 flex flex-auto flex-col text-base leading-7 text-zinc-300">
                            <p class="flex-auto">Et quod quaerat dolorem quaerat architecto aliquam accusantium. Ex
                                adipisci et doloremque autem quia quam. Quis eos molestiae at iure impedit.</p>
                            <p class="mt-6">
                                <a href="#" class="text-sm font-semibold leading-6 text-indigo-400">Learn more <span
                                        aria-hidden="true">→</span></a>
                            </p>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Feature section -->
        <div class="mt-32 sm:mt-56">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="mx-auto max-w-2xl sm:text-center">
                    <h2 class="text-base font-semibold leading-7 text-indigo-400">Everything you need</h2>
                    <p class="mt-2 text-3xl font-bold tracking-tight text-white sm:text-4xl">No server? No problem.</p>
                    <p class="mt-6 text-lg leading-8 text-zinc-300">Lorem ipsum, dolor sit amet consectetur adipisicing
                        elit. Maiores impedit perferendis suscipit eaque, iste dolor cupiditate blanditiis.</p>
                </div>
            </div>
            <div class="relative overflow-hidden pt-16">
                <div class="mx-auto max-w-7xl px-6 lg:px-8">
                    <img src="https://tailwindui.com/img/component-images/dark-project-app-screenshot.png"
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
                        <dt class="inline font-semibold text-white">
                            <svg class="absolute left-1 top-1 h-5 w-5 text-indigo-500" viewBox="0 0 20 20"
                                 fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                      d="M5.5 17a4.5 4.5 0 01-1.44-8.765 4.5 4.5 0 018.302-3.046 3.5 3.5 0 014.504 4.272A4 4 0 0115 17H5.5zm3.75-2.75a.75.75 0 001.5 0V9.66l1.95 2.1a.75.75 0 101.1-1.02l-3.25-3.5a.75.75 0 00-1.1 0l-3.25 3.5a.75.75 0 101.1 1.02l1.95-2.1v4.59z"
                                      clip-rule="evenodd"/>
                            </svg>
                            Push to deploy.
                        </dt>
                        <dd class="inline">Lorem ipsum, dolor sit amet consectetur adipisicing elit aute id magna.</dd>
                    </div>
                    <div class="relative pl-9">
                        <dt class="inline font-semibold text-white">
                            <svg class="absolute left-1 top-1 h-5 w-5 text-indigo-500" viewBox="0 0 20 20"
                                 fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                      d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z"
                                      clip-rule="evenodd"/>
                            </svg>
                            SSL certificates.
                        </dt>
                        <dd class="inline">Anim aute id magna aliqua ad ad non deserunt sunt. Qui irure qui lorem
                            cupidatat commodo.
                        </dd>
                    </div>
                    <div class="relative pl-9">
                        <dt class="inline font-semibold text-white">
                            <svg class="absolute left-1 top-1 h-5 w-5 text-indigo-500" viewBox="0 0 20 20"
                                 fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                      d="M15.312 11.424a5.5 5.5 0 01-9.201 2.466l-.312-.311h2.433a.75.75 0 000-1.5H3.989a.75.75 0 00-.75.75v4.242a.75.75 0 001.5 0v-2.43l.31.31a7 7 0 0011.712-3.138.75.75 0 00-1.449-.39zm1.23-3.723a.75.75 0 00.219-.53V2.929a.75.75 0 00-1.5 0V5.36l-.31-.31A7 7 0 003.239 8.188a.75.75 0 101.448.389A5.5 5.5 0 0113.89 6.11l.311.31h-2.432a.75.75 0 000 1.5h4.243a.75.75 0 00.53-.219z"
                                      clip-rule="evenodd"/>
                            </svg>
                            Simple queues.
                        </dt>
                        <dd class="inline">Ac tincidunt sapien vehicula erat auctor pellentesque rhoncus.</dd>
                    </div>
                    <div class="relative pl-9">
                        <dt class="inline font-semibold text-white">
                            <svg class="absolute left-1 top-1 h-5 w-5 text-indigo-500" viewBox="0 0 20 20"
                                 fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                      d="M10 2.5c-1.31 0-2.526.386-3.546 1.051a.75.75 0 01-.82-1.256A8 8 0 0118 9a22.47 22.47 0 01-1.228 7.351.75.75 0 11-1.417-.49A20.97 20.97 0 0016.5 9 6.5 6.5 0 0010 2.5zM4.333 4.416a.75.75 0 01.218 1.038A6.466 6.466 0 003.5 9a7.966 7.966 0 01-1.293 4.362.75.75 0 01-1.257-.819A6.466 6.466 0 002 9c0-1.61.476-3.11 1.295-4.365a.75.75 0 011.038-.219zM10 6.12a3 3 0 00-3.001 3.041 11.455 11.455 0 01-2.697 7.24.75.75 0 01-1.148-.965A9.957 9.957 0 005.5 9c0-.028.002-.055.004-.082a4.5 4.5 0 018.996.084V9.15l-.005.297a.75.75 0 11-1.5-.034c.003-.11.004-.219.005-.328a3 3 0 00-3-2.965zm0 2.13a.75.75 0 01.75.75c0 3.51-1.187 6.745-3.181 9.323a.75.75 0 11-1.186-.918A13.687 13.687 0 009.25 9a.75.75 0 01.75-.75zm3.529 3.698a.75.75 0 01.584.885 18.883 18.883 0 01-2.257 5.84.75.75 0 11-1.29-.764 17.386 17.386 0 002.078-5.377.75.75 0 01.885-.584z"
                                      clip-rule="evenodd"/>
                            </svg>
                            Advanced security.
                        </dt>
                        <dd class="inline">Lorem ipsum, dolor sit amet consectetur adipisicing elit aute id magna.</dd>
                    </div>
                    <div class="relative pl-9">
                        <dt class="inline font-semibold text-white">
                            <svg class="absolute left-1 top-1 h-5 w-5 text-indigo-500" viewBox="0 0 20 20"
                                 fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                      d="M7.84 1.804A1 1 0 018.82 1h2.36a1 1 0 01.98.804l.331 1.652a6.993 6.993 0 011.929 1.115l1.598-.54a1 1 0 011.186.447l1.18 2.044a1 1 0 01-.205 1.251l-1.267 1.113a7.047 7.047 0 010 2.228l1.267 1.113a1 1 0 01.206 1.25l-1.18 2.045a1 1 0 01-1.187.447l-1.598-.54a6.993 6.993 0 01-1.929 1.115l-.33 1.652a1 1 0 01-.98.804H8.82a1 1 0 01-.98-.804l-.331-1.652a6.993 6.993 0 01-1.929-1.115l-1.598.54a1 1 0 01-1.186-.447l-1.18-2.044a1 1 0 01.205-1.251l1.267-1.114a7.05 7.05 0 010-2.227L1.821 7.773a1 1 0 01-.206-1.25l1.18-2.045a1 1 0 011.187-.447l1.598.54A6.993 6.993 0 017.51 3.456l.33-1.652zM10 13a3 3 0 100-6 3 3 0 000 6z"
                                      clip-rule="evenodd"/>
                            </svg>
                            Powerful API.
                        </dt>
                        <dd class="inline">Anim aute id magna aliqua ad ad non deserunt sunt. Qui irure qui lorem
                            cupidatat commodo.
                        </dd>
                    </div>
                    <div class="relative pl-9">
                        <dt class="inline font-semibold text-white">
                            <svg class="absolute left-1 top-1 h-5 w-5 text-indigo-500" viewBox="0 0 20 20"
                                 fill="currentColor" aria-hidden="true">
                                <path
                                    d="M4.632 3.533A2 2 0 016.577 2h6.846a2 2 0 011.945 1.533l1.976 8.234A3.489 3.489 0 0016 11.5H4c-.476 0-.93.095-1.344.267l1.976-8.234z"/>
                                <path fill-rule="evenodd"
                                      d="M4 13a2 2 0 100 4h12a2 2 0 100-4H4zm11.24 2a.75.75 0 01.75-.75H16a.75.75 0 01.75.75v.01a.75.75 0 01-.75.75h-.01a.75.75 0 01-.75-.75V15zm-2.25-.75a.75.75 0 00-.75.75v.01c0 .414.336.75.75.75H13a.75.75 0 00.75-.75V15a.75.75 0 00-.75-.75h-.01z"
                                      clip-rule="evenodd"/>
                            </svg>
                            Database backups.
                        </dt>
                        <dd class="inline">Ac tincidunt sapien vehicula erat auctor pellentesque rhoncus.</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Stats -->
        <div class="mx-auto mt-32 max-w-7xl px-6 sm:mt-56 lg:px-8">
            <div class="mx-auto max-w-2xl lg:mx-0 lg:max-w-xl">
                <h2 class="text-base font-semibold leading-8 text-indigo-400">Our track record</h2>
                <p class="mt-2 text-3xl font-bold tracking-tight text-white sm:text-4xl">Trusted by thousands of
                    developers&nbsp;worldwide</p>
                <p class="mt-6 text-lg leading-8 text-zinc-300">Lorem ipsum, dolor sit amet consectetur adipisicing
                    elit. Maiores impedit perferendis suscipit eaque, iste dolor cupiditate blanditiis ratione.</p>
            </div>
            <dl class="mx-auto mt-16 grid max-w-2xl grid-cols-1 gap-x-8 gap-y-10 text-white sm:mt-20 sm:grid-cols-2 sm:gap-y-16 lg:mx-0 lg:max-w-none lg:grid-cols-4">
                <div class="flex flex-col gap-y-3 border-l border-white/10 pl-6">
                    <dt class="text-sm leading-6">Developers on the platform</dt>
                    <dd class="order-first text-3xl font-semibold tracking-tight">8,000+</dd>
                </div>
                <div class="flex flex-col gap-y-3 border-l border-white/10 pl-6">
                    <dt class="text-sm leading-6">Daily requests</dt>
                    <dd class="order-first text-3xl font-semibold tracking-tight">900m+</dd>
                </div>
                <div class="flex flex-col gap-y-3 border-l border-white/10 pl-6">
                    <dt class="text-sm leading-6">Uptime guarantee</dt>
                    <dd class="order-first text-3xl font-semibold tracking-tight">99.9%</dd>
                </div>
                <div class="flex flex-col gap-y-3 border-l border-white/10 pl-6">
                    <dt class="text-sm leading-6">Projects deployed</dt>
                    <dd class="order-first text-3xl font-semibold tracking-tight">12m</dd>
                </div>
            </dl>
        </div>

        <!-- CTA section -->
        <div class="relative isolate mt-32 px-6 py-32 sm:mt-56 sm:py-40 lg:px-8">
            <svg
                class="absolute inset-0 -z-10 h-full w-full stroke-white/10 [mask-image:radial-gradient(100%_100%_at_top_right,white,transparent)]"
                aria-hidden="true">
                <defs>
                    <pattern id="1d4240dd-898f-445f-932d-e2872fd12de3" width="200" height="200" x="50%" y="0"
                             patternUnits="userSpaceOnUse">
                        <path d="M.5 200V.5H200" fill="none"/>
                    </pattern>
                </defs>
                <svg x="50%" y="0" class="overflow-visible fill-zinc-800/20">
                    <path d="M-200 0h201v201h-201Z M600 0h201v201h-201Z M-400 600h201v201h-201Z M200 800h201v201h-201Z"
                          stroke-width="0"/>
                </svg>
                <rect width="100%" height="100%" stroke-width="0" fill="url(#1d4240dd-898f-445f-932d-e2872fd12de3)"/>
            </svg>
            <div class="absolute inset-x-0 top-10 -z-10 flex transform-gpu justify-center overflow-hidden blur-3xl"
                 aria-hidden="true">
                <div
                    class="aspect-[1108/632] w-[69.25rem] flex-none bg-gradient-to-r from-[#80caff] to-[#4f46e5] opacity-20"
                    style="clip-path: polygon(73.6% 51.7%, 91.7% 11.8%, 100% 46.4%, 97.4% 82.2%, 92.5% 84.9%, 75.7% 64%, 55.3% 47.5%, 46.5% 49.4%, 45% 62.9%, 50.3% 87.2%, 21.3% 64.1%, 0.1% 100%, 5.4% 51.1%, 21.4% 63.9%, 58.9% 0.2%, 73.6% 51.7%)"></div>
            </div>
            <div class="mx-auto max-w-2xl text-center">
                <h2 class="text-3xl font-bold tracking-tight text-white sm:text-4xl">Boost your productivity.<br>Start
                    using our app today.</h2>
                <p class="mx-auto mt-6 max-w-xl text-lg leading-8 text-zinc-300">Incididunt sint fugiat pariatur
                    cupidatat consectetur sit cillum anim id veniam aliqua proident excepteur commodo do ea.</p>
                <div class="mt-10 flex items-center justify-center gap-x-6">
                    <a href="#"
                       class="rounded-md bg-white px-3.5 py-2.5 text-sm font-semibold text-zinc-900 shadow-sm hover:bg-zinc-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">Get
                        started</a>
                    <a href="#" class="text-sm font-semibold leading-6 text-white">Learn more <span
                            aria-hidden="true">→</span></a>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer aria-labelledby="footer-heading" class="relative">
        <h2 id="footer-heading" class="sr-only">Footer</h2>
        <div class="mx-auto max-w-7xl px-6 pb-8 pt-4 lg:px-8">
            <div class="border-t border-white/10 pt-8 md:flex md:items-center md:justify-between">
                <div class="flex space-x-6 md:order-2">
                    <a href="#" class="text-zinc-500 hover:text-zinc-400">
                        <span class="sr-only">Facebook</span>
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd"
                                  d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"
                                  clip-rule="evenodd"/>
                        </svg>
                    </a>
                    <a href="#" class="text-zinc-500 hover:text-zinc-400">
                        <span class="sr-only">Instagram</span>
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd"
                                  d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z"
                                  clip-rule="evenodd"/>
                        </svg>
                    </a>
                    <a href="#" class="text-zinc-500 hover:text-zinc-400">
                        <span class="sr-only">Twitter</span>
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path
                                d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"/>
                        </svg>
                    </a>
                    <a href="#" class="text-zinc-500 hover:text-zinc-400">
                        <span class="sr-only">GitHub</span>
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd"
                                  d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"
                                  clip-rule="evenodd"/>
                        </svg>
                    </a>
                    <a href="#" class="text-zinc-500 hover:text-zinc-400">
                        <span class="sr-only">YouTube</span>
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd"
                                  d="M19.812 5.418c.861.23 1.538.907 1.768 1.768C21.998 8.746 22 12 22 12s0 3.255-.418 4.814a2.504 2.504 0 0 1-1.768 1.768c-1.56.419-7.814.419-7.814.419s-6.255 0-7.814-.419a2.505 2.505 0 0 1-1.768-1.768C2 15.255 2 12 2 12s0-3.255.417-4.814a2.507 2.507 0 0 1 1.768-1.768C5.744 5 11.998 5 11.998 5s6.255 0 7.814.418ZM15.194 12 10 15V9l5.194 3Z"
                                  clip-rule="evenodd"/>
                        </svg>
                    </a>
                </div>
                <p class="mt-8 text-xs leading-5 text-zinc-400 md:order-1 md:mt-0">&copy; 2020 Your Company, Inc. All
                    rights reserved.</p>
            </div>
        </div>
    </footer>
</div>
