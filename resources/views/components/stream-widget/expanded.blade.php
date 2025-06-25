@props(['streams', 'currentIndex'])

<div class="fixed bottom-4 right-4 z-50 min-w-80">
    <div
        class="bg-white dark:bg-zinc-900/75 backdrop-blur-lg rounded-xl border border-purple-500/30 shadow-2xl shadow-zinc-500/60 dark:shadow-purple-500/20 overflow-hidden transition-all duration-300">
        <div
            class="flex items-center justify-between p-2 bg-gradient-to-r from-purple-600/15 to-blue-600/15 dark:from-purple-600/30 dark:to-blue-600/30">
            <div class="flex items-center space-x-2">
                <div class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></div>

                <flux:text class="font-medium">{{  __('LIVE') }}</flux:text>

                <flux:text size="sm"
                           x-text="`${getCurrentStream()?.average_viewers?.toLocaleString() || '0'} viewers`"
                />

                <template x-if="streams.length > 1">
                    <div class="flex items-center space-x-2">
                        <flux:text size="sm">•</flux:text>
                        <flux:text size="sm" x-text="`${currentIndex + 1}/${streams.length}`"></flux:text>
                    </div>
                </template>
            </div>

            <div class="flex items-center space-x-1">
                <x-stream-widget.navigation-buttons/>

                <flux:button @click="minimize()" icon="minus" size="xs" variant="subtle"/>

                <flux:button @click="close()" icon="x-mark" size="xs" variant="subtle"
                             class="hover:!text-red-400"/>
            </div>
        </div>
        <div class="relative group">
            <div id="stream-player-container" class="w-80 h-48 bg-zinc-800"></div>

            <x-stream-widget.stream-overlay/>
        </div>

        <x-stream-widget.stream-footer/>
    </div>
</div>
