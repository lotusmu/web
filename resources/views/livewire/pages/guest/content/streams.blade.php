<?php

use App\Actions\Stream\LoadActiveStreamsAction;
use App\Http\Resources\StreamResource;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public $streams = [];
    public $selectedStreamId = null;
    public $viewMode = 'grid'; // 'grid' or 'featured'

    public function mount(LoadActiveStreamsAction $loadStreams): void
    {
        // Load saved view preference
        $this->viewMode = request()->cookie('streams_view_mode', 'grid');
        $this->loadStreams($loadStreams);
    }

    public function loadStreams(LoadActiveStreamsAction $loadStreams): void
    {
        $streams       = $loadStreams->handle();
        $this->streams = StreamResource::collection($streams)->resolve();

        // Auto-select first stream if none selected
        if ( ! $this->selectedStreamId && ! empty($this->streams)) {
            $this->selectedStreamId = $this->streams[0]['id'];
        }
    }

    #[On('refresh-streams')]
    public function refreshStreams(LoadActiveStreamsAction $loadStreams)
    {
        $this->loadStreams($loadStreams);
    }

    public function updatedViewMode($value): void
    {
        $this->viewMode = $value;
        // Save preference to cookie
        cookie()->queue('streams_view_mode', $value, 60 * 24 * 30); // 30 days
    }

    public function getTotalViewersProperty(): int
    {
        return collect($this->streams)->sum('average_viewers');
    }

    public function getSelectedStreamProperty(): ?array
    {
        return collect($this->streams)->firstWhere('id', $this->selectedStreamId);
    }
}; ?>

<flux:main container>
    <x-page-header
        :title="__('Live Streaming Now')"
        :kicker="__('Live')"
        :description="__('Watch our content creators live and discover amazing gameplay moments.')"
    />

    <div wire:poll.120s="refreshStreams"
         x-data="streamsPageManager(@js($streams), @js($selectedStreamId), '{{ $viewMode }}')">
        <header class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-12">
            <flux:spacer/>

            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></div>
                    <flux:text class="font-medium">{{ count($streams) }} {{ __('Live') }}</flux:text>
                    <flux:text>•</flux:text>
                    <flux:text>{{ number_format($this->totalViewers) }} {{ __('viewers') }}</flux:text>
                </div>

                <flux:radio.group wire:model.live="viewMode" variant="segmented" size="sm">
                    <flux:radio value="grid" icon="squares-2x2"/>
                    <flux:radio value="featured" icon="tv"/>
                </flux:radio.group>

                <flux:button
                    wire:click="refreshStreams"
                    icon="arrow-path"
                    variant="filled"
                    size="sm"
                >
                </flux:button>
            </div>
        </header>

        @if(empty($streams))
            <div class="text-center py-12">
                <flux:icon.tv class="mx-auto h-12 w-12 text-gray-400 mb-4"/>
                <flux:heading>{{ __('No Live Streams') }}</flux:heading>
                <flux:subheading>{{ __('No partners are currently streaming. Check back later!') }}</flux:subheading>
            </div>
        @else
            <div x-show="viewMode === 'featured'">
                <!-- Featured View: Large player + radio group sidebar -->
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                    <!-- Main Player -->
                    <div class="lg:col-span-3">
                        <flux:card class="overflow-hidden">
                            <div class="relative bg-zinc-900 aspect-video" id="main-stream-player">
                                <!-- Player will be auto-loaded -->
                            </div>

                            <div class="mt-4" x-show="selectedStream">
                                <flux:heading
                                    x-text="selectedStream?.title || '{{ __('Untitled Stream') }}'"></flux:heading>

                                <div class="flex items-center gap-2 mt-2">
                                    <flux:text x-text="selectedStream?.channel_name"></flux:text>

                                    <flux:text>•</flux:text>

                                    <flux:text
                                        x-text="selectedStream?.game_category || '{{ __('No Category') }}'"></flux:text>

                                    <flux:spacer/>

                                    <flux:text
                                        x-text="(selectedStream?.average_viewers || 0).toLocaleString() + ' {{ __('viewers') }}'"></flux:text>

                                    <flux:text>•</flux:text>

                                    <flux:text x-text="getDuration(selectedStream?.started_at)"></flux:text>
                                </div>
                            </div>
                        </flux:card>
                    </div>

                    <!-- Stream Selection Radio Group -->
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
                                                    <div
                                                        class="w-2 h-2 bg-red-500 rounded-full flex-shrink-0 animate-pulse"></div>
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
                </div>
            </div>

            <div x-show="viewMode === 'grid'">
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    <template x-for="(stream, index) in streams" :key="stream.id">
                        <flux:card class="overflow-hidden">
                            <div class="relative bg-zinc-900 aspect-video" x-bind:id="'stream-player-' + stream.id">
                                <!-- Player will be auto-loaded with no controls -->
                            </div>

                            <div class="mt-4">
                                <flux:heading class="truncate"
                                              x-text="stream.title || '{{ __('Untitled Stream') }}'"></flux:heading>

                                <flux:subheading x-text="stream.channel_name"></flux:subheading>

                                <div class="flex items-center gap-2 mt-1">
                                    <flux:text size="sm"
                                               x-text="stream.game_category || '{{ __('No Category') }}'"></flux:text>
                                    <flux:text size="sm">•</flux:text>
                                    <flux:text size="sm"
                                               x-text="(stream.average_viewers || 0).toLocaleString() + ' {{ __('viewers') }}'"></flux:text>
                                </div>

                                <div class="mt-4">
                                    <flux:button
                                        x-bind:href="'https://twitch.tv/' + stream.channel_name"
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
                    </template>
                </div>
            </div>
        @endif
    </div>
