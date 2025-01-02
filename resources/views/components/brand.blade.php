@props([
    'logo_light' => null,
    'logo_dark' => null,
    'size' => 'base',
    'href' => route('guest.home'),
])

@php
    $classes = Flux::classes()
        ->add('flex items-center h-8 overflow-hidden');

    $imageSize = match($size) {
        'base' => '',
        'sm' => 'w-3/4',
    }
@endphp

<a wire:navigate href="{{ $href }}" {{ $attributes->class([ $classes ]) }} data-flux-brand>
    <img src="{{ $logo_light }}" alt="Brand logo image" class="{{$imageSize}} block dark:hidden"/>
    <img src="{{ $logo_dark }}" alt="Brand logo image" class="{{$imageSize}} hidden dark:block"/>
</a>
