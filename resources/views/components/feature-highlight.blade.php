@php
    $classes = Flux::classes()
        ->add('bg-zinc-900/40 dark:bg-zinc-50/5')
        ->add('backdrop-blur-md rounded-lg')
        ->add('border border-transparent dark:border-zinc-50/10')
        ->add('shadow-lg dark:shadow-none')
        ->add('p-3')
        ;
@endphp

<div {{ $attributes->class($classes) }}>{{ $slot }}</div>
