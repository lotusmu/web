<template x-if="streams.length > 1">
    <div class="flex items-center space-x-1">
        <flux:button @click="previousStream()" icon="chevron-left" size="xs" variant="subtle"/>

        <flux:button @click="nextStream()" icon="chevron-right" size="xs" variant="subtle"/>
    </div>
</template>
