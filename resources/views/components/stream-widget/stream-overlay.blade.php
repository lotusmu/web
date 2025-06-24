<div
    class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/90 via-black/50 to-transparent p-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
    <flux:heading x-text="getCurrentStream()?.title || 'Untitled Stream'"/>

    <div class="flex items-center justify-between text-xs mt-1">
        <div class="flex items-center space-x-2 ">
            <flux:text size="sm" class="font-medium" x-text="getCurrentStream()?.channel_name"/>

            <flux:text size="sm">•</flux:text>

            <flux:text size="sm" x-text="getCurrentStream()?.game_category || 'No Category'"/>
        </div>
        <div class="flex items-center space-x-1 ">
            <flux:icon.clock variant="micro" class="size-3"/>

            <flux:text size="sm" x-text="getDuration(getCurrentStream()?.started_at)"/>
        </div>
    </div>
</div>
