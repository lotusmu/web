<?php

use App\Models\Stream\StreamSession;
use Livewire\Volt\Component;

new class extends Component {
    public $streams = [];

    public function mount()
    {
        $this->loadStreams();
    }

    public function loadStreams()
    {
        $this->streams = StreamSession::with(['partner.user'])
            ->whereNull('ended_at')
            ->orderByDesc('average_viewers')
            ->get()
            ->toArray();
    }

    public function refreshStreams()
    {
        $this->loadStreams();
        $this->dispatch('streams-updated');
    }
}; ?>

<div
    wire:poll.60s="refreshStreams"
    x-data="streamWidget(@js($streams))"
    x-init="init()"
>
    <div>
        <template x-if="visible && streams.length > 0">
            <div x-show="minimized">
                <x-stream-widget.minimized/>
            </div>
        </template>

        <template x-if="visible && streams.length > 0">
            <div x-show="!minimized">
                <x-stream-widget.expanded/>
            </div>
        </template>
    </div>

    <x-stream-widget.restore-button/>
</div>


<script>
    window.streamWidget = function (initialStreams) {
        return {
            visible: localStorage.getItem('stream-widget-visible') !== 'false',
            minimized: localStorage.getItem('stream-widget-minimized') === 'true',
            muted: localStorage.getItem('stream-widget-muted') !== 'false',
            streams: initialStreams,
            currentIndex: 0,
            twitchPlayer: null,
            currentChannelName: null,
            isTabVisible: true,
            showCustomPlayButton: false,
            initializationId: null,

            init() {
                // Generate unique initialization ID to prevent conflicts
                this.initializationId = Date.now() + Math.random();

                // Cleanup any existing instance
                if (window.streamWidgetInstance && window.streamWidgetInstance.initializationId !== this.initializationId) {
                    window.streamWidgetInstance.destroy();
                }

                window.streamWidgetInstance = this;

                // Track tab visibility
                this.isTabVisible = !document.hidden;

                if (this.streams.length > 0 && !this.minimized && this.visible) {
                    this.$nextTick(() => this.loadPlayer());
                }

                // Listen for Livewire updates
                this.$wire.on('streams-updated', () => {
                    this.updateStreams(this.$wire.streams);
                });

                // Enhanced visibility change handler
                document.addEventListener('visibilitychange', () => {
                    this.isTabVisible = !document.hidden;

                    if (this.isTabVisible) {
                        // Tab became visible
                        if (!this.minimized && this.visible && this.streams.length > 0) {
                            if (this.showCustomPlayButton) {
                                // Keep showing custom play button
                                return;
                            }
                            // Try to resume after a short delay
                            setTimeout(() => {
                                this.resumePlayer();
                            }, 200);
                        }
                    } else {
                        // Tab became hidden - show custom play button instead of pausing
                        if (!this.minimized && this.visible && this.twitchPlayer) {
                            this.showCustomPlayButton = true;
                        }
                    }
                });
            },

            updateStreams(newStreams) {
                this.streams = newStreams || [];

                if (this.currentIndex >= this.streams.length) {
                    this.currentIndex = 0;
                }

                // Reset custom play button if no streams
                if (this.streams.length === 0) {
                    this.showCustomPlayButton = false;
                }

                // Reload player if expanded and visible
                if (!this.minimized && this.visible && this.streams.length > 0) {
                    this.$nextTick(() => this.loadPlayer());
                }
            },

            loadPlayer() {
                const stream = this.getCurrentStream();
                if (!stream || this.minimized || !this.visible) return;

                const container = document.getElementById('stream-player-container');
                if (!container) {
                    // Container doesn't exist - probably page changed
                    console.log('Player container not found, resetting player state');
                    this.twitchPlayer = null;
                    this.currentChannelName = null;
                    return;
                }

                // Hide custom play button when loading player
                this.showCustomPlayButton = false;

                // Check if we need to recreate the player
                const hasValidPlayer = this.twitchPlayer &&
                    container.querySelector('iframe, div[data-twitch-embed]');

                const channelChanged = this.currentChannelName !== stream.channel_name;

                // Don't recreate if we have a valid player for the same channel
                if (hasValidPlayer && !channelChanged) {
                    return;
                }

                // Store current channel name
                this.currentChannelName = stream.channel_name;

                // Destroy existing player properly
                this.destroyPlayer();

                // Clear container
                container.innerHTML = '';

                // Create Twitch embed using the SDK with video-only layout
                const options = {
                    width: '100%',
                    height: '100%',
                    channel: stream.channel_name,
                    parent: [window.location.hostname],
                    muted: this.muted,
                    autoplay: true,
                    controls: false,
                    layout: 'video'
                };

                try {
                    this.twitchPlayer = new Twitch.Embed(container, options);

                    // Set up event listeners
                    this.twitchPlayer.addEventListener(Twitch.Embed.VIDEO_READY, () => {
                        const player = this.twitchPlayer.getPlayer();

                        // Set mute state
                        if (this.muted) {
                            player.setMuted(true);
                        }

                        // Set volume
                        player.setVolume(this.muted ? 0 : 0.5);
                    });

                    this.twitchPlayer.addEventListener(Twitch.Embed.OFFLINE, () => {
                        console.log('Stream went offline');
                        // Could trigger a refresh of streams here
                    });

                } catch (error) {
                    console.error('Failed to create Twitch embed:', error);
                    // Fallback to iframe method
                    this.fallbackToIframe(container, stream);
                }
            },

            resumePlayer() {
                // Only resume if tab is visible and not showing custom play button
                if (this.isTabVisible && !this.showCustomPlayButton && this.visible && !this.minimized) {
                    this.loadPlayer();
                }
            },

            // Method to handle custom play button click
            playFromCustomButton() {
                this.showCustomPlayButton = false;
                this.loadPlayer();
            },

            destroyPlayer() {
                if (this.twitchPlayer) {
                    try {
                        this.twitchPlayer.destroy();
                    } catch (e) {
                        console.log('Error destroying player:', e);
                    }
                    this.twitchPlayer = null;
                }
                this.currentChannelName = null;
            },

            fallbackToIframe(container, stream) {
                const domain = window.location.hostname;
                const src = `https://player.twitch.tv/?channel=${stream.channel_name}&parent=${domain}&muted=${this.muted}&autoplay=true&controls=false&playsinline=true`;

                const iframe = document.createElement('iframe');
                iframe.src = src;
                iframe.frameBorder = '0';
                iframe.scrolling = 'no';
                iframe.allowFullscreen = true;
                iframe.className = 'w-full h-full';
                iframe.allow = 'autoplay; fullscreen';

                container.appendChild(iframe);
            },

            getCurrentStream() {
                return this.streams[this.currentIndex] || null;
            },

            toggleMute() {
                this.muted = !this.muted;
                this.savePreferences();

                if (this.twitchPlayer) {
                    const player = this.twitchPlayer.getPlayer();
                    if (player) {
                        player.setMuted(this.muted);
                        player.setVolume(this.muted ? 0 : 0.5);
                    }
                }
            },

            // User actions
            nextStream() {
                if (this.streams.length <= 1) return;
                this.currentIndex = (this.currentIndex + 1) % this.streams.length;
                this.showCustomPlayButton = false;
                // Force reload for new channel
                this.currentChannelName = null;
                this.loadPlayer();
            },

            previousStream() {
                if (this.streams.length <= 1) return;
                this.currentIndex = (this.currentIndex - 1 + this.streams.length) % this.streams.length;
                this.showCustomPlayButton = false;
                // Force reload for new channel
                this.currentChannelName = null;
                this.loadPlayer();
            },

            minimize() {
                this.minimized = true;
                this.savePreferences();

                // Destroy player when minimizing to stop playback
                this.destroyPlayer();
                this.showCustomPlayButton = false;

                const container = document.getElementById('stream-player-container');
                if (container) {
                    container.innerHTML = '';
                }
            },

            resume() {
                this.minimized = false;
                this.showCustomPlayButton = false;
                this.savePreferences();
                this.$nextTick(() => this.loadPlayer());
            },

            close() {
                this.visible = false;
                this.savePreferences();

                // Properly destroy player and reset state
                this.destroyPlayer();
                this.showCustomPlayButton = false;

                const container = document.getElementById('stream-player-container');
                if (container) {
                    container.innerHTML = '';
                }
            },

            show() {
                this.visible = true;
                this.showCustomPlayButton = false;
                this.savePreferences();

                // Reset current channel to force reload
                this.currentChannelName = null;

                if (!this.minimized && this.streams.length > 0) {
                    this.$nextTick(() => this.loadPlayer());
                }
            },

            // Cleanup method for proper destruction
            destroy() {
                this.destroyPlayer();

                const container = document.getElementById('stream-player-container');
                if (container) {
                    container.innerHTML = '';
                }

                // Remove from window if this is the current instance
                if (window.streamWidgetInstance === this) {
                    window.streamWidgetInstance = null;
                }
            },

            savePreferences() {
                localStorage.setItem('stream-widget-visible', this.visible);
                localStorage.setItem('stream-widget-minimized', this.minimized);
                localStorage.setItem('stream-widget-muted', this.muted);
            },

            getDuration(startedAt) {
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
