<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
//
}; ?>

<div class="space-y-40">
    <!-- Hero section -->
    <section class="relative isolate overflow-hidden">
        <x-grid-pattern position="top_left" :border-top="false"/>


        <div class="mx-auto max-w-7xl px-6 pt-10 lg:flex lg:px-8 lg:pt-40">
            <div class="mx-auto max-w-2xl flex-shrink-0 lg:mx-0 lg:max-w-xl lg:pt-8">
                <livewire:pages.guest.home.updates-banner/>

                <flux:heading level="1" size="3xl" class="mt-10">
                    {{ __('Embark on a divine conquest') }}
                </flux:heading>

                <flux:subheading size="xl" class="mt-6">
                    {{ __('Through mystical lands where legendary warriors rise and immortal battles echo across time.') }}
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
                    <div class="rounded-full bg-[color-mix(in_oklab,_var(--color-compliment),_transparent_90%)] p-3">
                        <flux:icon.squares-plus class="h-6 w-6 text-[var(--color-compliment-content)]"/>
                    </div>
                </div>

                <p class="text-[var(--color-compliment-content)] !mt-3">Features</p>

                <flux:heading size="2xl" level="2">
                    Ancient powers reimagined for modern gameplay.
                </flux:heading>

                <flux:text size="lg" class="mx-auto max-w-2xl leading-8">
                    Built with balance in mind, Lotus Mu brings fresh mechanics to the classic MU Online formula.
                    Every feature is carefully crafted to enhance gameplay without compromising the core experience
                    that made the game legendary.
                </flux:text>

                <flux:link href="https://wiki.lotusmu.org" variant="ghost" :accent="false" external
                           class="!text-[var(--color-compliment-content)] mt-6 inline-flex items-center gap-2">
                    {{ __('Learn more') }}
                    <flux:icon.arrow-long-right variant="micro"/>
                </flux:link>
            </div>

            <flux:tab.group>
                <flux:tabs variant="pills" class="max-sm:flex-col justify-center">
                    <flux:tab name="tab1" :accent="false" icon="swords">Evolving Items</flux:tab>
                    <flux:tab name="tab2" :accent="false" icon="scroll">Progressive Quests</flux:tab>
                    <flux:tab name="tab3" :accent="false" icon="sparkles">Crafting System</flux:tab>
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

    {{--    MORE FEATURES SECTION--}}
    <section id="modern-features">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <div
                class="w-16 h-16 p-[0.1875rem] rounded-full ring-1 ring-slate-900/10 shadow overflow-hidden dark:bg-indigo-500 dark:highlight-white/20">
                <div class="aspect-w-1 aspect-h-1 bg-[length:100%] dark:hidden"
                     style="background-image: url(&quot;/_next/static/media/modern-features.0c791977.png&quot;);"></div>
                <div class="hidden aspect-w-1 aspect-h-1 bg-[length:100%] dark:block"
                     style="background-image: url(&quot;/_next/static/media/modern-features.9af738b6.png&quot;);"></div>
            </div>
            <h2 class="mt-8 font-semibold text-indigo-500 dark:text-indigo-400">Modern features</h2>
            <p class="mt-4 text-3xl sm:text-4xl text-slate-900 font-extrabold tracking-tight dark:text-slate-50 ">
                Cutting-edge is our comfort&nbsp;zone.</p>
            <div class="mt-4 max-w-3xl space-y-6 "><p>Tailwind is unapologetically modern, and takes advantage of all
                    the latest and greatest CSS features to make the developer experience as enjoyable as possible.</p>
                <p>We've got first-class CSS grid support, composable transforms and gradients powered by CSS variables,
                    support for modern state selectors like <code
                        class="font-mono text-slate-900 font-medium dark:text-slate-200 ">:focus-visible</code>, and
                    tons more.</p></div>
            <a class="group inline-flex items-center h-9 rounded-full text-sm font-semibold whitespace-nowrap px-3 focus:outline-none focus:ring-2 bg-indigo-50 text-indigo-600 hover:bg-indigo-200 hover:text-indigo-700 focus:ring-indigo-500 dark:bg-slate-700 dark:text-slate-100 dark:hover:bg-slate-600 dark:hover:text-white dark:focus:ring-slate-500 mt-8"
               href="/docs/grid-template-columns">Learn more<span class="sr-only">, grid template columns</span>
                <svg
                    class="overflow-visible ml-3 text-indigo-300 group-hover:text-indigo-400 dark:text-slate-500 dark:group-hover:text-slate-400"
                    width="3" height="6" viewBox="0 0 3 6" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M0 0L3 3L0 6"></path>
                </svg>
            </a>
            <div class="mt-10">
                <div class="flex overflow-auto -mx-4 sm:mx-0">
                    <ul class="flex-none inline-grid gap-x-2 px-4 sm:px-0 xl:gap-x-6"
                        style="grid-template-columns: repeat(3, minmax(6rem, 1fr));">
                        <li>
                            <button type="button"
                                    class="group text-sm font-semibold w-full flex flex-col items-center text-indigo-600 dark:text-indigo-400">
                                <svg width="48" height="48" fill="none" aria-hidden="true"
                                     class="mb-6 text-indigo-500 dark:text-indigo-400">
                                    <path
                                        d="M5 13a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v6a3 3 0 0 1-3 3H8a3 3 0 0 1-3-3v-6ZM5 29a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v6a3 3 0 0 1-3 3H8a3 3 0 0 1-3-3v-6ZM19 29a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v6a3 3 0 0 1-3 3h-4a3 3 0 0 1-3-3v-6ZM33 29a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v6a3 3 0 0 1-3 3h-4a3 3 0 0 1-3-3v-6ZM19 13a3 3 0 0 1 3-3h18a3 3 0 0 1 3 3v6a3 3 0 0 1-3 3H22a3 3 0 0 1-3-3v-6Z"
                                        fill="currentColor" fill-opacity=".1" stroke="currentColor"
                                        stroke-width="2"></path>
                                </svg>
                                CSS Grid
                            </button>
                        </li>
                        <li>
                            <button type="button"
                                    class="group text-sm font-semibold w-full flex flex-col items-center ">
                                <svg width="48" height="48" fill="none" aria-hidden="true"
                                     class="mb-6 text-slate-300 group-hover:text-slate-400 dark:text-slate-600 dark:group-hover:text-slate-500">
                                    <path
                                        d="M5.632 11.725a3 3 0 0 1 2.554-3.388l3.96-.557a3 3 0 0 1 3.389 2.554l.835 5.941a3 3 0 0 1-2.553 3.388l-3.961.557a3 3 0 0 1-3.389-2.553l-.835-5.942ZM1 29a3 3 0 0 1 3-3h8a3 3 0 0 1 3 3v6a3 3 0 0 1-3 3H4a3 3 0 0 1-3-3v-6ZM20 34a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v6a3 3 0 0 1-3 3h-4a3 3 0 0 1-3-3v-6ZM36.728 27.026a3 3 0 0 1 3.558-2.31l3.913.831a3 3 0 0 1 2.31 3.558l-1.247 5.87a3 3 0 0 1-3.558 2.31l-3.913-.832a3 3 0 0 1-2.31-3.558l1.247-5.869ZM22.236 9.17a3 3 0 0 1 3.202-2.783l17.956 1.255a3 3 0 0 1 2.784 3.202l-.419 5.986a3 3 0 0 1-3.202 2.783l-17.956-1.255a3 3 0 0 1-2.784-3.202l.419-5.986Z"
                                        fill="currentColor" fill-opacity="0" stroke="currentColor"
                                        stroke-width="2"></path>
                                </svg>
                                Transforms
                            </button>
                        </li>
                        <li>
                            <button type="button"
                                    class="group text-sm font-semibold w-full flex flex-col items-center ">
                                <svg width="48" height="48" fill="none" aria-hidden="true"
                                     class="mb-6 text-slate-300 group-hover:text-slate-400 dark:text-slate-600 dark:group-hover:text-slate-500">
                                    <path
                                        d="M31 30c0-7.18-5.82-13-13-13m-5.009 1C8.298 19.961 5 24.596 5 30c0 7.18 5.82 13 13 13 5.404 0 10.039-3.298 12-7.991"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"></path>
                                    <circle cx="30" cy="18" r="13" fill="currentColor" fill-opacity="0"
                                            stroke="currentColor" stroke-width="2"></circle>
                                    <path d="m26 30 4-4M21 27l6-6M18 22l4-4" stroke="currentColor" stroke-width="2"
                                          stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                Filters
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="relative pt-10 xl:pt-0 mt-10 xl:mt-2">
            <div
                class="hidden dark:block absolute top-0 inset-x-0 h-[37.5rem] bg-gradient-to-b from-[#0c1120] top-0 xl:top-8"></div>
            <div class="absolute top-0 inset-x-0 bg-top bg-no-repeat GridLockup_beams-0___8Vns top-0 xl:top-8"></div>
            <div
                class="absolute top-0 inset-x-0 h-[37.5rem] bg-grid-slate-900/[0.04] bg-top [mask-image:linear-gradient(0deg,transparent,black)] dark:bg-grid-slate-100/[0.03] dark:bg-[center_top_-1px] dark:border-t dark:border-slate-100/5 top-0 xl:top-8"></div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8 lg:grid lg:grid-cols-12 lg:gap-8">
                <div class="lg:col-span-5 xl:col-span-6 flex flex-col">
                    <div class="flex text-4xl font-black lg:mt-10 xl:mt-18">
                        <div class="w-full flex-none grid grid-cols-3 grid-rows-2 gap-8">
                            <div style="transform: none;">
                                <div
                                    class="relative pt-full bg-white rounded-lg shadow-lg overflow-hidden transition-[filter] duration-500">
                                    <img src="/_next/static/media/1.4985e539.jpg" alt=""
                                         class="absolute inset-0 w-full h-full object-cover" decoding="async"
                                         loading="lazy"></div>
                            </div>
                            <div class="col-start-3 col-end-4 row-start-2 row-end-3" style="transform: none;">
                                <div
                                    class="relative pt-full bg-white rounded-lg shadow-lg overflow-hidden transition-[filter] duration-500">
                                    <img src="/_next/static/media/2.86c4c41e.jpg" alt=""
                                         class="absolute inset-0 w-full h-full object-cover" decoding="async"
                                         loading="lazy"></div>
                            </div>
                            <div class="origin-right" style="transform: none;">
                                <div
                                    class="relative pt-full bg-white rounded-lg shadow-lg overflow-hidden transition-[filter] duration-500">
                                    <img src="/_next/static/media/3.19759950.jpg" alt=""
                                         class="absolute inset-0 w-full h-full object-cover" decoding="async"
                                         loading="lazy"></div>
                            </div>
                            <div style="transform: none;">
                                <div
                                    class="relative pt-full bg-white rounded-lg shadow-lg overflow-hidden transition-[filter] duration-500">
                                    <img src="/_next/static/media/4.a2ed7a78.jpg" alt=""
                                         class="absolute inset-0 w-full h-full object-cover" decoding="async"
                                         loading="lazy"></div>
                            </div>
                            <div
                                class="relative bg-white rounded-lg shadow-lg overflow-hidden col-start-2 col-end-4 row-start-1 row-end-2 transition-[filter] duration-500"
                                style="opacity: 1; transform: none;"><img src="/_next/static/media/5.e8ff4aa4.jpg"
                                                                          alt=""
                                                                          class="absolute inset-0 w-full h-full object-cover"
                                                                          decoding="async" loading="lazy"></div>
                        </div>
                    </div>
                </div>
                <div class="mt-4 -mx-4 sm:mx-0 lg:mt-0 lg:col-span-7 xl:col-span-6">
                    <div
                        class="relative overflow-hidden shadow-xl flex bg-slate-800 h-[31.625rem] max-h-[60vh] sm:max-h-[none] sm:rounded-xl lg:h-[34.6875rem] xl:h-[31.625rem] dark:bg-slate-900/70 dark:backdrop-blur dark:ring-1 dark:ring-inset dark:ring-white/10">
                        <div class="relative w-full flex flex-col">
                            <div class="flex-none border-b border-slate-500/30">
                                <div class="flex items-center h-8 space-x-1.5 px-3">
                                    <div class="w-2.5 h-2.5 bg-slate-600 rounded-full"></div>
                                    <div class="w-2.5 h-2.5 bg-slate-600 rounded-full"></div>
                                    <div class="w-2.5 h-2.5 bg-slate-600 rounded-full"></div>
                                </div>
                            </div>
                            <div class="relative min-h-0 flex-auto flex flex-col">
                                <div class="w-full flex-auto flex min-h-0" style="opacity: 1;">
                                    <div class="w-full flex-auto flex min-h-0 overflow-auto">
                                        <div class="w-full relative flex-auto"><pre
                                                class="flex min-h-full text-sm leading-6"><div aria-hidden="true"
                                                                                               class="hidden md:block text-slate-600 flex-none py-4 pr-4 text-right select-none w-[3.125rem]">1<br>2<br>3<br>4<br>5<br>6<br>7<br>8<br>9<br>10<br>11<br>12<br>13<br>14<br>15<br>16<br>17<br>18</div><code
                                                    class="flex-auto relative block text-slate-50 overflow-auto p-4"><span
                                                        class="tag punctuation token">&lt;</span><span
                                                        class="tag token">div</span><span
                                                        class="tag token"> </span><span class="tag attr-name token">class</span><span
                                                        class="tag attr-value punctuation attr-equals token">=</span><span
                                                        class="tag attr-value punctuation token">"</span><span
                                                        class="tag attr-value token">grid grid-flow-col grid-rows-2 grid-cols-3 gap-8</span><span
                                                        class="tag attr-value punctuation token">"</span><span
                                                        class="tag punctuation token">&gt;</span><span></span>
