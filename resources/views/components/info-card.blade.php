@props([
    'color' => 'teal',
    'icon' => null,
])

@php
    $classes = Flux::classes()
        ->add('flex gap-2 items-center')
        ->add(match ($color) {
            'teal' => '!bg-teal-50/50 dark:!bg-teal-400/20',
            'red' => '!bg-red-50/50 dark:!bg-red-400/20',
            'blue' => '!bg-blue-50/50 dark:!bg-blue-400/20',
            'yellow' => '!bg-yellow-50/50 dark:!bg-yellow-400/20',
            default => '!bg-zinc-50/50 dark:!bg-zinc-400/20',
        });
@endphp

<flux:card :attributes="$attributes->class($classes)">
    @if($icon)
        <flux:icon :icon="$icon"/>
    @endif

    <flux:text>
        {{ $slot }}
    </flux:text>
</flux:card>
