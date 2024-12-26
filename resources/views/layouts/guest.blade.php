<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts.components.head')
</head>
<body class="min-h-screen antialiased bg-zinc-50 dark:bg-zinc-900">

<livewire:layout.guest.header/>

{{ $slot }}

@persist('toast')
<flux:toast/>
@endpersist

@livewireScripts
@fluxScripts

</body>
</html>
