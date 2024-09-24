<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

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
        <flux:navlist.item wire:navigate icon="inbox" badge="12" href="#">Inbox</flux:navlist.item>
        <flux:navlist.item wire:navigate icon="document-text" href="#">Documents</flux:navlist.item>
        <flux:navlist.item wire:navigate icon="calendar" href="#">Calendar</flux:navlist.item>

        <flux:navlist.group expandable heading="Favorites" class="hidden lg:grid">
            <flux:navlist.item wire:navigate href="#">Marketing site</flux:navlist.item>
            <flux:navlist.item wire:navigate href="#">Android app</flux:navlist.item>
            <flux:navlist.item wire:navigate href="#">Brand guidelines</flux:navlist.item>
        </flux:navlist.group>
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

<flux:header class="!block bg-white lg:bg-zinc-50 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700">
    <flux:navbar class="lg:hidden w-full">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left"/>

        <flux:spacer/>

        <flux:navbar.item href="/admin">Admin Dashboard</flux:navbar.item>
        <flux:button variant="ghost" size="sm" icon="moon" tooltip="Toggle dark mode"
                     x-on:click="$store.darkMode.toggle()"/>

        <flux:separator variant="subtle" vertical class="mx-4"/>

        <flux:dropdown>
            <flux:button variant="ghost" size="sm" icon-trailing="chevron-down">{{ auth()->user()->name }}</flux:button>

            <flux:menu>
                <livewire:logout/>
            </flux:menu>
        </flux:dropdown>
    </flux:navbar>

    <flux:navbar scrollable>
        <flux:navbar.item href="/upcoming-events" wire:navigate>Events</flux:navbar.item>

        <flux:spacer/>

        <div class="flex items-center gap-1 max-lg:hidden">
            <flux:navbar.item href="/admin">Admin Dashboard</flux:navbar.item>
            <flux:button variant="ghost" size="sm" icon="moon" tooltip="Toggle dark mode"
                         x-on:click="$store.darkMode.toggle()"/>


            <flux:separator variant="subtle" vertical class="mx-4"/>

            <flux:dropdown>
                <flux:button variant="ghost" size="sm"
                             icon-trailing="chevron-down">{{ auth()->user()->name }}</flux:button>

                <flux:menu>
                    <livewire:logout/>
                </flux:menu>
            </flux:dropdown>
        </div>
    </flux:navbar>
</flux:header>

<flux:main>
    {{ $slot }}
</flux:main>

<flux:toast/>

@fluxScripts
</body>
</html>
