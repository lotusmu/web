/**
 * Core stream management utilities
 * Shared functionality for both widget and page components
 */
class StreamManager {
    static namespace = 'stream-manager';
    static instances = new Map();

    constructor(id, options = {}) {
        this.id = id;
        this.options = {
            autoCleanup: true,
            debounceMs: 200,
            ...options
        };

        this.players = new Map();
        this.eventListeners = new Map();
        this.isDestroyed = false;

        // Register instance
        StreamManager.instances.set(id, this);

        // Auto cleanup on page unload
        if (this.options.autoCleanup) {
            this.addEventListener(window, 'beforeunload', () => this.destroy());
        }
    }

    /**
     * Create a Twitch player with fallback
     */
    async createPlayer(containerId, options = {}) {
        if (this.isDestroyed) return null;

        const container = document.getElementById(containerId);
        if (!container) {
            console.warn(`Container ${containerId} not found`);
            return null;
        }

        // Clean existing player
        this.destroyPlayer(containerId);

        const playerOptions = {
            width: '100%',
            height: '100%',
            parent: [window.location.hostname],
            autoplay: true,
            layout: 'video',
            ...options
        };

        try {
            const player = new Twitch.Embed(container, playerOptions);
            this.players.set(containerId, {
                instance: player,
                options: playerOptions,
                createdAt: Date.now()
            });

            return player;
        } catch (error) {
            console.error(`Failed to create Twitch player for ${containerId}:`, error);
            return this.createFallbackPlayer(container, playerOptions);
        }
    }

    /**
     * Create fallback iframe player
     */
    createFallbackPlayer(container, options) {
        const params = new URLSearchParams({
            channel: options.channel,
            parent: options.parent[0],
            muted: options.muted || false,
            autoplay: options.autoplay || true,
            controls: options.controls || false,
            playsinline: true
        });

        const iframe = document.createElement('iframe');
        iframe.src = `https://player.twitch.tv/?${params.toString()}`;
        iframe.frameBorder = '0';
        iframe.scrolling = 'no';
        iframe.allowFullscreen = true;
        iframe.className = 'w-full h-full';
        iframe.allow = 'autoplay; fullscreen';

        container.innerHTML = '';
        container.appendChild(iframe);

        const fallbackPlayer = {iframe, isFallback: true};
        this.players.set(container.id, {
            instance: fallbackPlayer,
            options,
            createdAt: Date.now()
        });

        return fallbackPlayer;
    }

    /**
     * Destroy a specific player
     */
    destroyPlayer(containerId) {
        const playerData = this.players.get(containerId);
        if (!playerData) return;

        try {
            if (playerData.instance.isFallback) {
                // Handle fallback iframe
                const container = document.getElementById(containerId);
                if (container) container.innerHTML = '';
            } else if (typeof playerData.instance.destroy === 'function') {
                playerData.instance.destroy();
            }
        } catch (error) {
            // Handle security errors gracefully
            if (error.name !== 'SecurityError') {
                console.warn(`Error destroying player ${containerId}:`, error);
            }

            // Force cleanup
            const container = document.getElementById(containerId);
            if (container) container.innerHTML = '';
        }

        this.players.delete(containerId);
    }

    /**
     * Add event listener with automatic cleanup tracking
     */
    addEventListener(element, event, handler, options = {}) {
        const wrappedHandler = this.debounce(handler, this.options.debounceMs);
        element.addEventListener(event, wrappedHandler, options);

        const key = `${element.constructor.name}-${event}-${Date.now()}`;
        this.eventListeners.set(key, {
            element,
            event,
            handler: wrappedHandler,
            options
        });

        return key;
    }

    /**
     * Remove specific event listener
     */
    removeEventListener(key) {
        const listener = this.eventListeners.get(key);
        if (listener) {
            listener.element.removeEventListener(listener.event, listener.handler, listener.options);
            this.eventListeners.delete(key);
        }
    }

    /**
     * Debounce utility
     */
    debounce(func, delay) {
        let timeoutId;
        return (...args) => {
            clearTimeout(timeoutId);
            timeoutId = setTimeout(() => func.apply(this, args), delay);
        };
    }

    /**
     * Format stream duration
     */
    static formatDuration(startedAt) {
        if (!startedAt) return '0m';

        const start = new Date(startedAt);
        const now = new Date();
        const diffMinutes = Math.floor((now - start) / 1000 / 60);

        if (diffMinutes >= 60) {
            const hours = Math.floor(diffMinutes / 60);
            const minutes = diffMinutes % 60;
            return `${hours}h ${minutes}m`;
        }

        return `${diffMinutes}m`;
    }

    /**
     * Safe localStorage operations
     */
    static storage = {
        get(key, defaultValue = null) {
            try {
                const value = localStorage.getItem(`${StreamManager.namespace}-${key}`);
                return value !== null ? JSON.parse(value) : defaultValue;
            } catch {
                return defaultValue;
            }
        },

        set(key, value) {
            try {
                localStorage.setItem(`${StreamManager.namespace}-${key}`, JSON.stringify(value));
                return true;
            } catch {
                return false;
            }
        },

        remove(key) {
            try {
                localStorage.removeItem(`${StreamManager.namespace}-${key}`);
                return true;
            } catch {
                return false;
            }
        }
    };

    /**
     * Cleanup all resources
     */
    destroy() {
        if (this.isDestroyed) return;

        // Destroy all players
        for (const containerId of this.players.keys()) {
            this.destroyPlayer(containerId);
        }

        // Remove all event listeners
        for (const key of this.eventListeners.keys()) {
            this.removeEventListener(key);
        }

        // Cleanup instance registry
        StreamManager.instances.delete(this.id);

        this.isDestroyed = true;
    }

    /**
     * Global cleanup utility
     */
    static destroyAll() {
        for (const instance of StreamManager.instances.values()) {
            instance.destroy();
        }
    }
}

// Export for module systems or attach to window
if (typeof module !== 'undefined' && module.exports) {
    module.exports = StreamManager;
} else {
    window.StreamManager = StreamManager;
}
