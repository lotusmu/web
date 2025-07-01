@props(['streams'])

<div class="lg:col-span-3">
    <flux:card class="overflow-hidden">
        <div class="relative bg-zinc-900 aspect-video">
            <div id="main-stream-player" class="w-full h-full">
                <!-- Player will be loaded by JavaScript -->
            </div>
        </div>

        <div class="mt-4" x-show="selectedStream">
            <flux:heading x-text="selectedStream?.title || '{{ __('Untitled Stream') }}'"/>

            <div class="flex max-sm:flex-col sm:items-center gap-2 mt-2">
                <div class="flex items-center gap-2">
                    <flux:text x-text="selectedStream?.channel_name"/>
                    <flux:text>•</flux:text>
                    <flux:text x-text="selectedStream?.game_category || '{{ __('No Category') }}'"/>
                </div>

                <flux:spacer/>

                <div class="flex items-center gap-2">
                    <flux:text
                        x-text="(selectedStream?.average_viewers || 0).toLocaleString() + ' {{ __('viewers') }}'"/>
                    <flux:text>•</flux:text>
                    <flux:text x-text="getDuration(selectedStream?.started_at)"/>
                </div>
            </div>
        </div>
    </flux:card>
</div>
