<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @themeComponent('layout.head')

    @stack('scripts')
</head>
<body
    class="min-h-screen antialiased bg-zinc-50 dark:bg-zinc-900 dark:selection:bg-rose-600 selection:bg-violet-600 selection:text-white">


<flux:main class="relative flex min-h-screen !p-0">
    <div
        class="absolute dark:opacity-10 h-screen w-screen bg-[radial-gradient(#e5e7eb_1px,transparent_1px)] [background-size:16px_16px] [mask-image:radial-gradient(ellipse_30%_60%_at_50%_50%,#000_70%,transparent_100%)] -z-10">
    </div>

    <div class="flex-1 flex justify-center items-center mb-10">
        <div class="w-[28rem] max-w-[28rem] max-sm:w-full max-sm:max-w-full space-y-6 my-12">
            <div class="flex justify-center">
                <x-brand
                    :logo_light="theme_logo('light')"
                    :logo_dark="theme_logo('dark')"
                />
            </div>

            {{ $slot }}
        </div>
    </div>

    <div
        class="absolute bottom-0 left-1/2 z-10 flex -translate-x-1/2 items-center gap-3 py-8 opacity-50 dark:opacity-30 hover:opacity-70 transition-opacity cursor-default">
        <img src="{{ theme_logo('mark') }}" class="size-6 grayscale" width="24" height="24" alt="Yulan Mu Logo Mark">

        <flux:text size="sm" class="leading-5">
            &copy; {{ date("Y") }} {{__('Yulan Mu')}}
        </flux:text>
    </div>
</flux:main>


@persist('toast')
<flux:toast/>
@endpersist

@livewireScripts
@fluxScripts

</body>
</html>
