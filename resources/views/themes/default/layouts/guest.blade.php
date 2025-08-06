<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts.components.head')

    @themeAssets
</head>
<body
    class="flex flex-col min-h-screen antialiased bg-zinc-50 dark:bg-zinc-900 selection:bg-sky-600 selection:text-white transition-colors duration-300">

@include('layouts.components.guest.background-beams')

<livewire:discord-popup/>

@auth()
    <livewire:referral-survey-popup/>
@endauth

<livewire:layout.guest.header/>

@persist('stream-widget')
<livewire:stream-widget/>
@endpersist

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
