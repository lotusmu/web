@props(['streams', 'currentIndex'])

<div class="fixed bottom-4 right-4 z-50 min-w-80">
    <div
        class="bg-gradient-to-r from-purple-600/15 to-blue-600/15 dark:from-purple-600/20 dark:to-blue-600/20 backdrop-blur-lg rounded-lg border border-purple-500/30 shadow-xl overflow-hidden">
        <div class="flex items-center justify-between p-2">
            <div class="flex items-center space-x-2">
                <div class="w-2 h-2 bg-red-500 rounded-full"></div>

                <flux:text size="sm" class="font-medium" x-text="getCurrentStream()?.channel_name"/>
                
                <flux:text size="sm">•</flux:text>

                <flux:text size="sm"
                           x-text="getCurrentStream()?.average_viewers?.toLocaleString() || '0'"/>

                <template x-if="streams.length > 1">
                    <flux:text size="sm"
                               x-text="`(${currentIndex + 1}/${streams.length})`"/>
                </template>
            </div>

            <div class="flex items-center space-x-1">
                <x-stream-widget.navigation-buttons/>

                <flux:button @click="resume()" icon="play" size="xs" variant="subtle"
                             class="hover:!text-purple-400"/>

                <flux:button @click="close()" icon="x-mark" size="xs" variant="subtle"
                             class="hover:!text-red-400"/>
            </div>
        </div>
    </div>
</div>
