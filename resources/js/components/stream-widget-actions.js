// Export as global function
window.streamWidgetActions = function () {
    return {
        nextStream() {
            if (this.streams.length <= 1) return;

            this.currentIndex = (this.currentIndex + 1) % this.streams.length;
            this.showCustomPlayButton = false;
            this.currentChannelName = null;
            this.loadPlayer();
        },

        previousStream() {
            if (this.streams.length <= 1) return;

            this.currentIndex = (this.currentIndex - 1 + this.streams.length) % this.streams.length;
            this.showCustomPlayButton = false;
            this.currentChannelName = null;
            this.loadPlayer();
        },

        minimize() {
            this.minimized = true;
            this.savePreferences();

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

            this.currentChannelName = null;

            if (!this.minimized && this.streams.length > 0) {
                this.$nextTick(() => this.loadPlayer());
            }
        }
    };
};
