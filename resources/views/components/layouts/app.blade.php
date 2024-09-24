<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
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
</head>
<body class="min-h-screen bg-white dark:bg-zinc-800 antialiased">

<flux:sidebar sticky stashable class="bg-zinc-50 dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-700">
    <flux:sidebar.toggle class="lg:hidden" icon="x-mark"/>

    <flux:brand wire:navigate href="/" logo="https://fluxui.dev/img/demo/logo.png"
                name="Acme Inc."
                class="px-2 dark:hidden"/>
    <flux:brand wire:navigate href="/" logo="https://fluxui.dev/img/demo/dark-mode-logo.png"
                name="Acme Inc."
                class="px-2 hidden dark:flex"/>

    <flux:navlist variant="outline">
        <flux:navlist.item wire:navigate icon="home" href="/dashboard">Dashboard</flux:navlist.item>
        <flux:navlist.item wire:navigate icon="wallet" href="#">Wallet</flux:navlist.item>
        <flux:navlist.item wire:navigate icon="arrows-right-left" href="#">Wallet Transfers</flux:navlist.item>
        <flux:navlist.item wire:navigate icon="clock" href="#">Event Entries</flux:navlist.item>
        <flux:navlist.item wire:navigate icon="building-library" href="#">Castle Siege</flux:navlist.item>
        <flux:navlist.item wire:navigate icon="fire" href="#">Buy VIP</flux:navlist.item>
        <flux:navlist.item wire:navigate icon="eye-slash" href="#">Hide Info</flux:navlist.item>
        <flux:navlist.item wire:navigate icon="banknotes" href="#">Donate</flux:navlist.item>
        <flux:navlist.item wire:navigate icon="hand-thumb-up" href="#">Vote</flux:navlist.item>
        <flux:navlist.item wire:navigate icon="list-bullet" href="#">Activities</flux:navlist.item>
    </flux:navlist>

    <flux:spacer/>

    <flux:navlist variant="outline">
        <flux:navlist.item wire:navigate icon="cog-6-tooth" href="/profile">Settings</flux:navlist.item>
        <flux:navlist.item wire:navigate icon="information-circle" href="#">Help</flux:navlist.item>
    </flux:navlist>
    <flux:dropdown position="top" align="left" class="max-lg:hidden">
        <flux:profile avatar="https://fluxui.dev/img/demo/user.png" name="Game Server"/>

        <flux:menu>
            <flux:menu.radio.group>
                <flux:menu.radio checked>Yoskreth - x10</flux:menu.radio>
                <flux:menu.radio>Carnage - x200</flux:menu.radio>
            </flux:menu.radio.group>
        </flux:menu>
    </flux:dropdown>
</flux:sidebar>

<livewire:layout.header/>

<flux:main>
    {{ $slot }}
</flux:main>

<flux:toast/>

@fluxScripts
</body>
</html>
