@props(['streams', 'currentIndex'])

<div class="fixed bottom-4 right-4 z-50 min-w-80">
    <div
        class="bg-slate-900/95 backdrop-blur-lg rounded-xl border border-purple-500/30 shadow-2xl shadow-purple-500/20 overflow-hidden transition-all duration-300 hover:shadow-purple-500/30">
        <div
            class="flex items-center justify-between p-3 bg-gradient-to-r from-purple-600/20 to-blue-600/20 border-b border-slate-700/50">
            <div class="flex items-center space-x-2">
                <div class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></div>
                <span class="text-white text-sm font-medium">LIVE</span>
                <span class="text-gray-300 text-xs"
                      x-text="`${getCurrentStream()?.average_viewers?.toLocaleString() || '0'} viewers`"></span>
                <template x-if="streams.length > 1">
                    <div class="flex items-center space-x-1">
                        <span class="text-gray-400 text-xs">•</span>
                        <span class="text-gray-400 text-xs" x-text="`${currentIndex + 1}/${streams.length}`"></span>
                    </div>
                </template>
            </div>
            <div class="flex items-center space-x-1">
                <x-stream-widget.navigation-buttons size="w-4 h-4"/>
                <button @click="minimize()"
                        class="p-1 text-gray-400 hover:text-white hover:bg-slate-700 rounded transition-colors"
                        title="Minimize">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                    </svg>
                </button>
                <button @click="close()"
                        class="p-1 text-gray-400 hover:text-red-400 hover:bg-slate-700 rounded transition-colors"
                        title="Close">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
        <div class="relative group">
            <div id="stream-player-container" class="w-80 h-48 bg-slate-800"></div>

            <template x-if="showCustomPlayButton">
                <div class="absolute inset-0 bg-black/50 flex items-center justify-center z-10">
                    <button
                        @click="playFromCustomButton()"
                        class="bg-purple-600 hover:bg-purple-700 rounded-full p-4 transition-colors"
                    >
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8 5v14l11-7z"/>
                        </svg>
                    </button>
                </div>
            </template>

            <x-stream-widget.stream-overlay/>
        </div>


        <x-stream-widget.stream-footer/>
    </div>
</div>
