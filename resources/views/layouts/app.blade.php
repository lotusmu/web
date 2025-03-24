<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts.components.head')
</head>
<body class="min-h-screen antialiased bg-zinc-50 dark:bg-zinc-900 transition-colors duration-300">

<div class="flex absolute -z-50 top-0 inset-x-0 justify-center overflow-hidden pointer-events-none">
    <div class="w-[108rem] flex-none flex justify-end">
        <picture>
            <source srcset="{{ asset('images/beams/dashboard-dark.avif') }}" type="image/avif">
            <img src="{{ asset('images/beams/dashboard-dark.png') }}" alt=""
                 class="w-[90rem] flex-none max-w-none hidden dark:block" decoding="async"></picture>

        <picture>
            <source srcset="{{ asset('images/beams/dashboard-light.avif') }}" type="image/avif">
            <img src="{{ asset('images/beams/dashboard-light.png') }}" alt=""
                 class="w-[71.75rem] flex-none max-w-none dark:hidden" decoding="async">
        </picture>
    </div>
</div>

<livewire:layout.header/>

<flux:sidebar stashable sticky
              class="lg:hidden border-r bg-zinc-50 dark:bg-zinc-900 border-zinc-200 dark:border-zinc-700">
    <flux:sidebar.toggle class="lg:hidden" icon="x-mark"/>

    <x-brand
        :logo_light="asset('images/brand/lotusmu-logotype.svg')"
        :logo_dark="asset('images/brand/lotusmu-logotype-white.svg')"
        size="sm"
        class="px-3"
    />

    @include('layouts.components.sidebar')
</flux:sidebar>

<flux:main container>
    <div class="flex gap-10 mt-2 lg:mt-8 max-w-[60rem] mx-auto">
        <div class="min-w-[13rem] max-lg:hidden flex-col min-h-full">
            @include('layouts.components.sidebar')
        </div>

        <div class="flex-1 overflow-x-auto">
            {{ $slot }}
        </div>
    </div>
</flux:main>

<livewire:discord-popup/>
<livewire:referral-survey-popup/>

@persist('toast')
<flux:toast/>
<livewire:session-toast/>
@endpersist

@livewireScripts
@fluxScripts

</body>
</html>
