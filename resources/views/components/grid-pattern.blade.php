@props([
    'position' => 'top_left',
    'borderTop' => true,
])

@php
    $border = $borderTop ? 0 : -1;
    $fullClass = "absolute inset-0 -z-10 h-full w-full stroke-black/10 dark:stroke-white/10 " .
        match($position) {
            'top_right' => '[mask-image:radial-gradient(100%_100%_at_top_right,white,transparent)]',
            'bottom_left' => '[mask-image:radial-gradient(100%_100%_at_bottom_left,white,transparent)]',
            'bottom_right' => '[mask-image:radial-gradient(100%_100%_at_bottom_right,white,transparent)]',
            default => '[mask-image:radial-gradient(100%_100%_at_top_left,white,transparent)]'
        };
    $patternId = 'pattern-'.uniqid();
@endphp


<svg {{ $attributes->merge(['class' => $fullClass]) }}
     aria-hidden="true">
    <defs>
        <pattern id="{{ $patternId }}" width="200" height="200" x="50%" y="{{ $border }}"
                 patternUnits="userSpaceOnUse">
            <path d="M.5 200V.5H200" fill="none"/>
        </pattern>
    </defs>
    <svg x="50%" y="-1" class="overflow-visible fill-zinc-800/5 dark:fill-zinc-800/20">
        <path d="M-200 0h201v201h-201Z M600 0h201v201h-201Z M-400 600h201v201h-201Z M200 800h201v201h-201Z"
              stroke-width="0"/>
    </svg>
    <rect width="100%" height="100%" stroke-width="0" fill="url(#{{ $patternId }})"/>
</svg>
