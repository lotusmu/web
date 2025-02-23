@props([
   'src',
   'alt' => 'Image description',
])

<picture>

    <img
        {{ $attributes->merge(['class' => 'rounded-xl border border-zinc-200 dark:border-white/10']) }}
        src="{{ asset($src . '.jpg') }}"
        alt="{{ $alt }}"
        loading="lazy"
        decoding="async">
</picture>
