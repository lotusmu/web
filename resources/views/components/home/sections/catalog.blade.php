<section class="relative isolate overflow-hidden">
    <x-home.wrapper class="lg:flex">
        <div class="flex-shrink-0 max-w-2xl lg:max-w-xl mx-auto lg:mx-0 lg:pt-12 space-y-8">
            <x-home.header-icon name="building-storefront"
                                position="start"/>

            <x-home.kicker>
                {{ __('Catalog') }}
            </x-home.kicker>

            <x-home.heading>
                {{ __('Your path, your possibilities.') }}
            </x-home.heading>

            <x-home.text>
                {{ __ ('Explore our selection of convenience features and enhancements designed to complement your gameplay
                experience. See detailed information about everything available in the game.')}}
            </x-home.text>

            <x-home.link :href="route('catalog')" wire:navigate.hover>
                {{ __('Browse Offerings') }}
            </x-home.link>
        </div>

        <div class="relative w-full justify-end ml-12 lg:ml-0 -z-10">
            <x-feature-highlight
                class="absolute top-4 max-lg:top-10 right-4 max-xl:right-12 z-[2]">
                <flux:text class="!text-white/70">
                    {{ __('Starter buffs inside!') }}
                </flux:text>
            </x-feature-highlight>

            <x-feature-highlight class="absolute top-1/3 max-sm:left-4 sm:right-[29rem] lg:left-16">
                <flux:text class="!text-white/70">
                    {{ __('Two tiers!') }}
                </flux:text>
            </x-feature-highlight>

            <x-feature-highlight
                class="absolute bottom-16 max-sm:left-16 md:right-[27rem] lg:-left-12 z-[2] max-w-xs">
                <flux:heading class="!text-white">
                    {{ __('Starting Item Bundles') }}
                </flux:heading>
                <flux:subheading class="!text-white/70">
                    {{ __('Psst... these bundles are packed with goodies to begin your adventure in style.') }}
                </flux:subheading>
            </x-feature-highlight>

            <picture>
                <source
                    srcset="{{ asset('images/catalog/catalog.webp') }}"
                    type="image/webp">
                <img
                    src="{{ asset('images/catalog/catalog.png') }}"
                    alt="Fantasy shop stall with magical potions"
                    class="relative max-w-[36rem] lg:max-w-[48rem] xl:max-w-[56rem] ml-auto"
                    decoding="async">
            </picture>
        </div>
    </x-home.wrapper>
</section>
