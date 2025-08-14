{{-- Dark theme beams --}}
<picture class="absolute inset-0 -z-20">
    {{--    <source--}}
    {{--        srcset="{{ asset('/images/beams/hero-dark.avif') }}"--}}
    {{--        type="image/avif"--}}
    {{--        class="hidden dark:block">--}}
    {{--    <source--}}
    {{--        srcset="{{ asset('/images/beams/hero-dark.webp') }}"--}}
    {{--        type="image/webp"--}}
    {{--        class="hidden dark:block">--}}
    <img
        src="{{ asset('/images/beams/hero-dark-v2.png') }}"
        alt="Dark background beams"
        class="hidden dark:block opacity-70 h-full w-full bg-bottom bg-no-repeat max-xl:object-cover [mask-image:linear-gradient(to_top,transparent_0%,white_20%)]"
        loading="eager"
        fetchpriority="high"
        decoding="async">
</picture>

{{-- Light theme beams --}}
<picture class="absolute inset-0 -z-20">
    <source
        srcset="{{ asset('/images/beams/hero-light.avif') }}"
        type="image/avif"
        class="dark:hidden">
    <source
        srcset="{{ asset('/images/beams/hero-light.webp') }}"
        type="image/webp"
        class="dark:hidden">
    <img
        src="{{ asset('/images/beams/hero-light.jpg') }}"
        alt="Light background beams"
        class="dark:hidden h-full w-full bg-bottom bg-no-repeat max-lg:object-cover [mask-image:linear-gradient(to_top,transparent_0%,white_20%)]"
        loading="eager"
        fetchpriority="high"
        decoding="async">
</picture>
