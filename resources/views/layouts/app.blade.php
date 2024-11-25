<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      class="transition-colors duration-500 bg-zinc-50 dark:bg-zinc-900">
<head>
    @include('layouts.components.head')
</head>
<body class="min-h-screen antialiased">

<div class="flex absolute -z-50 top-0 inset-x-0 justify-center overflow-hidden pointer-events-none">
    <div class="w-[108rem] flex-none flex justify-end">
        <picture>
            <source srcset="{{ asset('images/background/dark.avif') }}" type="image/avif">
            <img src="{{ asset('images/background/dark.png') }}" alt=""
                 class="w-[90rem] flex-none max-w-none hidden dark:block" decoding="async"></picture>

        <picture>
            <source srcset="{{ asset('images/background/light.avif') }}" type="image/avif">
            <img src="{{ asset('images/background/light.png') }}" alt=""
                 class="w-[71.75rem] flex-none max-w-none dark:hidden" decoding="async">
        </picture>
    </div>
</div>

<livewire:layout.header/>

<flux:sidebar stashable sticky
              class="lg:hidden border-r bg-zinc-50 dark:bg-zinc-900 border-zinc-200 dark:border-zinc-700">
    <flux:sidebar.toggle class="lg:hidden" icon="x-mark"/>

    <x-brand size="sm" logo="{{asset('images/logo/logo-hor-light.svg')}}" class="dark:hidden"/>
    <x-brand size="sm" logo="{{asset('images/logo/logo-hor-dark.svg')}}" class="hidden dark:flex"/>

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

            if (state) {
                document.body.classList.add('dark')
                document.documentElement.classList.add('dark')
            } else {
                document.body.classList.remove('dark')
                document.documentElement.classList.remove('dark')
            }
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

@stack('scripts')

</body>
</html>
