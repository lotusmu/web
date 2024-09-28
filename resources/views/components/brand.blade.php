@props([
    'logo' => null,
    'size' => 'base',
    'href' => '/',
])

@php
    $classes = Flux::classes()
        ->add('flex items-center h-8 px-3 overflow-hidden');

    $imageSize = match($size) {
        'base' => '',
        'sm' => 'w-3/4',
    }
@endphp

<a href="{{ $href }}" {{ $attributes->class([ $classes ]) }} data-flux-brand>
    <img src="{{ $logo }}" alt="Brand logo image" class="{{$imageSize}}"/>
</a>
