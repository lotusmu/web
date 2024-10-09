<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts.components.head')
</head>
<body class="min-h-screen antialiased">

<livewire:layout.header/>

<flux:sidebar stashable sticky
              class="lg:hidden bg-zinc-50 dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-700">
    <flux:sidebar.toggle class="lg:hidden" icon="x-mark"/>

    <x-brand size="sm" logo="{{asset('images/logo/logo-hor-light.svg')}}" class="dark:hidden"/>
    <x-brand size="sm" logo="{{asset('images/logo/logo-hor-dark.svg')}}" class="hidden dark:flex"/>

    <flux:navlist variant="outline">
        <flux:navlist.item wire:navigate icon="home" href="/dashboard">Dashboard</flux:navlist.item>
        <flux:navlist.item wire:navigate icon="wallet" href="/wallet">Wallet</flux:navlist.item>
        <flux:navlist.item wire:navigate icon="clock" href="#">Event Entries</flux:navlist.item>
        <flux:navlist.item wire:navigate icon="building-library" href="#">Castle Siege</flux:navlist.item>
        <flux:navlist.item wire:navigate icon="fire" href="#">Buy VIP</flux:navlist.item>
        <flux:navlist.item wire:navigate icon="eye-slash" href="#">Hide Info</flux:navlist.item>
        <flux:navlist.item wire:navigate icon="arrows-pointing-out" href="#">Unstuck Character</flux:navlist.item>
        <flux:navlist.item wire:navigate icon="sparkles" href="#">Donate</flux:navlist.item>
        <flux:navlist.item wire:navigate icon="hand-thumb-up" href="#">Vote</flux:navlist.item>
        <flux:navlist.item wire:navigate icon="list-bullet" href="/activities">Activities</flux:navlist.item>
        <flux:separator variant="subtle" class="my-px"/>
        <flux:navlist.item icon="information-circle" href="#">Help</flux:navlist.item>
    </flux:navlist>
</flux:sidebar>

<flux:main container>
    <div class="flex gap-10 mt-2 lg:mt-8 max-w-[60rem] mx-auto">
        <div class="min-w-[13rem] max-lg:hidden flex-col min-h-full">
            <flux:navlist>
                <flux:navlist.item wire:navigate icon="home" href="/dashboard">Dashboard</flux:navlist.item>
                <flux:navlist.item wire:navigate icon="wallet" href="/wallet">Wallet</flux:navlist.item>
                <flux:navlist.item wire:navigate icon="clock" href="#">Event Entries</flux:navlist.item>
                <flux:navlist.item wire:navigate icon="building-library" href="#">Castle Siege</flux:navlist.item>
                <flux:navlist.item wire:navigate icon="fire" href="#">Buy VIP</flux:navlist.item>
                <flux:navlist.item wire:navigate icon="eye-slash" href="#">Hide Info</flux:navlist.item>
                <flux:navlist.item wire:navigate icon="arrows-pointing-out" href="#">Unstuck Character
                </flux:navlist.item>
                <flux:navlist.item wire:navigate icon="sparkles" href="#">Donate</flux:navlist.item>
                <flux:navlist.item wire:navigate icon="hand-thumb-up" href="#">Vote</flux:navlist.item>
                <flux:navlist.item wire:navigate icon="list-bullet" href="/activities">Activities</flux:navlist.item>
                <flux:separator variant="subtle" class="my-px"/>
                <flux:navlist.item icon="information-circle" href="#">Help</flux:navlist.item>
            </flux:navlist>
        </div>

        <div class="flex-1">
            {{ $slot }}
        </div>
    </div>
</flux:main>


@persist('toast')
<flux:toast/>
@endpersist

@livewireScripts
@fluxScripts

<script data-navigate-once="">
    document.addEventListener('livewire:navigated', () => {
        // wire:navigate will wipe out the dark class on the body element, se we need to reapply it...
        Alpine.store('darkMode').applyToBody()
    })

    Alpine.store('darkMode', {
        on: false,

        toggle() {
            this.on = !this.on
        },

        init() {
            this.on = this.wantsDarkMode()

            Alpine.effect(() => {
                document.dispatchEvent(new CustomEvent('dark-mode-toggled', {detail: {isDark: this.on}, bubbles: true}))

                this.applyToBody()
            })

            // Putting this in a set timeout to wait for the iframes to be loaded...
            setTimeout(() => {
                Alpine.effect(() => {
                    this.applyToIframes()
                })
            }, 5000)

            let media = window.matchMedia('(prefers-color-scheme: dark)')

            media.addEventListener('change', e => {
                this.on = media.matches
            })
        },

        wantsDarkMode() {
            let media = window.matchMedia('(prefers-color-scheme: dark)')

            if (window.localStorage.getItem('darkMode') === '') {
                return media.matches
            } else {
                return JSON.parse(window.localStorage.getItem('darkMode'))
            }
        },

        applyToBody() {
            let state = this.on

            window.localStorage.setItem('darkMode', JSON.stringify(state))

            state ? document.body.classList.add('dark') : document.body.classList.remove('dark')
        },

        applyToIframes() {
            let state = this.on

            // Update dark mode inside iframes...
            state
                ? document.querySelectorAll('iframe').forEach(iframe => iframe.contentDocument?.querySelector('body')?.classList?.add('dark'))
                : document.querySelectorAll('iframe').forEach(iframe => iframe.contentDocument?.querySelector('body')?.classList?.remove('dark'))
        }
    })
</script>
</body>
</html>