<span>  </span><span class="tag punctuation token">&lt;</span><span class="tag token">div</span><span
                                                        class="tag punctuation token">&gt;</span><span></span>
<span>    </span><span class="tag punctuation token">&lt;</span><span class="tag token">img</span><span
                                                        class="tag token"> </span><span
                                                        class="tag attr-name token">src</span><span
                                                        class="tag attr-value punctuation attr-equals token">=</span><span
                                                        class="tag attr-value punctuation token">"</span><span
                                                        class="tag attr-value token">/mountains-1.jpg</span><span
                                                        class="tag attr-value punctuation token">"</span><span
                                                        class="tag token"> </span><span
                                                        class="tag attr-name token">alt</span><span
                                                        class="tag attr-value punctuation attr-equals token">=</span><span
                                                        class="tag attr-value punctuation token">"</span><span
                                                        class="tag attr-value punctuation token">"</span><span
                                                        class="tag token"> </span><span class="tag attr-name token">loading</span><span
                                                        class="tag attr-value punctuation attr-equals token">=</span><span
                                                        class="tag attr-value punctuation token">"</span><span
                                                        class="tag attr-value token">lazy</span><span
                                                        class="tag attr-value punctuation token">"</span><span
                                                        class="tag punctuation token">&gt;</span><span></span>
