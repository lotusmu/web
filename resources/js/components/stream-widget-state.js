// Export as global function
window.streamWidgetState = function (initialStreams) {
    return {
        visible: localStorage.getItem('stream-widget-visible') !== 'false',
        minimized: localStorage.getItem('stream-widget-minimized') === 'true',
        muted: localStorage.getItem('stream-widget-muted') !== 'false',
        streams: initialStreams,
        currentIndex: 0,
        isTabVisible: true,
        showCustomPlayButton: false,
        initializationId: null,

        init() {
            this.initializationId = Date.now() + Math.random();

            if (window.streamWidgetInstance && window.streamWidgetInstance.initializationId !== this.initializationId) {
                window.streamWidgetInstance.destroy();
            }

            window.streamWidgetInstance = this;
            this.setupEventListeners();

            if (this.streams.length > 0 && !this.minimized && this.visible) {
                this.$nextTick(() => this.loadPlayer());
            }
        },

        setupEventListeners() {
            this.isTabVisible = !document.hidden;

            this.$wire.on('streams-updated', () => {
                this.updateStreams(this.$wire.streams);
            });

            this.visibilityHandler = () => {
                this.isTabVisible = !document.hidden;

                if (this.isTabVisible) {
                    if (!this.minimized && this.visible && this.streams.length > 0) {
                        if (this.showCustomPlayButton) {
                            return;
                        }
                        setTimeout(() => this.resumePlayer(), 200);
                    }
                } else {
                    if (!this.minimized && this.visible && this.twitchPlayer) {
                        this.showCustomPlayButton = true;
                    }
                }
            };

            document.addEventListener('visibilitychange', this.visibilityHandler);
        },

        updateStreams(newStreams) {
            this.streams = newStreams || [];

            if (this.currentIndex >= this.streams.length) {
                this.currentIndex = 0;
            }

            if (this.streams.length === 0) {
                this.showCustomPlayButton = false;
            }

            if (!this.minimized && this.visible && this.streams.length > 0) {
                this.$nextTick(() => this.loadPlayer());
            }
        },

        getCurrentStream() {
            return this.streams[this.currentIndex] || null;
        },

        savePreferences() {
            localStorage.setItem('stream-widget-visible', this.visible);
            localStorage.setItem('stream-widget-minimized', this.minimized);
            localStorage.setItem('stream-widget-muted', this.muted);
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
        },

        destroy() {
            if (this.visibilityHandler) {
                document.removeEventListener('visibilitychange', this.visibilityHandler);
            }

            this.destroyPlayer();

            if (window.streamWidgetInstance === this) {
                window.streamWidgetInstance = null;
            }
        }
    };
};
