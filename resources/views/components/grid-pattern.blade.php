@props([
    'position' => 'top_left',
    'ellipseWidth' => '75',
    'ellipseHeight' => '100',
    'borderTop' => true,
])

@php
    $positionMap = [
        'top_left' => 'top_left',
        'top_right' => 'top_right',
        'bottom_left' => 'bottom_left',
        'bottom_right' => 'bottom_right',
    ];

    $borderTopValue = [
        true => 0,
        false => -1,
    ];

    $border = $borderTopValue[$borderTop] ?? '0';
    $maskPosition = $positionMap[$position] ?? 'top_left';
    $patternId = 'pattern-'.uniqid();
@endphp

<svg {{ $attributes->merge([
    'class' => 'absolute inset-0 -z-10 h-full w-full stroke-black/10 dark:stroke-white/10 [mask-image:radial-gradient('.$ellipseWidth.'%_'.$ellipseHeight.'%_at_'.$maskPosition.',white,transparent)] ']) }}
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
