/**
 * Refactored Stream Widget Component
 * Uses StreamManager for better resource management
 */
window.streamWidget = function (initialStreams, initialVisible = null, initialMinimized = null) {
    return {
        // State
        visible: initialVisible !== null ? initialVisible : getInitialVisibility(),
        minimized: initialMinimized !== null ? initialMinimized : StreamManager.storage.get('widget-minimized', false),
        muted: StreamManager.storage.get('widget-muted', true),
        streams: initialStreams || [],
        currentIndex: 0,
        isTabVisible: true,

        // Internal
        manager: null,
        currentChannelName: null,
        visibilityKey: null,

        init() {
            // Prevent double initialization
            if (this.manager && !this.manager.isDestroyed) {
                return;
            }

            // Destroy any existing widget manager first
            if (StreamManager.instances.has('stream-widget')) {
                StreamManager.instances.get('stream-widget').destroy();
            }

            // Create manager instance
            this.manager = new StreamManager('stream-widget', {
                debounceMs: 300
            });

            this.loadSavedPreferences();
            this.setupEventListeners();

            // Initial load
            if (this.shouldLoadPlayer()) {
                this.$nextTick(() => this.loadPlayer());
            }
        },

        loadSavedPreferences() {
            const savedVisible = StreamManager.storage.get('widget-visible');
            const savedMinimized = StreamManager.storage.get('widget-minimized');

            if (savedVisible !== null) {
                this.visible = savedVisible;
            }

            if (savedMinimized !== null) {
                this.minimized = savedMinimized;
            }
        },

        setupEventListeners() {
            this.isTabVisible = !document.hidden;

            // Livewire stream updates
            this.$wire.on('streams-updated', () => {
                this.updateStreams(this.$wire.streams);
            });

            // Tab visibility handling
            this.visibilityKey = this.manager.addEventListener(
                document,
                'visibilitychange',
                this.handleVisibilityChange.bind(this)
            );
        },

        handleVisibilityChange() {
            this.isTabVisible = !document.hidden;

            if (this.isTabVisible) {
                // Tab becomes visible - try to resume
                const playerData = this.manager.players.get('stream-player-container');
                if (playerData && !playerData.instance.isFallback) {
                    try {
                        playerData.instance.play();
                    } catch (error) {
                        console.error('Error resuming widget player:', error);
                    }
                }
            } else {
                // Tab becomes hidden - pause player
                const playerData = this.manager.players.get('stream-player-container');
                if (playerData && !playerData.instance.isFallback) {
                    try {
                        playerData.instance.pause();
                    } catch (error) {
                        console.error('Error pausing widget player:', error);
                    }
                }
            }
        },

        loadPlayer() {
            const stream = this.getCurrentStream();
            if (!stream || !this.shouldLoadPlayer()) return;

            // Check if we need to reload
            if (this.currentChannelName === stream.channel_name && this.hasValidPlayer()) {
                return;
            }

            this.currentChannelName = stream.channel_name;

            // Create new player
            this.manager.createPlayer('stream-player-container', {
                channel: stream.channel_name,
                muted: this.muted,
                controls: false
            }).then(player => {
                if (player && !player.isFallback) {
                    this.setupPlayerEvents(player);
                }
            });
        },

        setupPlayerEvents(player) {
            player.addEventListener(Twitch.Embed.VIDEO_READY, () => {
                const twitchPlayer = player.getPlayer();
                if (twitchPlayer) {
                    twitchPlayer.setMuted(this.muted);
                    twitchPlayer.setVolume(this.muted ? 0 : 0.5);
                }
            });

            player.addEventListener(Twitch.Embed.OFFLINE, () => {
                console.log('Stream went offline');
            });
        },

        hasValidPlayer() {
            const playerData = this.manager.players.get('stream-player-container');
            if (!playerData) return false;

            const container = document.getElementById('stream-player-container');
            return container && container.querySelector('iframe, div[data-twitch-embed]');
        },

        shouldLoadPlayer() {
            return !this.minimized && this.visible && this.streams.length > 0;
        },

        // Navigation
        nextStream() {
            if (this.streams.length <= 1) return;

            this.currentIndex = (this.currentIndex + 1) % this.streams.length;
            this.currentChannelName = null;
            this.loadPlayer();
        },

        previousStream() {
            if (this.streams.length <= 1) return;

            this.currentIndex = (this.currentIndex - 1 + this.streams.length) % this.streams.length;
            this.currentChannelName = null;
            this.loadPlayer();
        },

        // Actions
        minimize() {
            this.minimized = true;
            this.savePreferences();
            this.destroyPlayer();
        },

        resume() {
            this.minimized = false;
            this.currentChannelName = null;
            this.savePreferences();
            this.$nextTick(() => this.loadPlayer());
        },

        close() {
            this.visible = false;
            this.savePreferences();
            this.destroyPlayer();
        },

        show() {
            this.visible = true;
            this.currentChannelName = null;
            this.savePreferences();

            if (this.shouldLoadPlayer()) {
                this.$nextTick(() => this.loadPlayer());
            }
        },

        toggleMute() {
            this.muted = !this.muted;
            this.savePreferences();

            const playerData = this.manager.players.get('stream-player-container');
            if (playerData && !playerData.instance.isFallback) {
                const twitchPlayer = playerData.instance.getPlayer();
                if (twitchPlayer) {
                    twitchPlayer.setMuted(this.muted);
                    twitchPlayer.setVolume(this.muted ? 0 : 0.5);
                }
            }
        },

        destroyPlayer() {
            this.manager.destroyPlayer('stream-player-container');
            this.currentChannelName = null;
        },

        // Utilities
        updateStreams(newStreams) {
            this.streams = newStreams || [];

            if (this.currentIndex >= this.streams.length) {
                this.currentIndex = 0;
            }

            if (this.shouldLoadPlayer()) {
                this.$nextTick(() => this.loadPlayer());
            }
        },

        getCurrentStream() {
            return this.streams[this.currentIndex] || null;
        },

        savePreferences() {
            StreamManager.storage.set('widget-visible', this.visible);
            StreamManager.storage.set('widget-minimized', this.minimized);
            StreamManager.storage.set('widget-muted', this.muted);
        },

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

function getInitialVisibility() {
    const stored = StreamManager.storage.get('widget-visible');

    if (stored !== null) {
        return stored;
    }

    // Mobile detection for first-time users
    const isMobile = window.innerWidth <= 768 ||
        /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);

    return !isMobile;
}
