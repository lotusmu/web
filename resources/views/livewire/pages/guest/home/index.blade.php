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

    <!-- More Features TO BE DONE -->
    <section id="modern-features">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <div
                class="w-16 h-16 p-[0.1875rem] rounded-full ring-1 ring-zinc-900/10 shadow overflow-hidden dark:bg-indigo-500 dark:highlight-white/20">
            </div>
            <h2 class="mt-8 font-semibold text-indigo-500 dark:text-indigo-400">Modern features</h2>
            <p class="mt-4 text-3xl sm:text-4xl text-zinc-900 font-extrabold tracking-tight dark:text-zinc-50 ">
                Cutting-edge is our comfort&nbsp;zone.
            </p>
            <div class="mt-4 max-w-3xl space-y-6">
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Delectus deserunt dolorem fuga maxime
                    nihil non quam quos! Aspernatur deserunt magni neque, odio quos rerum tenetur vitae! Consectetur
                    dolor nesciunt omnis?
                </p>
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Animi, commodi consequuntur fugiat minima
                    natus officia optio quo. Neque omnis, reiciendis!
                </p>
            </div>

            <flux:button icon-trailing="arrow-long-right" class="mt-6">
                Learn More
            </flux:button>

            <div class="mt-10">
                <div class="flex overflow-auto -mx-4 sm:mx-0">
                    <flux:tab.group>
                        <flux:tabs variant="pills" class="max-sm:flex-col justify-center">
                            <flux:tab name="tab1" :accent="false" icon="swords">Evolving Items</flux:tab>
                            <flux:tab name="tab2" :accent="false" icon="scroll">Progressive Quests</flux:tab>
                            <flux:tab name="tab3" :accent="false" icon="sparkles">Crafting System</flux:tab>
                        </flux:tabs>
                    </flux:tab.group>
                </div>
            </div>
        </div>
        
        <div class="relative pt-10 xl:pt-0 mt-10 xl:mt-2">
            <x-grid-pattern position="top_left" class="mt-8"/>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8 lg:grid lg:grid-cols-12 lg:gap-8">
                <div class="lg:col-span-5 xl:col-span-6 flex flex-col">
                    <div class="flex text-4xl font-black lg:mt-10 xl:mt-18">
                        <flux:text>
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Amet architecto aut deserunt
                            ea
                            eligendi eveniet expedita illum odio, provident recusandae rerum sapiente velit
                            voluptatem!
                            Accusantium adipisci, beatae blanditiis cumque ea eos id, illo illum, in minus nam
                            nesciunt
                            odio porro quaerat qui quis rem repellat temporibus. Atque fugit maiores tempore!

                        </flux:text>
                    </div>
                </div>
                <div class="mt-4 -mx-4 sm:mx-0 lg:mt-0 lg:col-span-7 xl:col-span-6">
                    <div
                        class="relative overflow-hidden shadow-xl flex bg-zinc-800 h-[31.625rem] max-h-[60vh] sm:max-h-[none] sm:rounded-xl lg:h-[34.6875rem] xl:h-[31.625rem] dark:bg-zinc-900/70 dark:backdrop-blur dark:ring-1 dark:ring-inset dark:ring-white/10">
                        <div class="relative w-full flex flex-col">
                            <div class="flex-none border-b border-zinc-500/30">
                                <div class="flex items-center h-8 space-x-1.5 px-3">
                                    <div class="w-2.5 h-2.5 bg-zinc-600 rounded-full"></div>
                                    <div class="w-2.5 h-2.5 bg-zinc-600 rounded-full"></div>
                                    <div class="w-2.5 h-2.5 bg-zinc-600 rounded-full"></div>
                                </div>
                            </div>
                            <div class="relative min-h-0 flex-auto flex flex-col">
                                <div class="w-full flex-auto flex min-h-0" style="opacity: 1;">
                                    <div class="w-full flex-auto flex min-h-0 overflow-hidden">
                                        <div class="w-full relative flex-auto"><pre
                                                class="flex min-h-full text-sm leading-6"><div aria-hidden="true"
                                                                                               class="hidden md:block text-zinc-600 flex-none py-4 pr-4 text-right select-none w-[3.125rem]">1<br>2<br>3<br>4<br>5<br>6<br>7<br>8<br>9<br>10<br>11<br>12<br>13<br>14<br>15<br>16<br>17<br>18</div><code
                                                    class="flex-auto relative block text-zinc-50 overflow-hidden p-4"><span
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
    <section class="relative isolate overflow-hidden">
        <div class="mx-auto max-w-7xl px-6 lg:flex lg:px-8">
            {{-- texts --}}
            <div class="mx-auto max-w-2xl flex-shrink-0 lg:mx-0 lg:max-w-xl lg:pt-12">
                <div class="flex justify-start">
                    <div
                        class="rounded-full bg-[color-mix(in_oklab,_var(--color-compliment),_transparent_90%)] p-3">
                        <flux:icon.building-storefront class="h-6 w-6 text-[var(--color-compliment-content)]"/>
                    </div>
                </div>

                <p class="text-[var(--color-compliment-content)] mt-3">Catalog</p>

                <flux:heading size="2xl" level="2" class="mt-6">
                    Your path, your possibilities.
                </flux:heading>

                <flux:text size="lg" class="mt-6 leading-8">
                    Explore our selection of convenience features and enhancements designed to complement your gameplay
                    experience. See detailed information about everything available in the game.
                </flux:text>

                <div class="mt-10">
                    <flux:link href="#" variant="ghost" :accent="false"
                               class="!text-[var(--color-compliment-content)] inline-flex items-center gap-2">
                        {{ __('Browse Offerings') }}
                        <flux:icon.arrow-long-right variant="micro"/>
                    </flux:link>
                </div>
            </div>

            {{-- image with floating boxes --}}
            <div class="relative w-full justify-end ml-12 lg:ml-0 -z-10">
                <x-feature-highlight
                    class="absolute top-4 max-lg:top-10 right-4 max-xl:right-12 z-[2]">
                    <flux:text class="text-white/70">
                        Starter buffs inside!
                    </flux:text>
                </x-feature-highlight>

                <x-feature-highlight class="absolute top-1/3 max-sm:left-4 sm:right-[29rem] lg:left-16">
                    <flux:text class="!text-white/70">Two tiers!</flux:text>
                </x-feature-highlight>

                <x-feature-highlight
                    class="absolute bottom-16 max-sm:left-16 md:right-[27rem] lg:-left-12 z-[2] max-w-xs">
                    <flux:heading class="!text-white">
                        Starting Item Bundles
                    </flux:heading>
                    <flux:subheading class="!text-white/70">
                        Psst... these bundles are packed with goodies to begin your adventure in style.
                    </flux:subheading>
                </x-feature-highlight>

                <img
                    src="{{ asset('images/shop.png') }}"
                    alt="App screenshot"
                    class="relative max-w-[36rem] lg:max-w-[48rem] xl:max-w-[56rem] ml-auto"
                >
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
