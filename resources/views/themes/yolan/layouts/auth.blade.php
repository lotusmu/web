<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts.components.head')

    @themeAssets

    @stack('scripts')
</head>
<body class="min-h-screen antialiased bg-zinc-50 dark:bg-zinc-900 selection:bg-indigo-500 selection:text-white">

<flux:main class="flex min-h-screen !p-0">
    <div class="flex-1 flex justify-center items-center">
        <div class="w-80 max-w-80 space-y-6 my-12">
            <div class="flex justify-center">
                <x-brand
                    :logo_light="theme_asset('brand/logotype.svg')"
                    :logo_dark="theme_asset('brand/logotype-white.svg')"
                />
            </div>

            {{ $slot }}

        </div>
    </div>


    <div class="flex-1 p-4 max-lg:hidden">
        <div
            class="relative rounded-lg h-full w-full flex flex-col items-start justify-end p-16 text-white overflow-hidden">

            <picture class="absolute inset-0 h-full w-full -z-10">
                <source
                    srcset="{{ asset('images/auth/image.avif') }}"
                    type="image/avif">
                <source
                    srcset="{{ asset('images/auth/image.webp') }}"
                    type="image/webp">
                <img
                    src="{{ asset('images/auth/image.jpg') }}"
                    alt="Nighttime torii gate scene"
                    class="h-full w-full object-cover"
                    style="object-position: 25%;"
                    loading="lazy"
                    decoding="async">
            </picture>

            <div class="flex gap-2 mb-4">
                <flux:icon.star variant="solid"/>
                <flux:icon.star variant="solid"/>
                <flux:icon.star variant="solid"/>
                <flux:icon.star variant="solid"/>
                <flux:icon.star variant="solid"/>
            </div>

            <div class="mb-6 italic font-base text-3xl xl:text-4xl">
                {{__('The Journey Begins Here')}}
            </div>

            <div class="text-lg font-medium">
                {{__('Beyond the veil of nostalgia lies a land of untold wonders and hidden mysteries')}}
            </div>
        </div>
    </div>
</flux:main>

@persist('toast')
<flux:toast/>
@endpersist

@livewireScripts
@fluxScripts

</body>
</html>
