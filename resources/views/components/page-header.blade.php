@props([
   'title' => '',
   'kicker' => '',
   'description' => null,
])

<header {{ $attributes->merge(['class' => 'flex flex-col items-center text-center mb-12']) }}>
    <flux:heading level="1" size="lg" class="!text-[var(--color-compliment-content)] mb-3">
        {{ $kicker }}
    </flux:heading>

    <flux:heading size="2xl">
        {{ $title }}
    </flux:heading>

    @if($description)
        <flux:subheading size="lg" class="max-w-3xl">
            {{ $description }}
        </flux:subheading>
    @endif
</header>
