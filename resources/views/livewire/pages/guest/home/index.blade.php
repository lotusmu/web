<?php

use App\Enums\Content\ArticleType;
use App\Models\Content\Article;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    #[Computed]
    public function articles(): Collection
    {
        return Article::where('is_published', true)
            ->where('type', ArticleType::NEWS)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();
    }
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
    <section class="max-w-7xl mx-auto px-6 lg:px-8 !-mt-24">
        <div class="flex gap-8 items-center mb-8">
            <flux:heading size="xl" level="2" class="z-0">
                {{ __('Latest News') }}
            </flux:heading>
            <flux:link variant="subtle" :href="route('articles')" wire:navigate class="flex items-center gap-2 z-0">
                {{ __('View All') }}
                <flux:icon.arrow-right variant="micro"/>
            </flux:link>
        </div>

        <!-- News Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($this->articles as $article)
                <livewire:pages.guest.articles.card :$article :wire:key="$article->id"/>
            @endforeach
        </div>
    </section>

    <!-- Features -->
    <section class="relative isolate mt-32 px-6 pt-32 sm:mt-56 sm:pt-40 lg:px-8">
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
                    <flux:tab name="tab1" :accent="false">Tab Name</flux:tab>
                    <flux:tab name="tab2" :accent="false">Tab Name</flux:tab>
                    <flux:tab name="tab3" :accent="false">Tab Name</flux:tab>
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

    <!-- More Features -->
    <section class="relative isolate">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="mb-12 space-y-6 text-center">
                <div class="flex justify-center">
                    <div class="rounded-full bg-[color-mix(in_oklab,_var(--color-compliment),_transparent_90%)] p-3">
                        <flux:icon.film class="h-6 w-6 text-[var(--color-compliment-content)]"/>
                    </div>
                </div>

                <p class="text-[var(--color-compliment-content)] !mt-3">More Features</p>

                <flux:heading size="2xl" level="2">
                    Ancient powers reimagined for modern gameplay.
                </flux:heading>

                <flux:text size="lg" class="max-w-3xl leading-8 mx-auto">
                    Built with balance in mind, Lotus Mu brings fresh mechanics to the classic MU Online formula.
                    Every feature is carefully crafted to enhance gameplay without compromising the core experience
                    that made the game legendary.
                </flux:text>
            </div>

            <flux:tab.group class="mt-10">
                <flux:tabs variant="pills" class="flex overflow-auto sm:mx-0 sm:justify-center">
                    <flux:tab name="tab10" :accent="false" icon="swords">Evolving Items</flux:tab>
                    <flux:tab name="tab20" :accent="false" icon="scroll">Progressive Quests</flux:tab>
                    <flux:tab name="tab30" :accent="false" icon="sparkles">Crafting System</flux:tab>
                </flux:tabs>

                <div class="absolute w-full overflow-visible left-0 mt-8 xl:mt-16">
                    <x-grid-pattern position="top_left" class="h-[30rem]"/>
                </div>

                <flux:tab.panel name="tab10">
                    <x-feature-split>
                        <x-slot:text>
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit...
                        </x-slot:text>

                        <x-slot:media>
                            <x-browser-frame
                                video="{{ asset('videos/mu.mp4') }}"
                                poster="{{ asset('images/auth/auth-dark.jpg') }}"
                            />
                        </x-slot:media>
                    </x-feature-split>
                </flux:tab.panel>

                <flux:tab.panel name="tab20">
                    <x-feature-split>
                        <x-slot:text>
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit... Lorem ipsum dolor sit amet,
                            consectetur adipisicing elit. Ipsam, minima?
                        </x-slot:text>

                        <x-slot:media>
                            <x-browser-frame
                                video="{{ asset('videos/example.mp4') }}"
                                poster="{{ asset('images/auth/auth-dark.jpg') }}"
                            />
                        </x-slot:media>
                    </x-feature-split>
                </flux:tab.panel>

                <flux:tab.panel name="tab30">
                    <x-feature-split>
                        <x-slot:text>
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit... Lorem ipsum dolor sit amet,
                            consectetur adipisicing elit. Ipsam, minima? Lorem ipsum dolor sit amet, consectetur
                            adipisicing elit. Consectetur, minus.
                        </x-slot:text>

                        <x-slot:media>
                            <x-browser-frame
                                video="{{ asset('videos/auth-video.mp4') }}"
                                poster="{{ asset('images/auth/auth-dark.jpg') }}"
                            />
                        </x-slot:media>
                    </x-feature-split>
                </flux:tab.panel>
            </flux:tab.group>
        </div>


    </section>

    <!-- Catalog -->
    <section class="relative isolate overflow-hidden">
        <div class="mx-auto max-w-7xl px-6 lg:flex lg:px-8">
            {{-- texts --}}
            <div class="mx-auto max-w-2xl flex-shrink-0 lg:mx-0 lg:max-w-xl lg:pt-12">
                {{--                <div class="flex justify-start">--}}
                {{--                    <div--}}
                {{--                        class="rounded-full bg-[color-mix(in_oklab,_var(--color-compliment),_transparent_90%)] p-3">--}}
                {{--                        <flux:icon.building-storefront class="h-6 w-6 text-[var(--color-compliment-content)]"/>--}}
                {{--                    </div>--}}
                {{--                </div>--}}

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
