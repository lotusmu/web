<div
    class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/90 via-black/50 to-transparent p-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
    <h3 class="text-white text-sm font-medium leading-tight line-clamp-2 mb-1"
        x-text="getCurrentStream()?.title || 'Untitled Stream'"></h3>
    <div class="flex items-center justify-between text-xs">
        <div class="flex items-center space-x-2 text-zinc-300">
            <span class="font-medium" x-text="getCurrentStream()?.channel_name"></span>
            <span>•</span>
            <span x-text="getCurrentStream()?.game_category || 'No Category'"></span>
        </div>
        <div class="flex items-center space-x-1 text-zinc-400">
            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                      clip-rule="evenodd"/>
            </svg>
            <span x-text="getDuration(getCurrentStream()?.started_at)"></span>
        </div>
    </div>
</div>