<span>  </span><span class="tag punctuation token">&lt;/</span><span class="tag token">div</span><span
                                                        class="tag punctuation token">&gt;</span><span></span>
<span>  </span><span class="tag punctuation token">&lt;</span><span class="tag token">div</span><span
                                                        class="tag token"> </span><span class="tag attr-name token">class</span><span
                                                        class="tag attr-value punctuation attr-equals token">=</span><span
                                                        class="tag attr-value punctuation token">"</span><span
                                                        class="tag attr-value token">col-start-3</span><span
                                                        class="tag attr-value punctuation token">"</span><span
                                                        class="tag punctuation token">&gt;</span><span></span>
<span>    </span><span class="tag punctuation token">&lt;</span><span class="tag token">img</span><span
                                                        class="tag token"> </span><span
                                                        class="tag attr-name token">src</span><span
                                                        class="tag attr-value punctuation attr-equals token">=</span><span
                                                        class="tag attr-value punctuation token">"</span><span
                                                        class="tag attr-value token">/mountains-2.jpg</span><span
                                                        class="tag attr-value punctuation token">"</span><span
                                                        class="tag token"> </span><span
                                                        class="tag attr-name token">alt</span><span
                                                        class="tag attr-value punctuation attr-equals token">=</span><span
                                                        class="tag attr-value punctuation token">"</span><span
                                                        class="tag attr-value punctuation token">"</span><span
                                                        class="tag token"> </span><span class="tag attr-name token">loading</span><span
                                                        class="tag attr-value punctuation attr-equals token">=</span><span
                                                        class="tag attr-value punctuation token">"</span><span
                                                        class="tag attr-value token">lazy</span><span
                                                        class="tag attr-value punctuation token">"</span><span
                                                        class="tag punctuation token">&gt;</span><span></span>
