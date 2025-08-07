<section class="relative isolate overflow-hidden">
    <x-home.grid-pattern position="top_left" :border-top="false"/>

    <x-home.wrapper class="lg:flex pt-10 lg:pt-40">
        <div class="max-w-2xl lg:max-w-xl flex-shrink-0 lg:pt-8">
            <livewire:updates-banner/>

            <flux:heading level="1" size="3xl" class="mt-10 flex flex-col">
                <span>{{ __('Reimagined.') }}</span>
                <span>{{ __('Refined.') }}</span>
                <span>{{ __('Revolutionary.') }}</span>
            </flux:heading>

            <flux:subheading size="xl" class="mt-6">
                {{ __('A server with soul. Thoughtfully crafted, not copy-pasted.') }}
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
    </x-home.wrapper>
</section>
