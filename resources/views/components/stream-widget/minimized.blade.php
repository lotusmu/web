@props(['streams', 'currentIndex'])

<div class="fixed bottom-4 right-4 z-50 min-w-80">
    <div class="bg-zinc-900/95 backdrop-blur-lg rounded-lg border border-purple-500/30 shadow-xl overflow-hidden">
        <div class="flex items-center justify-between p-2 pr-3">
            <div class="flex items-center space-x-2">
                <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                <span class="text-white text-xs font-medium" x-text="getCurrentStream()?.channel_name"></span>
                <span class="text-zinc-400 text-xs">•</span>
                <span class="text-zinc-400 text-xs"
                      x-text="getCurrentStream()?.average_viewers?.toLocaleString() || '0'"></span>
                <template x-if="streams.length > 1">
                    <span class="text-zinc-500 text-xs" x-text="`(${currentIndex + 1}/${streams.length})`"></span>
                </template>
            </div>
            <div class="flex items-center space-x-1">
                <x-stream-widget.navigation-buttons size="w-3 h-3"/>
                <button @click="resume()"
                        class="p-1 text-zinc-400 hover:text-purple-400 hover:bg-zinc-700 rounded transition-colors"
                        title="Resume">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/>
                    </svg>
                </button>
                <button @click="close()"
                        class="p-1 text-zinc-400 hover:text-red-400 hover:bg-zinc-700 rounded transition-colors"
                        title="Close">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>
