// Export as global function
window.streamWidgetPlayer = function () {
    return {
        twitchPlayer: null,
        currentChannelName: null,

        loadPlayer() {
            const stream = this.getCurrentStream();
            if (!stream || this.minimized || !this.visible) return;

            const container = document.getElementById('stream-player-container');
            if (!container) {
                console.log('Player container not found, resetting player state');
                this.twitchPlayer = null;
                this.currentChannelName = null;
                return;
            }

            this.showCustomPlayButton = false;

            const hasValidPlayer = this.twitchPlayer &&
                container.querySelector('iframe, div[data-twitch-embed]');
            const channelChanged = this.currentChannelName !== stream.channel_name;

            if (hasValidPlayer && !channelChanged) {
                return;
            }

            this.currentChannelName = stream.channel_name;
            this.destroyPlayer();
            container.innerHTML = '';

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
                this.setupPlayerEvents();
            } catch (error) {
                console.error('Failed to create Twitch embed:', error);
                this.fallbackToIframe(container, stream);
            }
        },

        setupPlayerEvents() {
            if (!this.twitchPlayer) return;

            this.twitchPlayer.addEventListener(Twitch.Embed.VIDEO_READY, () => {
                const player = this.twitchPlayer.getPlayer();

                if (this.muted) {
                    player.setMuted(true);
                }
                player.setVolume(this.muted ? 0 : 0.5);
            });

            this.twitchPlayer.addEventListener(Twitch.Embed.OFFLINE, () => {
                console.log('Stream went offline');
            });
        },

        resumePlayer() {
            if (this.isTabVisible && !this.showCustomPlayButton && this.visible && !this.minimized) {
                this.loadPlayer();
            }
        },

        playFromCustomButton() {
            this.showCustomPlayButton = false;
            this.loadPlayer();
        },

        destroyPlayer() {
            if (this.twitchPlayer) {
                try {
                    // Try to destroy gracefully
                    if (typeof this.twitchPlayer.destroy === 'function') {
                        this.twitchPlayer.destroy();
                    }
                } catch (e) {
                    // Silently handle security errors from Twitch SDK
                    if (e.name !== 'SecurityError') {
                        console.log('Error destroying player:', e);
                    }

                    // Force cleanup by clearing container
                    const container = document.getElementById('stream-player-container');
                    if (container) {
                        container.innerHTML = '';
                    }
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
        }
    };
};
