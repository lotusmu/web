@props([
   'video',
   'poster',
   'aspect' => 'aspect-video',
])

<div {{ $attributes->merge(['class' => 'mt-4 -mx-6 sm:mx-0 lg:mt-0']) }}>
    <div
        class="relative overflow-hidden shadow-xl flex bg-zinc-100 sm:rounded-xl dark:bg-zinc-900/70 dark:backdrop-blur ring-1 ring-inset ring-zinc-800/10 dark:ring-white/10 {{ $aspect }}">

        <div class="relative w-full flex flex-col">
            <div class="flex-none border-b border-zinc-500/30">
                <div class="flex items-center h-8 space-x-1.5 px-3">
                    <div class="w-2.5 h-2.5 bg-zinc-600 rounded-full"></div>
                    <div class="w-2.5 h-2.5 bg-zinc-600 rounded-full"></div>
                    <div class="w-2.5 h-2.5 bg-zinc-600 rounded-full"></div>
                </div>
            </div>

            <div class="relative min-h-0 flex-auto flex flex-col">
                <div class="w-full flex-auto flex min-h-0" style="opacity: 1;">
                    <div class="w-full flex-auto flex min-h-0 overflow-hidden">
                        <video
                            class="h-full w-full object-cover"
                            autoplay
                            muted
                            loop
                            playsinline
                            preload="metadata"
                            poster="{{ $poster }}"
                        >
                            <source
                                src="{{ $video }}"
                                type="video/mp4"
                            >
                        </video>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
