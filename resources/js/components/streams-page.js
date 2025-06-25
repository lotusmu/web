/**
 * Refactored Streams Page Component
 * Uses StreamManager for better resource management
 */
window.streamsPage = function (initialStreams, initialSelectedStreamId, initialViewMode) {
    return {
        // State
        streams: initialStreams || [],
        selectedStreamId: initialSelectedStreamId,
        viewMode: initialViewMode,
        selectedStream: null,
        isPollingActive: true,

        // Internal
        manager: null,
        visibilityKey: null,
        loadingPlayers: new Set(), // Track loading players

        init() {
            // Create manager instance
            this.manager = new StreamManager('streams-page', {
                debounceMs: 200
            });

            this.updateSelectedStream();
            this.setupEventListeners();
            this.setupWatchers();
            this.loadAllPlayers();
        },

        setupEventListeners() {
            // Tab visibility handling with debouncing
            this.visibilityKey = this.manager.addEventListener(
                document,
                'visibilitychange',
                this.handleVisibilityChange.bind(this)
            );
        },

        setupWatchers() {
            // Watch for selectedStreamId changes
            this.$watch('selectedStreamId', () => {
                this.updateSelectedStream();
                if (this.viewMode === 'featured' && this.selectedStream) {
                    this.loadMainPlayer();
                }
            });

            // Watch for Livewire selectedStreamId changes
            this.$watch('$wire.selectedStreamId', (newSelectedStreamId) => {
                if (newSelectedStreamId !== this.selectedStreamId) {
                    this.selectedStreamId = newSelectedStreamId;
                }
            });

            // Watch for view mode changes
            this.$watch('$wire.viewMode', (newMode) => {
                if (newMode !== this.viewMode) {
                    this.viewMode = newMode;
                    this.loadAllPlayers();
                }
            });
        },

        handleVisibilityChange() {
            const isHidden = document.hidden;

            // Handle all players consistently
            this.manager.players.forEach((playerData, containerId) => {
                if (playerData.instance.isFallback) return;

                try {
                    const twitchPlayer = playerData.instance.getPlayer();
                    if (twitchPlayer) {
                        if (isHidden) {
                            twitchPlayer.pause();
                        } else {
                            twitchPlayer.play();
                        }
                    }
                } catch (error) {
                    // Ignore API errors
                }
            });
        },

        loadAllPlayers() {
            // Prevent multiple simultaneous calls
            if (this.loadingPlayers.size > 0) return;

            if (this.viewMode === 'featured') {
                this.cleanupGridPlayers();
                if (this.selectedStream) {
                    this.loadMainPlayer();
                }
            } else {
                this.cleanupMainPlayer();
                this.streams.forEach(stream => {
                    this.loadGridPlayer(stream.id, stream);
                });
            }
        },

        async loadMainPlayer() {
            if (!this.selectedStream) return;

            const containerId = 'main-stream-player';

            // Don't reload if already showing correct stream
            const existing = this.manager.players.get(containerId);
            if (existing && existing.options.channel === this.selectedStream.channel_name) {
                return;
            }

            const player = await this.manager.createPlayer(containerId, {
                channel: this.selectedStream.channel_name,
                muted: false,
                controls: true
            });

            if (player && !player.isFallback) {
                this.setupMainPlayerEvents(player);
            }
        },

        setupMainPlayerEvents(player) {
            player.addEventListener(Twitch.Embed.VIDEO_READY, () => {
                const twitchPlayer = player.getPlayer();
                if (twitchPlayer) {
                    twitchPlayer.setVolume(0.7);
                }
            });

            player.addEventListener(Twitch.Embed.OFFLINE, () => {
                console.log('Main stream went offline');
            });
        },

        async loadGridPlayer(streamId, stream) {
            const containerId = `stream-player-${streamId}`;

            // Don't reload if already exists OR currently loading
            if (this.manager.players.has(containerId) || this.loadingPlayers.has(containerId)) {
                return;
            }

            // Check if container already has content (prevent double loading)
            const container = document.getElementById(containerId);
            if (!container) return;

            if (container.querySelector('iframe, div[data-twitch-embed]')) {
                console.log(`Player ${containerId} already has content, skipping`);
                return;
            }

            // Mark as loading
            this.loadingPlayers.add(containerId);

            try {
                const player = await this.manager.createPlayer(containerId, {
                    channel: stream.channel_name,
                    muted: true,
                    controls: false
                });

                if (player && !player.isFallback) {
                    this.setupGridPlayerEvents(player, streamId);
                }
            } finally {
                // Remove loading state
                this.loadingPlayers.delete(containerId);
            }
        },

        setupGridPlayerEvents(player, streamId) {
            player.addEventListener(Twitch.Embed.OFFLINE, () => {
                console.log(`Grid stream ${streamId} went offline`);
                // Could trigger a refresh or show offline state
            });
        },

        cleanupMainPlayer() {
            this.manager.destroyPlayer('main-stream-player');
        },

        cleanupGridPlayers() {
            // Get all grid player container IDs
            const gridPlayerIds = Array.from(this.manager.players.keys())
                .filter(id => id.startsWith('stream-player-'));

            gridPlayerIds.forEach(containerId => {
                this.manager.destroyPlayer(containerId);
            });
        },

        updateSelectedStream() {
            this.selectedStream = this.streams.find(stream => stream.id == this.selectedStreamId) || null;

            if (!this.selectedStream && this.streams.length > 0) {
                this.selectedStreamId = this.streams[0].id;
                this.selectedStream = this.streams[0];
            }
        },

        // Polling controls
        pausePolling() {
            this.isPollingActive = false;
            this.$wire.call('pausePolling');
        },

        resumePolling() {
            if (!this.isPollingActive) {
                this.isPollingActive = true;
                this.$wire.call('resumePolling');
            }
        },

        // Utilities
        getDuration(startedAt) {
            return StreamManager.formatDuration(startedAt);
        },

        // Cleanup
        destroy() {
            if (this.manager) {
                this.manager.destroy();
                this.manager = null;
            }
        }
    };
};
