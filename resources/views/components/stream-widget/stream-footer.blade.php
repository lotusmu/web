<div class="p-3 bg-zinc-800/50 border-t border-zinc-700/50">
    <div class="flex space-x-2">
        <a :href="`https://twitch.tv/${getCurrentStream()?.channel_name}`"
           target="_blank"
           class="flex-1 bg-purple-600 hover:bg-purple-700 text-white text-xs py-2 px-3 rounded-lg font-medium text-center transition-colors flex items-center justify-center space-x-1">
            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                <path
                    d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 101.414 1.414L15 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5z"/>
                <path d="M5 5a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-3a1 1 0 10-2 0v3H5V7h3a1 1 0 000-2H5z"/>
            </svg>
            <span>Watch Full</span>
        </a>
        <a :href="`/partners/${getCurrentStream()?.partner_id}`"
           class="bg-zinc-700 hover:bg-zinc-600 text-zinc-300 text-xs py-2 px-3 rounded-lg font-medium transition-colors flex items-center space-x-1"
           title="View Partner">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-3">
                <path
                    d="M4.5 4.5a3 3 0 0 0-3 3v9a3 3 0 0 0 3 3h8.25a3 3 0 0 0 3-3v-9a3 3 0 0 0-3-3H4.5ZM19.94 18.75l-2.69-2.69V7.94l2.69-2.69c.944-.945 2.56-.276 2.56 1.06v11.38c0 1.336-1.616 2.005-2.56 1.06Z"/>
            </svg>
            <span>All Streams</span>
        </a>
    </div>
</div>
