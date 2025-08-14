<section class="relative isolate overflow-hidden">
    <x-home.grid-pattern position="top_left" :border-top="false"/>

    <x-home.wrapper class="lg:flex pt-10 lg:pt-40">
        <div class="max-w-2xl lg:max-w-xl flex-shrink-0 lg:pt-8">
            <livewire:updates-banner/>

            <flux:heading level="1" size="3xl" class="mt-10 flex flex-col">
                <span>{{ __('Ancient.') }}</span>
                <span>{{ __('Awakened.') }}</span>
                <span>{{ __('Alive.') }}</span>
            </flux:heading>

            <flux:subheading size="xl" class="mt-6">
                {{ __('An age-old saga, reborn in your hands.') }}
            </flux:subheading>

            <div class="mt-10 flex items-center gap-x-6">
                <flux:button variant="primary" icon="arrow-down-tray" :href="route('files')" wire:navigate.hover>
                    {{ __('Play for Free') }}
                </flux:button>

                <flux:button variant="ghost" icon-trailing="arrow-long-right"
                             href="https://wiki.lotusmu.org" target="_blank">
                    {{ __('Learn more') }}
                </flux:button>
            </div>
        </div>

        {{-- Dark theme hero image --}}
        <div class="hidden dark:flex w-full justify-end ml-24 lg:-ml-24 mt-0 md:-mt-64 lg:-mt-36 -z-10">
            {{--            <picture>--}}
            {{--                <source srcset="{{ asset('images/hero/hero_wizard.avif') }}" type="image/avif">--}}
            {{--                <source srcset="{{ asset('images/hero/hero_wizard.webp') }}" type="image/webp">--}}
            <img src="{{ asset('images/hero/hero_knight.png') }}"
                 class="max-w-[42rem] md:max-w-[64rem] lg:max-w-[80rem] xl:max-w-[92rem]"
                 alt="Knight character from game Mu Online"
                 loading="eager"
                 fetchpriority="high"
                 decoding="async">
            {{--            </picture>--}}
        </div>

        {{-- Light theme hero image --}}
        <div class="flex dark:hidden w-full justify-end ml-64 md:ml-80 lg:ml-24 mt-0 md:-mt-64 lg:-mt-36 -z-10">
            <picture>
                {{--                <source srcset="{{ asset('images/hero/hero_elf.avif') }}" type="image/avif">--}}
                {{--                <source srcset="{{ asset('images/hero/hero_elf.webp') }}" type="image/webp">--}}
                <img src="{{ asset('images/hero/hero_gladiator.png') }}"
                     class="max-w-[42rem] md:max-w-[64rem] lg:max-w-[80rem] xl:max-w-[92rem]"
                     alt="Gladiator character from game Mu Online"
                     loading="eager"
                     fetchpriority="high"
                     decoding="async">
            </picture>
        </div>
    </x-home.wrapper>
</section>
