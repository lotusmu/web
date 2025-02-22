<section class="relative isolate mt-32 px-6 py-32 sm:mt-56 sm:py-40 lg:px-8">
    <x-sections.grid-pattern position="top_right"/>

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
