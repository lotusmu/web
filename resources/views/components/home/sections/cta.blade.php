<section class="relative isolate mt-32 py-32 sm:mt-56 sm:py-40">
    <x-home.grid-pattern position="top_right"/>

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

    <x-home.wrapper>
        <div class="text-center space-y-8">
            <x-home.heading class="mx-auto">
                {{ __('Build your dream character.') }}
            </x-home.heading>

            <x-home.text class="mx-auto">
                {{ __('Join the Lotus Mu Community as Mu Online enters a new era of gameplay. Experience our signature progressive item system, rich quest system, and an innovative crafting system that rewards creativity.') }}
            </x-home.text>

            <div class="flex max-sm:flex-col-reverse items-center justify-center gap-x-6 max-sm:gap-y-6 !mt-12">
                <flux:button variant="primary" :href="route('register')">
                    {{ __('Register Now') }}
                </flux:button>

                <flux:button variant="ghost" icon-trailing="arrow-long-right"
                             href="{{ config('social.links.discord') }}" target="_blank">
                    {{ __('Join Discord') }}
                </flux:button>
            </div>
        </div>
    </x-home.wrapper>
</section>
