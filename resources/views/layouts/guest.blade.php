<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts.components.head')
</head>
<body class="min-h-screen antialiased">

<flux:main>
    {{ $slot }}
</flux:main>

@persist('toast')
<flux:toast/>
@endpersist

@livewireScripts
@fluxScripts

</body>
</html>
