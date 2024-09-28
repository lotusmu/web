@props([
    'logo' => null,
    'size' => 'base',
    'href' => '/',
])

@php
    $classes = Flux::classes()
        ->add('h-10 px-3 overflow-hidden')
        ->add(match ($size) { // Size...
        'base' => '', // Full image size
        'sm' => 'size-3/4',
    });
@endphp

<a href="{{ $href }}" {{ $attributes->class([ $classes ]) }} data-flux-brand>
    <img src="{{ $logo }}" alt="Brand logo image"/>
</a>