</flux:main>

<script>
    window.streamsPageManager = function (initialStreams, initialSelectedStreamId, initialViewMode) {
        return {
            streams: initialStreams,
            selectedStreamId: initialSelectedStreamId,
            viewMode: localStorage.getItem('streams_view_mode') || initialViewMode,
            selectedStream: null,
            mainPlayer: null,
            gridPlayers: {},

            init() {
                this.updateSelectedStream();

                // Auto-load players when page loads
                this.loadAllPlayers();

                // Watch for changes in selectedStreamId
                this.$watch('selectedStreamId', () => {
                    this.updateSelectedStream();
                    if (this.viewMode === 'featured' && this.selectedStream) {
                        this.loadMainPlayer();
                    }
                });

                // Listen for Livewire updates
                this.$wire.on('refresh-streams', () => {
                    this.streams = this.$wire.streams;
                    this.updateSelectedStream();
                    this.loadAllPlayers();
                });

                // Watch for view mode changes from Livewire
                this.$watch('$wire.viewMode', (newMode) => {
                    this.viewMode = newMode;
                    localStorage.setItem('streams_view_mode', newMode);
                    if (newMode === 'featured' && this.selectedStream) {
                        this.$nextTick(() => this.loadMainPlayer());
                    }
                });
            },

            loadAllPlayers() {
                // Load grid players
                this.streams.forEach(stream => {
                    this.$nextTick(() => this.loadGridPlayer(stream.id, stream));
                });

                // Load main player if in featured mode
                if (this.viewMode === 'featured' && this.selectedStream) {
                    this.$nextTick(() => this.loadMainPlayer());
                }
            },

            updateSelectedStream() {
                this.selectedStream = this.streams.find(stream => stream.id == this.selectedStreamId) || null;
            },

            loadMainPlayer() {
                if (!this.selectedStream || this.viewMode !== 'featured') return;

                const container = document.getElementById('main-stream-player');
                if (!container) return;

                // Clear existing player
                if (this.mainPlayer) {
                    try {
                        this.mainPlayer.destroy();
                    } catch (e) {
                        console.log('Error destroying main player:', e);
                    }
                }

                // Clear container
                container.innerHTML = '';

                try {
                    this.mainPlayer = new Twitch.Embed('main-stream-player', {
                        width: '100%',
                        height: '100%',
                        channel: this.selectedStream.channel_name,
                        parent: [window.location.hostname],
                        muted: false,
                        autoplay: true,
                        controls: true,
                        layout: 'video'
                    });

                    this.mainPlayer.addEventListener(Twitch.Embed.VIDEO_READY, () => {
                        console.log('Main player ready');
                    });

                } catch (error) {
                    console.error('Failed to create main Twitch embed:', error);
                    this.fallbackToIframe(container, this.selectedStream);
                }
            },

            loadGridPlayer(streamId, stream) {
                const containerId = `stream-player-${streamId}`;
                const container = document.getElementById(containerId);
                if (!container) return;

                // Clear existing player for this stream
                if (this.gridPlayers[streamId]) {
                    try {
                        this.gridPlayers[streamId].destroy();
                    } catch (e) {
                        console.log('Error destroying grid player:', e);
                    }
                }

                // Clear container
                container.innerHTML = '';

                try {
                    this.gridPlayers[streamId] = new Twitch.Embed(containerId, {
                        width: '100%',
                        height: '100%',
                        channel: stream.channel_name,
                        parent: [window.location.hostname],
                        muted: true,
                        autoplay: true,
                        controls: false, // No controls for clean look
                        layout: 'video'
                    });

                } catch (error) {
                    console.error('Failed to create grid Twitch embed:', error);
                    this.fallbackToIframe(container, stream);
                }
            },

            fallbackToIframe(container, stream) {
                const domain = window.location.hostname;
                const src = `https://player.twitch.tv/?channel=${stream.channel_name}&parent=${domain}&muted=true&autoplay=true&controls=false&playsinline=true`;

                const iframe = document.createElement('iframe');
                iframe.src = src;
                iframe.frameBorder = '0';
                iframe.scrolling = 'no';
                iframe.allowFullscreen = true;
                iframe.className = 'w-full h-full';
                iframe.allow = 'autoplay; fullscreen';

                container.appendChild(iframe);
            },

            getDuration(startedAt) {
                if (!startedAt) return '0m';

                const start = new Date(startedAt);
                const now = new Date();
                const diff = Math.floor((now - start) / 1000 / 60);

                if (diff >= 60) {
                    const hours = Math.floor(diff / 60);
                    const minutes = diff % 60;
                    return `${hours}h ${minutes}m`;
                }
                return `${diff}m`;
            }
        };
    };
</script>