<span>  </span><span class="tag punctuation token">&lt;/</span><span class="tag token">div</span><span
                                                        class="tag punctuation token">&gt;</span><span></span>
<span>  </span><span class="tag punctuation token">&lt;</span><span class="tag token">div</span><span
                                                        class="tag punctuation token">&gt;</span><span></span>
<span>    </span><span class="tag punctuation token">&lt;</span><span class="tag token">img</span><span
                                                        class="tag token"> </span><span
                                                        class="tag attr-name token">src</span><span
                                                        class="tag attr-value punctuation attr-equals token">=</span><span
                                                        class="tag attr-value punctuation token">"</span><span
                                                        class="tag attr-value token">/mountains-3.jpg</span><span
                                                        class="tag attr-value punctuation token">"</span><span
                                                        class="tag token"> </span><span
                                                        class="tag attr-name token">alt</span><span
                                                        class="tag attr-value punctuation attr-equals token">=</span><span
                                                        class="tag attr-value punctuation token">"</span><span
                                                        class="tag attr-value punctuation token">"</span><span
                                                        class="tag token"> </span><span class="tag attr-name token">loading</span><span
                                                        class="tag attr-value punctuation attr-equals token">=</span><span
                                                        class="tag attr-value punctuation token">"</span><span
                                                        class="tag attr-value token">lazy</span><span
                                                        class="tag attr-value punctuation token">"</span><span
                                                        class="tag punctuation token">&gt;</span><span></span>
