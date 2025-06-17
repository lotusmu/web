class StreamWidget {
    constructor() {
        this.visible = localStorage.getItem('stream-widget-visible') !== 'false';
        this.minimized = localStorage.getItem('stream-widget-minimized') === 'true';
        this.muted = localStorage.getItem('stream-widget-muted') !== 'false';
        this.streams = [];
        this.currentIndex = 0;
        this.iframe = null;
        this.widgetCreated = false;
        this.initialized = false;
    }

    init() {
        if (this.initialized) return;

        console.log('StreamWidget initializing...');
        this.initialized = true;

        // Create restore button immediately
        this.createRestoreButton();

        // Listen for data updates from Livewire
        document.addEventListener('stream-data-updated', (event) => {
            this.updateStreams(event.detail.streams);
        });

        // Listen for Livewire navigation events to maintain widget
        document.addEventListener('livewire:navigating', () => {
            console.log('Navigation starting - preserving widget');
        });

        document.addEventListener('livewire:navigated', () => {
            console.log('Navigation completed - ensuring widget visibility');
            this.ensureWidgetExists();
        });
    }

    createRestoreButton() {
        if (document.getElementById('stream-restore-btn')) return;

        const restoreBtn = document.createElement('div');
        restoreBtn.id = 'stream-restore-btn';
        restoreBtn.style.display = 'none';
        restoreBtn.className = 'fixed bottom-4 right-4 z-40';
        restoreBtn.innerHTML = `
            <button onclick="window.globalStreamWidget.show()"
                    class="bg-purple-600/90 hover:bg-purple-700 text-white p-2 rounded-full shadow-lg backdrop-blur-sm border border-purple-500/50 transition-all duration-300 hover:scale-110"
                    title="Show live streams">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/>
                </svg>
            </button>
        `;
        document.body.appendChild(restoreBtn);
    }

    updateStreams(newStreams) {
        console.log('Updating streams:', newStreams);
        this.streams = newStreams || [];

        if (this.streams.length === 0) {
            this.removeWidget();
            return;
        }

        if (this.currentIndex >= this.streams.length) {
            this.currentIndex = 0;
        }

        if (!this.widgetCreated) {
            this.createPersistentWidget();
            this.widgetCreated = true;
            // Force iframe creation after widget is created
            setTimeout(() => {
                this.updateIframe();
            }, 200);
        } else {
            // Only update UI, don't recreate iframe
            this.updateUIOnly();
        }
    }

    ensureWidgetExists() {
        // Check if widget still exists in DOM after navigation
        const widget = document.getElementById('persistent-stream-widget');
        if (!widget && this.streams.length > 0 && this.widgetCreated) {
            console.log('Widget missing after navigation, recreating...');
            this.widgetCreated = false;
            this.createPersistentWidget();
            setTimeout(() => {
                this.updateIframe();
            }, 100);
        }
        this.updateVisibility();
    }

    createPersistentWidget() {
        const existingWidget = document.getElementById('persistent-stream-widget');
        if (existingWidget) {
            console.log('Widget already exists, skipping creation');
            return;
        }

        const widget = document.createElement('div');
        widget.id = 'persistent-stream-widget';
        widget.innerHTML = this.getWidgetHTML();
        document.body.appendChild(widget);

        this.updateVisibility();

        setTimeout(() => {
            this.updateIframe();
        }, 0);
    }

    setupMuteListener() {
        if (this.iframe && this.iframe.contentWindow) {
            window.addEventListener('message', (event) => {
                if (event.origin === 'https://player.twitch.tv' && event.data) {
                    if (event.data.eventName === 'video-mute') {
                        this.muted = true;
                        localStorage.setItem('stream-widget-muted', 'true');
                    } else if (event.data.eventName === 'video-unmute') {
                        this.muted = false;
                        localStorage.setItem('stream-widget-muted', 'false');
                    }
                }
            });
        }
    }

    updateUIOnly() {
        const widget = document.getElementById('persistent-stream-widget');
        if (widget) {
            widget.innerHTML = this.getWidgetHTML();
            this.updateVisibility();
        }
    }

    removeWidget() {
        const widget = document.getElementById('persistent-stream-widget');
        if (widget) {
            widget.remove();
        }
        this.widgetCreated = false;
    }

    getCurrentStream() {
        return this.streams[this.currentIndex] || null;
    }

    updateIframe() {
        const stream = this.getCurrentStream();
        if (!stream || this.minimized) {
            console.log('No stream or minimized, skipping iframe update');
            return;
        }

        const domain = window.location.hostname;
        const src = `https://player.twitch.tv/?channel=${stream.channel_name}&parent=${domain}&muted=${this.muted}&autoplay=true&controls=false`;

        // Reuse existing iframe if same source
        if (this.iframe && this.iframe.src === src) {
            console.log('Iframe already showing correct stream, ensuring container');
            const container = document.querySelector('#stream-iframe-container');
            if (container && !container.contains(this.iframe)) {
                container.appendChild(this.iframe);
            }
            return;
        }

        console.log('Creating/updating iframe for stream:', stream.channel_name);

        if (!this.iframe) {
            this.iframe = document.createElement('iframe');
            this.iframe.frameBorder = '0';
            this.iframe.scrolling = 'no';
            this.iframe.allowFullscreen = true;
            this.iframe.width = '320';
            this.iframe.height = '192';
            this.iframe.className = 'w-full h-full';
            this.iframe.allow = 'autoplay; fullscreen';
        }

        if (this.iframe.src !== src) {
            this.iframe.src = src;
            this.iframe.onload = () => {
                console.log('Iframe loaded successfully');
                this.setupMuteListener();
            };
        }

        const container = document.querySelector('#stream-iframe-container');
        if (container) {
            container.innerHTML = '';
            container.appendChild(this.iframe);
        } else {
            console.warn('Stream iframe container not found');
        }
    }

    updateVisibility() {
        const widget = document.getElementById('persistent-stream-widget');
        if (widget) {
            widget.style.display = this.visible ? 'block' : 'none';
        }

        const restoreBtn = document.getElementById('stream-restore-btn');
        if (restoreBtn) {
            restoreBtn.style.display = (!this.visible && this.streams.length > 0) ? 'block' : 'none';
        }
    }

    nextStream() {
        if (this.streams.length > 1) {
            this.currentIndex = (this.currentIndex + 1) % this.streams.length;
            this.updateIframe();
            this.updateUI();
        }
    }

    previousStream() {
        if (this.streams.length > 1) {
            this.currentIndex = (this.currentIndex - 1 + this.streams.length) % this.streams.length;
            this.updateIframe();
            this.updateUI();
        }
    }

    minimize() {
        this.minimized = true;
        localStorage.setItem('stream-widget-minimized', 'true');
        this.updateUI();
    }

    resume() {
        this.minimized = false;
        localStorage.setItem('stream-widget-minimized', 'false');
        this.updateIframe();
        this.updateUI();
    }

    close() {
        this.visible = false;
        localStorage.setItem('stream-widget-visible', 'false');
        this.updateVisibility();
    }

    show() {
        this.visible = true;
        localStorage.setItem('stream-widget-visible', 'true');
        this.updateVisibility();
        this.updateIframe();
    }

    updateUI() {
        const widget = document.getElementById('persistent-stream-widget');
        if (widget) {
            widget.innerHTML = this.getWidgetHTML();
            this.updateVisibility();
            setTimeout(() => {
                this.updateIframe();
            }, 0);
        }
    }

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

    getWidgetHTML() {
        if (!this.streams.length) return '';

        const stream = this.getCurrentStream();
        if (!stream) return '';

        if (this.minimized) {
            return `
                <div class="fixed bottom-4 right-4 z-50 min-w-80">
                    <div class="bg-slate-900/95 backdrop-blur-lg rounded-lg border border-purple-500/30 shadow-xl overflow-hidden">
                        <div class="flex items-center justify-between p-2 pr-3">
                            <div class="flex items-center space-x-2">
                                <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                                <span class="text-white text-xs font-medium">${stream.channel_name}</span>
                                <span class="text-gray-400 text-xs">•</span>
                                <span class="text-gray-400 text-xs">${stream.average_viewers?.toLocaleString() || '0'}</span>
                                ${this.streams.length > 1 ? `<span class="text-gray-500 text-xs">(${this.currentIndex + 1}/${this.streams.length})</span>` : ''}
                            </div>
                            <div class="flex items-center space-x-1">
                                ${this.streams.length > 1 ? `
                                    <button onclick="window.globalStreamWidget.previousStream()" class="p-1 text-gray-400 hover:text-purple-400 hover:bg-slate-700 rounded transition-colors" title="Previous">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    </button>
                                    <button onclick="window.globalStreamWidget.nextStream()" class="p-1 text-gray-400 hover:text-purple-400 hover:bg-slate-700 rounded transition-colors" title="Next">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                                    </button>
                                ` : ''}
                                <button onclick="window.globalStreamWidget.resume()" class="p-1 text-gray-400 hover:text-purple-400 hover:bg-slate-700 rounded transition-colors" title="Resume">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/></svg>
                                </button>
                                <button onclick="window.globalStreamWidget.close()" class="p-1 text-gray-400 hover:text-red-400 hover:bg-slate-700 rounded transition-colors" title="Close">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        return `
            <div class="fixed bottom-4 right-4 z-50 min-w-80">
                <div class="bg-slate-900/95 backdrop-blur-lg rounded-xl border border-purple-500/30 shadow-2xl shadow-purple-500/20 overflow-hidden transition-all duration-300 hover:shadow-purple-500/30">
                    <div class="flex items-center justify-between p-3 bg-gradient-to-r from-purple-600/20 to-blue-600/20 border-b border-slate-700/50">
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></div>
                            <span class="text-white text-sm font-medium">LIVE</span>
                            <span class="text-gray-300 text-xs">${stream.average_viewers?.toLocaleString() || '0'} viewers</span>
                            ${this.streams.length > 1 ? `
                                <span class="text-gray-400 text-xs">•</span>
                                <span class="text-gray-400 text-xs">${this.currentIndex + 1}/${this.streams.length}</span>
                            ` : ''}
                        </div>
                        <div class="flex items-center space-x-1">
                            ${this.streams.length > 1 ? `
                                <button onclick="window.globalStreamWidget.previousStream()" class="p-1 text-gray-400 hover:text-white hover:bg-slate-700 rounded transition-colors" title="Previous">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                </button>
                                <button onclick="window.globalStreamWidget.nextStream()" class="p-1 text-gray-400 hover:text-white hover:bg-slate-700 rounded transition-colors" title="Next">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                                </button>
                            ` : ''}
                            <button onclick="window.globalStreamWidget.minimize()" class="p-1 text-gray-400 hover:text-white hover:bg-slate-700 rounded transition-colors" title="Minimize">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                            </button>
                            <button onclick="window.globalStreamWidget.close()" class="p-1 text-gray-400 hover:text-red-400 hover:bg-slate-700 rounded transition-colors" title="Close">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>
                    <div class="relative group">
                        <div class="w-80 h-48 bg-slate-800" id="stream-iframe-container"></div>
                        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/90 via-black/50 to-transparent p-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <h3 class="text-white text-sm font-medium leading-tight line-clamp-2 mb-1">${stream.title || 'Untitled Stream'}</h3>
                            <div class="flex items-center justify-between text-xs">
                                <div class="flex items-center space-x-2 text-gray-300">
                                    <span class="font-medium">${stream.channel_name}</span>
                                    <span>•</span>
                                    <span>${stream.game_category || 'No Category'}</span>
                                </div>
                                <div class="flex items-center space-x-1 text-gray-400">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></path></svg>
                                    <span>${this.getDuration(stream.started_at)}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-3 bg-slate-800/50 border-t border-slate-700/50">
                        <div class="flex space-x-2">
                            <a href="https://twitch.tv/${stream.channel_name}" target="_blank" class="flex-1 bg-purple-600 hover:bg-purple-700 text-white text-xs py-2 px-3 rounded-lg font-medium text-center transition-colors flex items-center justify-center space-x-1">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 101.414 1.414L15 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5z"/><path d="M5 5a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-3a1 1 0 10-2 0v3H5V7h3a1 1 0 000-2H5z"/></svg>
                                <span>Watch Full</span>
                            </a>
                            ${stream.partner && stream.partner.promo_code ? `
                                <button onclick="navigator.clipboard.writeText('${stream.partner.promo_code}')" class="bg-slate-700 hover:bg-slate-600 text-gray-300 text-xs py-2 px-3 rounded-lg font-medium transition-colors flex items-center space-x-1" title="Copy promo code">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                    <span>${stream.partner.promo_code}</span>
                                </button>
                            ` : ''}
                        </div>
                    </div>
                </div>
            </div>
        `;
    }
}
