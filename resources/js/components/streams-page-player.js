window.streamsPagePlayer = function () {
    return {
        mainPlayer: null,
        gridPlayers: {},

        loadAllPlayers() {
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

        loadMainPlayer() {
            if (!this.selectedStream || this.mainPlayer) return;

            const container = document.getElementById('main-stream-player');
            if (!container) return;

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
            } catch (error) {
                console.error('Failed to create main player:', error);
            }
        },

        loadGridPlayer(streamId, stream) {
            if (this.gridPlayers[streamId]) return;

            const container = document.getElementById(`stream-player-${streamId}`);
            if (!container) return;

            try {
                this.gridPlayers[streamId] = new Twitch.Embed(`stream-player-${streamId}`, {
                    width: '100%',
                    height: '100%',
                    channel: stream.channel_name,
                    parent: [window.location.hostname],
                    muted: true,
                    autoplay: true,
                    controls: false,
                    layout: 'video'
                });
            } catch (error) {
                console.error('Failed to create grid player:', error);
            }
        },

        cleanupMainPlayer() {
            if (this.mainPlayer) {
                try {
                    this.mainPlayer.destroy();
                } catch (e) {
                }
                this.mainPlayer = null;
            }
            const container = document.getElementById('main-stream-player');
            if (container) container.innerHTML = '';
        },

        cleanupGridPlayers() {
            Object.keys(this.gridPlayers).forEach(streamId => {
                try {
                    this.gridPlayers[streamId].destroy();
                } catch (e) {
                }
                delete this.gridPlayers[streamId];
                const container = document.getElementById(`stream-player-${streamId}`);
                if (container) container.innerHTML = '';
            });
        }
    };
};
