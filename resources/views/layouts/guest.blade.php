<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts.components.head')
</head>
<body class="min-h-screen antialiased bg-zinc-50 dark:bg-zinc-900 transition-colors duration-300">

<livewire:layout.guest.header/>

{{ $slot }}

@include('layouts.components.guest.footer')

@persist('toast')
<flux:toast/>
@endpersist

@livewireScripts
@fluxScripts

</body>
</html>
