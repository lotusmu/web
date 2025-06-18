@props(['size' => 'w-4 h-4'])

<template x-if="streams.length > 1">
    <div class="flex items-center space-x-1">
        <button @click="previousStream()"
                class="p-1 text-gray-400 hover:text-white hover:bg-slate-700 rounded transition-colors"
                title="Previous">
            <svg class="{{ $size }}" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                      d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                      clip-rule="evenodd"/>
            </svg>
        </button>
        <button @click="nextStream()"
                class="p-1 text-gray-400 hover:text-white hover:bg-slate-700 rounded transition-colors" title="Next">
            <svg class="{{ $size }}" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                      d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                      clip-rule="evenodd"/>
            </svg>
        </button>
    </div>
</template>
