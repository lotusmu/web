<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts.components.head')
</head>
<body class="min-h-screen antialiased bg-zinc-50 dark:bg-zinc-900">

<flux:main class="flex min-h-screen !p-0">
    <div class="flex-1 flex justify-center items-center">
        <div class="w-80 max-w-80 space-y-6">
            <div class="flex justify-center">
                <x-brand
                    :logo_light="asset('images/brand/lotusmu-logotype.svg')"
                    :logo_dark="asset('images/brand/lotusmu-logotype-white.svg')"
                />
            </div>

            {{ $slot }}

        </div>
    </div>


    <div class="flex-1 p-4 max-lg:hidden">
        <div class="text-white relative rounded-lg h-full w-full flex flex-col items-start justify-end p-16"
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
