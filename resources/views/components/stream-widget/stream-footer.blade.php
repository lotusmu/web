<div class="p-3 bg-slate-800/50 border-t border-slate-700/50">
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
        <template x-if="getCurrentStream()?.partner?.promo_code">
            <button @click="navigator.clipboard.writeText(getCurrentStream()?.partner?.promo_code)"
                    class="bg-slate-700 hover:bg-slate-600 text-gray-300 text-xs py-2 px-3 rounded-lg font-medium transition-colors flex items-center space-x-1"
                    title="Copy promo code">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
                <span x-text="getCurrentStream()?.partner?.promo_code"></span>
            </button>
        </template>
    </div>
</div>
