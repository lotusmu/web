@props([
    'href',
    'text',
    'external' => false,
])

<flux:link
    variant="ghost"
    :accent="false"
    href="{{ $href }}"
    :external="$external"
    {{ $attributes->merge(['class' => '!text-[var(--color-compliment-content)] mt-6 inline-flex items-center gap-2']) }}
>
    {{ $text }}
    <flux:icon.arrow-long-right variant="micro"/>
</flux:link>
