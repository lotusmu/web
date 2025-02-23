@props([
    'name',
    'position' => 'center',
])

@php
    $classes = Flux::classes()
        ->add(match ($position) {
            'start' => 'justify-start',
            'center' => 'justify-center',
            'end' => 'justify-end',
        })
        ->add('flex')
        ;
@endphp

<div {{ $attributes->class($classes) }}>
    <div class="rounded-full bg-[color-mix(in_oklab,_var(--color-compliment),_transparent_90%)] p-3">
        <flux:icon :$name class="h-6 w-6 text-[var(--color-compliment-content)]"/>
    </div>
</div>