<span>  </span><span class="tag punctuation token">&lt;/</span><span class="tag token">div</span><span
                                                        class="tag punctuation token">&gt;</span><span></span>
<span>  </span><span class="tag punctuation token">&lt;</span><span class="tag token">div</span><span
                                                        class="tag punctuation token">&gt;</span><span></span>
<span>    </span><span class="tag punctuation token">&lt;</span><span class="tag token">img</span><span
                                                        class="tag token"> </span><span
                                                        class="tag attr-name token">src</span><span
                                                        class="tag attr-value punctuation attr-equals token">=</span><span
                                                        class="tag attr-value punctuation token">"</span><span
                                                        class="tag attr-value token">/mountains-4.jpg</span><span
                                                        class="tag attr-value punctuation token">"</span><span
                                                        class="tag token"> </span><span
                                                        class="tag attr-name token">alt</span><span
                                                        class="tag attr-value punctuation attr-equals token">=</span><span
                                                        class="tag attr-value punctuation token">"</span><span
                                                        class="tag attr-value punctuation token">"</span><span
                                                        class="tag token"> </span><span class="tag attr-name token">loading</span><span
                                                        class="tag attr-value punctuation attr-equals token">=</span><span
                                                        class="tag attr-value punctuation token">"</span><span
                                                        class="tag attr-value token">lazy</span><span
                                                        class="tag attr-value punctuation token">"</span><span
                                                        class="tag punctuation token">&gt;</span><span></span>
<span>  </span><span class="tag punctuation token">&lt;/</span><span class="tag token">div</span><span
                                                        class="tag punctuation token">&gt;</span><span></span>
<span>  </span><span class="tag punctuation token">&lt;</span><span class="tag token">div</span><span
                                                        class="tag token"> </span><span class="tag attr-name token">class</span><span
                                                        class="tag attr-value punctuation attr-equals token">=</span><span
                                                        class="tag attr-value punctuation token">"</span><span
                                                        class="tag attr-value token">row-start-1 col-start-2 col-span-2</span><span
                                                        class="tag attr-value punctuation token">"</span><span
                                                        class="tag punctuation token">&gt;</span><span></span>
