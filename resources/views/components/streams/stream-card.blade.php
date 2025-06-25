@props(['stream'])

<flux:card class="overflow-hidden">
    <div class="relative bg-zinc-900 aspect-video">
        <div id="stream-player-{{ $stream['id'] }}" class="w-full h-full">
            <!-- Player will be loaded lazily by JavaScript -->
        </div>
    </div>

    <div class="mt-4">
        <flux:heading class="truncate">
            {{ $stream['title'] ?? __('Untitled Stream') }}
        </flux:heading>
        <flux:subheading>{{ $stream['channel_name'] }}</flux:subheading>

        <div class="flex items-center gap-2 mt-1">
            <flux:text size="sm">{{ $stream['game_category'] ?? __('No Category') }}</flux:text>
            <flux:text size="sm">•</flux:text>
            <flux:text size="sm">{{ number_format($stream['average_viewers'] ?? 0) }} {{ __('viewers') }}</flux:text>
        </div>

        <div class="mt-4">
            <flux:button
                href="https://twitch.tv/{{ $stream['channel_name'] }}"
                external
                variant="filled"
                icon="arrow-top-right-on-square"
                size="sm"
                class="w-full"
            >
                {{ __('Watch on Twitch') }}
            </flux:button>
        </div>
    </div>
</flux:card>
