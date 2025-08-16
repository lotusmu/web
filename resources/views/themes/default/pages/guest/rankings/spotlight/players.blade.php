<div class="flex items-center gap-3 whitespace-nowrap overflow-auto touch-pan-x no-scrollbar snap-x w-full">
    @foreach($this->winners as $winner)
        <div
            class="bg-gradient-to-t from-zinc-950/10 dark:from-white/10 to-transparent to-90% rounded-xl snap-center inline-flex items-center justify-center min-w-40 w-full min-h-72">
            <div class="p-6 space-y-6">
                <div>
                    <div
                        class="w-28 h-28 mx-auto rounded-tr-full rounded-tl-full p-1 bg-gradient-to-b {{ self::COLOR_CLASSES[$winner['color']]['gradient'] }} to-85%">
                        <img src="{{ asset($winner['image']) }}" alt="{{ $winner['class_name'] }}"
                             class="w-full h-full rounded-xl object-cover"/>
                    </div>
                </div>

                <div class="flex flex-col text-center space-y-3">
                    <flux:link variant="ghost"
                               href="{{ $winner['name'] !== self::DEFAULT_WINNERS[$winner['class']] ? route('character', ['name' => $winner['name']]) : '#' }}"
                               wire:navigate
                    >
                        {{ $winner['name'] }}
                    </flux:link>
                    <div
                        class="px-4 py-1.5 rounded-full bg-transparent dark:bg-black/30 inline-flex items-center gap-2 border {{ self::COLOR_CLASSES[$winner['color']]['border'] }}">
                        <div class="{{ self::COLOR_CLASSES[$winner['color']]['text'] }} text-sm mx-auto">
                            {{ __($winner['class_name']) }}
                        </div>
                    </div>

                    <flux:text size="sm">
                        @if($winner['hof_wins'] > 0)
                            {{ $winner['hof_wins'] }}x Hall of Famer
                        @endif
                    </flux:text>
                </div>
            </div>
        </div>
    @endforeach
</div>
