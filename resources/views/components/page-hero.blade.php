@props([
   'title' => '',
   'kicker' => '',
   'description' => null,
])

<section {{ $attributes->merge(['class' => 'flex flex-col items-center text-center mb-12']) }}>
    <flux:heading level="1" size="lg" class="!text-[var(--color-compliment-content)] mb-3">
        {{ __($kicker) }}
    </flux:heading>

    <flux:heading size="2xl">
        {{ __($title) }}
    </flux:heading>

    @if($description)
        <flux:subheading size="lg">
            {{ __($description) }}
        </flux:subheading>
    @endif
</section>
