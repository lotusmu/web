<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts.components.head')

    @themeAssets
</head>
<body
    class="min-h-screen antialiased bg-zinc-50 dark:bg-zinc-900 transition-colors duration-300 selection:bg-sky-600 selection:text-white">

@include('layouts.components.background-beams')

<livewire:discord-popup/>

@auth
    <livewire:referral-survey-popup/>
@endauth

@persist('stream-widget')
<livewire:stream-widget/>
@endpersist

<livewire:notifications-modal/>

<livewire:layout.header/>

<flux:sidebar stashable sticky
              class="lg:hidden border-r bg-zinc-50 dark:bg-zinc-900 border-zinc-200 dark:border-zinc-700">
    <flux:sidebar.toggle class="lg:hidden" icon="x-mark"/>

    <x-brand
        :logo_light="theme_asset('brand/logotype.svg')"
        :logo_dark="theme_asset('brand/logotype-white.svg')"
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

@persist('toast')
<flux:toast/>
<livewire:session-toast/>
@endpersist

@livewireScripts
@fluxScripts

</body>
</html>
