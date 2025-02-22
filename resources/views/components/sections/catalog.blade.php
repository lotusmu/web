<section class="relative isolate overflow-hidden">
    <div class="mx-auto max-w-7xl px-6 lg:flex lg:px-8">

        {{-- texts --}}
        <div class="mx-auto max-w-2xl flex-shrink-0 lg:mx-0 lg:max-w-xl lg:pt-12">
            <x-sections.header-icon name="building-storefront" position="start"/>

            <x-sections.kicker :text="__('Catalog')"/>

            <flux:heading size="2xl" level="2" class="mt-6">
                {{ __('Your path, your possibilities.') }}
            </flux:heading>

            <flux:text size="lg" class="mt-6 leading-8">
                {{ __ ('Explore our selection of convenience features and enhancements designed to complement your gameplay
                experience. See detailed information about everything available in the game.')}}
            </flux:text>

            <div class="mt-10">
                <x-sections.link
                    :href="route('catalog')" wire:navigate.hover
                    text="{{ __('Browse Offerings') }}"
                />
            </div>
        </div>

        {{-- image with floating boxes --}}
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
                    loading="lazy"
                    decoding="async">
            </picture>
        </div>
    </div>
</section>
