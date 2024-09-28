<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts.components.head')
</head>
<body class="bg-white dark:bg-zinc-900 antialiased min-h-screen">

<flux:main>
    {{ $slot }}
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
