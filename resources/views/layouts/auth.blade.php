<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts.components.head')
</head>
<body class="bg-white dark:bg-zinc-900 antialiased min-h-screen">

<flux:main class="flex min-h-screen bg-white dark:bg-zinc-900 !p-0">
    <div class="flex-1 flex justify-center items-center">
        <div class="w-80 max-w-80 space-y-6">
            <div class="flex justify-center">
                <x-brand wire:navigate logo="{{asset('images/logo/logo-hor-light.svg')}}" class="dark:hidden"/>

                <x-brand wire:navigate logo="{{asset('images/logo/logo-hor-dark.svg')}}"
                         class="hidden dark:block"/>
            </div>

            {{ $slot }}

        </div>
    </div>


    <div class="flex-1 p-4 max-lg:hidden">
        <div class="text-white relative rounded-lg h-full w-full bg-zinc-900 flex flex-col items-start justify-end p-16"
             style="background-image: url({{asset('images/auth/auth-dark.jpg')}});
                    background-size: cover;
                    background-position: 25%;
                    background-repeat: no-repeat;">

            <div class="flex gap-2 mb-4">
                <svg class="shrink-0 [:where(&amp;)]:size-6" data-flux-icon="" xmlns="http://www.w3.org/2000/svg"
                     viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" data-slot="icon">
                    <path fill-rule="evenodd"
                          d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z"
                          clip-rule="evenodd"></path>
                </svg>

                <svg class="shrink-0 [:where(&amp;)]:size-6" data-flux-icon="" xmlns="http://www.w3.org/2000/svg"
                     viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" data-slot="icon">
                    <path fill-rule="evenodd"
                          d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z"
                          clip-rule="evenodd"></path>
                </svg>

                <svg class="shrink-0 [:where(&amp;)]:size-6" data-flux-icon="" xmlns="http://www.w3.org/2000/svg"
                     viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" data-slot="icon">
                    <path fill-rule="evenodd"
                          d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z"
                          clip-rule="evenodd"></path>
                </svg>

                <svg class="shrink-0 [:where(&amp;)]:size-6" data-flux-icon="" xmlns="http://www.w3.org/2000/svg"
                     viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" data-slot="icon">
                    <path fill-rule="evenodd"
                          d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z"
                          clip-rule="evenodd"></path>
                </svg>

                <svg class="shrink-0 [:where(&amp;)]:size-6" data-flux-icon="" xmlns="http://www.w3.org/2000/svg"
                     viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" data-slot="icon">
                    <path fill-rule="evenodd"
                          d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z"
                          clip-rule="evenodd"></path>
                </svg>
            </div>

            <div class="mb-6 italic font-base text-3xl xl:text-4xl">
                Join the adventure and conquer the realms in Lotus Mu!
            </div>

            <div class="text-lg font-medium">
                Embark on an epic quest in this Masterpiece.
            </div>
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