<span>    </span><span class="tag punctuation token">&lt;</span><span class="tag token">img</span><span
                                                        class="tag token"> </span><span
                                                        class="tag attr-name token">src</span><span
                                                        class="tag attr-value punctuation attr-equals token">=</span><span
                                                        class="tag attr-value punctuation token">"</span><span
                                                        class="tag attr-value token">/mountains-5.jpg</span><span
                                                        class="tag attr-value punctuation token">"</span><span
                                                        class="tag token"> </span><span
                                                        class="tag attr-name token">alt</span><span
                                                        class="tag attr-value punctuation attr-equals token">=</span><span
                                                        class="tag attr-value punctuation token">"</span><span
                                                        class="tag attr-value punctuation token">"</span><span
                                                        class="tag token"> </span><span class="tag attr-name token">loading</span><span
                                                        class="tag attr-value punctuation attr-equals token">=</span><span
                                                        class="tag attr-value punctuation token">"</span><span
                                                        class="tag attr-value token">lazy</span><span
                                                        class="tag attr-value punctuation token">"</span><span
                                                        class="tag punctuation token">&gt;</span><span></span>
<span>  </span><span class="tag punctuation token">&lt;/</span><span class="tag token">div</span><span
                                                        class="tag punctuation token">&gt;</span><span></span>
<span></span><span class="tag punctuation token">&lt;/</span><span class="tag token">div</span><span
                                                        class="tag punctuation token">&gt;</span><span></span>
<span class="inline-block"></span>
</code></pre>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Catalog -->
    <section class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="flex items-start w-full">
            {{--        texts--}}
            <div class="space-y-6 max-w-xl mt-12 ">
                <div class="flex justify-start">
                    <div
                        class="rounded-full bg-[color-mix(in_oklab,_var(--color-compliment),_transparent_90%)] p-3">
                        <flux:icon.building-storefront class="h-6 w-6 text-[var(--color-compliment-content)]"/>
                    </div>
                </div>

                <p class="text-[var(--color-compliment-content)] !mt-3">Catalog</p>

                <flux:heading size="2xl" level="2">
                    Your path, your possibilities.
                </flux:heading>

                <flux:text size="lg" class="mx-auto leading-8">
                    Explore our selection of convenience features and enhancements designed to complement your gameplay
                    experience. See detailed information about everything available in the game.
                </flux:text>

                <flux:link href="#" variant="ghost" :accent="false"
                           class="!text-[var(--color-compliment-content)] mt-6 inline-flex items-center gap-2">
                    {{ __('Browse Offerings') }}
                    <flux:icon.arrow-long-right variant="micro"/>
                </flux:link>
            </div>

            {{--        image--}}
            <div>
                <img
                    src="{{ asset('images/shop.png') }}"
                    alt="App screenshot">
            </div>
        </div>
    </section>


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
                class="hidden dark:block h-full w-full bg-bottom bg-no-repeat max-xl:object-cover"
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
                class="dark:hidden h-full w-full bg-bottom bg-no-repeat [mask-image:linear-gradient(to_top,transparent_0%,white_20%)] max-lg:object-cover"
                loading="lazy"
                decoding="async">
        </picture>

        <div class="mx-auto max-w-2xl text-center space-y-6">
            <flux:heading level="2" size="2xl">
                {{ __('Build your dream character.') }}
            </flux:heading>

            <flux:text size="lg" class="mx-auto max-w-2xl leading-8">
                {{ __('Join the Lotus Mu Community as Mu Online enters a new era of gameplay. Experience our signature
                progressive item system, rich quest system, and an innovative crafting system that rewards
                creativity.') }}
            </flux:text>

            <div class="flex items-center justify-center gap-x-6 !mt-12">
                <flux:button variant="primary" :href="route('register')">
                    {{ __('Register Now') }}
                </flux:button>
                <flux:button variant="ghost" icon-trailing="arrow-long-right"
                             href="{{ config('social.links.discord') }}" target="_blank">
                    {{ __('Join Discord') }}
                </flux:button>
            </div>
        </div>
    </section>
</div>
