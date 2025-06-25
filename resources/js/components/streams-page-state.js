window.streamsPageState = function (initialStreams, initialSelectedStreamId, initialViewMode) {
    return {
        streams: initialStreams,
        selectedStreamId: initialSelectedStreamId,
        viewMode: initialViewMode,
        selectedStream: null,
        isPollingActive: true,

        init() {
            this.updateSelectedStream();
            this.loadAllPlayers();

            // Handle tab visibility for all players
            document.addEventListener('visibilitychange', () => {
                if (document.hidden) {
                    // Tab hidden - pause all players
                    if (this.mainPlayer) {
                        try {
                            this.mainPlayer.pause();
                        } catch (e) {
                        }
                    }
                    Object.values(this.gridPlayers).forEach(player => {
                        try {
                            player.pause();
                        } catch (e) {
                        }
                    });
                } else {
                    // Tab visible - resume all players
                    if (this.mainPlayer) {
                        try {
                            this.mainPlayer.play();
                        } catch (e) {
                        }
                    }
                    Object.values(this.gridPlayers).forEach(player => {
                        try {
                            player.play();
                        } catch (e) {
                        }
                    });
                }
            });

            // Watch for selectedStreamId changes
            this.$watch('selectedStreamId', () => {
                this.updateSelectedStream();
                if (this.viewMode === 'featured' && this.selectedStream) {
                    this.loadMainPlayer();
                }
            });

            // Watch for Livewire stream updates
            this.$watch('$wire.streams', (newStreams) => {
                if (newStreams && Array.isArray(newStreams)) {
                    this.streams = newStreams;
                    this.updateSelectedStream();
                    this.loadAllPlayers();
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

        updateSelectedStream() {
            this.selectedStream = this.streams.find(stream => stream.id == this.selectedStreamId) || null;

            if (!this.selectedStream && this.streams.length > 0) {
                this.selectedStreamId = this.streams[0].id;
                this.selectedStream = this.streams[0];
            }
        },

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
