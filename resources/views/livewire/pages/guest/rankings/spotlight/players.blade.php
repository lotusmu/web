<?php

use Livewire\Volt\Component;

new class extends Component {
    public array $winners = [
        [
            'name'       => 'Knight',
            'class'      => 'dk',
            'class_name' => 'Dark Knight',
            'color'      => 'red',
            'image'      => 'images/characters/dk.png'
        ],
        [
            'name'       => 'Wizard',
            'class'      => 'dw',
            'class_name' => 'Dark Wizard',
            'color'      => 'blue',
            'image'      => 'images/characters/dw.png'
        ],
        [
            'name'       => 'Elf',
            'class'      => 'fe',
            'class_name' => 'Muse Elf',
            'color'      => 'green',
            'image'      => 'images/characters/fe.png'
        ],
        [
            'name'       => 'Gladiator',
            'class'      => 'mg',
            'class_name' => 'Magic Gladiator',
            'color'      => 'purple',
            'image'      => 'images/characters/mg.png'
        ],
        [
            'name'       => 'Lord',
            'class'      => 'dl',
            'class_name' => 'Dark Lord',
            'color'      => 'yellow',
            'image'      => 'images/characters/dl.png'
        ]
    ];

    public array $colorClasses = [
        'red'    => [
            'gradient' => 'from-red-700 via-red-600 to-transparent dark:from-red-600 dark:via-red-700',
            'border'   => 'border-red-600/90 dark:border-red-400/30',
            'text'     => 'text-red-600 dark:text-red-400'
        ],
        'blue'   => [
            'gradient' => 'from-blue-700 via-blue-600 to-transparent dark:from-blue-600 dark:via-blue-700',
            'border'   => 'border-blue-600/90 dark:border-blue-400/30',
            'text'     => 'text-blue-600 dark:text-blue-400'
        ],
        'green'  => [
            'gradient' => 'from-green-700 via-green-600 to-transparent dark:from-green-600 dark:via-green-700',
            'border'   => 'border-green-600/90 dark:border-green-400/30',
            'text'     => 'text-green-600 dark:text-green-400'
        ],
        'purple' => [
            'gradient' => 'from-purple-700 via-purple-600 to-transparent dark:from-purple-600 dark:via-purple-700',
            'border'   => 'border-purple-600/90 dark:border-purple-400/30',
            'text'     => 'text-purple-600 dark:text-purple-400'
        ],
        'yellow' => [
            'gradient' => 'from-yellow-700 via-yellow-600 to-transparent dark:from-yellow-600 dark:via-yellow-700',
            'border'   => 'border-yellow-600/90 dark:border-yellow-400/30',
            'text'     => 'text-yellow-600 dark:text-yellow-400'
        ]
    ];
} ?>

<div class="flex items-center gap-8 whitespace-nowrap overflow-auto touch-pan-x no-scrollbar snap-x w-full">
    @foreach($winners as $winner)
        <div
            class="bg-gradient-to-t from-zinc-950/10 dark:from-white/10 to-transparent to-90% rounded-xl snap-center inline-flex items-center justify-center min-w-48 w-full min-h-64">
            <div class="p-6 space-y-6">
                <div>
                    <div
                        class="w-28 h-28 mx-auto rounded-tr-full rounded-tl-full p-1 bg-gradient-to-b {{ $colorClasses[$winner['color']]['gradient'] }} to-85%">
                        <img src="{{ asset($winner['image']) }}" alt="{{ $winner['class_name'] }}"
                             class="w-full h-full rounded-xl object-cover"/>
                    </div>
                </div>

                <div class="flex flex-col text-center space-y-3">
                    <flux:link variant="ghost" href="#">{{ $winner['name'] }}</flux:link>
                    <div
                        class="px-4 py-1.5 rounded-full bg-transparent dark:bg-black/30 inline-flex items-center gap-2 border {{ $colorClasses[$winner['color']]['border'] }}">
                        <div class="{{ $colorClasses[$winner['color']]['text'] }} text-sm mx-auto">
                            {{ $winner['class_name'] }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
