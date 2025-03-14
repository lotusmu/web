@props(['status'])

@php
    $classes = Flux::classes()
        ->add('w-1.5 h-1.5 rounded-full pulse')
        ->add(match ($status) {
            true => 'bg-emerald-400/80',
            false => 'bg-rose-400/80',
            default => 'bg-rose-400/80',
        })
        ;
@endphp

<div {{ $attributes->class($classes) }}></div>
