@props(['streams'])

<div>
    <flux:radio.group
        label="{{ __('Live Channels') }}"
        variant="cards"
        :indicator="false"
        wire:model.live="selectedStreamId"
        class="flex flex-col"
    >
        @foreach($streams as $stream)
            <flux:tooltip content="{{ $stream['title'] ?? __('Untitled Stream') }}" position="left">
                <flux:radio value="{{ $stream['id'] }}">
                    <div class="w-full">
                        <flux:heading class="flex items-center">
                            <span>{{ $stream['channel_name'] }}</span>
                            <flux:spacer/>
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 bg-red-500 rounded-full flex-shrink-0 animate-pulse"></div>
                                <flux:text size="sm">
                                    {{ number_format($stream['average_viewers'] ?? 0) }} {{ __('viewers') }}
                                </flux:text>
                            </div>
                        </flux:heading>
                        <flux:subheading size="sm">
                            {{ $stream['game_category'] ?? __('No Category') }}
                        </flux:subheading>
                    </div>
                </flux:radio>
            </flux:tooltip>
        @endforeach
    </flux:radio.group>
</div>
