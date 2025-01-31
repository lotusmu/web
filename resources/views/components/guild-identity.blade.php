@props([
    'guild' => null,
    'guildMember' => null,
    'size' => 'base'
])

@php
    $guildInstance = $guild ?? ($guildMember?->guild()->first());

    $sizes = [
        'xs' => [
            'image' => 'w-4 h-4',
            'mark' => 16,
            'container' => 'gap-1',
            'text' => 'text-xs'
        ],
        'sm' => [
            'image' => 'w-5 h-5',
            'mark' => 20,
            'container' => 'gap-1.5',
            'text' => 'text-sm'
        ],
        'base' => [
            'image' => 'w-6 h-6',
            'mark' => 24,
            'container' => 'gap-2',
            'text' => 'text-base'
        ],
        'lg' => [
            'image' => 'w-8 h-8',
            'mark' => 32,
            'container' => 'gap-2.5',
            'text' => 'text-lg'
        ],
        'xl' => [
            'image' => 'w-10 h-10',
            'mark' => 40,
            'container' => 'gap-3',
            'text' => 'text-xl'
        ]
    ];
@endphp

<div class="flex items-center w-full {{ $sizes[$size]['container'] }}">
    @if($guildInstance)
        <img src="{{ $guildInstance->getMarkUrl($sizes[$size]['mark']) }}"
             alt="Guild Mark"
             class="{{ $sizes[$size]['image'] }} shrink-0 rounded border border-zinc-200 dark:border-white/10"
        />
    @else
        <span class="sm:hidden">—</span>
    @endif

    <span class="max-sm:hidden {{ $sizes[$size]['text'] }}">
        {{ $guildInstance?->G_Name ?? '—' }}
    </span>
</div>
