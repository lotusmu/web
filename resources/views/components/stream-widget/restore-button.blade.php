<div x-show="!visible && streams.length > 0" class="fixed bottom-4 right-4 z-40">
    <flux:button
        @click="show()"
        icon="play" class="!bg-purple-600 hover:!bg-purple-700 !text-white !border-none !rounded-full"
    />
</div>
