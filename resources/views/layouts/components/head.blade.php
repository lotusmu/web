<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>{{ config('app.name', 'Home') }}</title>

<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400..600&display=swap" rel="stylesheet">

<!-- Scripts -->
@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxStyles

<style>
    :root:has(body.dark) {
        color-scheme: dark;
    }
</style>

@yield('head')
