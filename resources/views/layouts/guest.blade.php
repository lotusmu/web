<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts.components.head')
</head>
<body class="flex flex-col min-h-screen antialiased bg-zinc-50 dark:bg-zinc-900 transition-colors duration-300">

{{-- Dark theme beams --}}
<picture class="absolute inset-0 -z-20">
    <source
        srcset="{{ asset('/images/beams/hero-dark.avif') }}"
        type="image/avif"
        class="hidden dark:block">
    <source
        srcset="{{ asset('/images/beams/hero-dark.webp') }}"
        type="image/webp"
        class="hidden dark:block">
    <img
        src="{{ asset('/images/beams/hero-dark.png') }}"
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

<livewire:discord-popup/>

@auth()
    <livewire:referral-survey-popup/>
@endauth

<livewire:layout.guest.header/>

<livewire:stream-widget/>

<main class="flex-1">
    {{ $slot }}
</main>

@include('layouts.components.guest.footer')

@persist('toast')
<flux:toast/>
@endpersist

@livewireScripts
@fluxScripts

</body>
</html>
