<div x-show="!visible && streams.length > 0" class="fixed bottom-4 right-4 z-40">
    <button @click="show()"
            class="bg-purple-600/90 hover:bg-purple-700 text-white p-2 rounded-full shadow-lg backdrop-blur-sm border border-purple-500/50 transition-all duration-300 hover:scale-110"
            title="Show live streams">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path
                d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/>
        </svg>
    </button>
</div>
