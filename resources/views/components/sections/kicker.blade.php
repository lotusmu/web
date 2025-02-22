@props(['text'])

<p {{ $attributes->merge(['class' => 'text-[var(--color-compliment-content)] !mt-3']) }}>{{ $text }}</p>
