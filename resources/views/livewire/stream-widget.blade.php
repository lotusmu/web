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
    x-data="streamWidget()"
    x-init="initWithWire()"
>
    <!-- Restore button -->
    <div id="stream-restore-btn" style="display: none;" class="fixed bottom-4 right-4 z-40">
        <button onclick="window.streamWidgetInstance?.show()"
                class="bg-purple-600/90 hover:bg-purple-700 text-white p-2 rounded-full shadow-lg backdrop-blur-sm border border-purple-500/50 transition-all duration-300 hover:scale-110"
                title="Show live streams">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path
                    d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/>
            </svg>
        </button>
    </div>
</div>

<script>
    window.streamWidget = function () {
        return {
            visible: localStorage.getItem('stream-widget-visible') !== 'false',
            minimized: localStorage.getItem('stream-widget-minimized') === 'true',
            muted: localStorage.getItem('stream-widget-muted') !== 'false',
            streams: [],
            currentIndex: 0,
            iframe: null,
            widgetCreated: false,

            async initWithWire() {
                console.log('StreamWidget initializing...');

                // Prevent multiple initializations
                if (window.streamWidgetInstance) {
                    console.log('StreamWidget already exists, skipping');
                    return;
                }

                window.streamWidgetInstance = this;

                // Get initial streams
                this.streams = this.$wire.streams || [];
                console.log('Initial streams:', this.streams);

                if (this.streams.length > 0) {
                    this.createWidget();
                }

                // Listen for Livewire updates
                this.$wire.on('streams-updated', () => {
                    console.log('Streams updated event received');
                    this.updateStreams(this.$wire.streams);
                });
            },

            createWidget() {
                // Remove existing widget
                const existing = document.getElementById('stream-widget');
                if (existing) existing.remove();

                const widget = document.createElement('div');
                widget.id = 'stream-widget';
                widget.innerHTML = this.getWidgetHTML();
                document.body.appendChild(widget);

                this.widgetCreated = true;
                this.updateVisibility();

                // Load player if not minimized
                if (!this.minimized && this.visible) {
                    setTimeout(() => this.loadPlayer(), 100);
                }
            },

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

                this.createWidget();
            },

            removeWidget() {
                const widget = document.getElementById('stream-widget');
                if (widget) widget.remove();
                // Clear iframe reference when removing widget
                this.iframe = null;
                this.widgetCreated = false;
                this.updateRestoreButton();
            },

            loadPlayer() {
                const stream = this.getCurrentStream();
                if (!stream || this.minimized || !this.visible) return;

                const container = document.getElementById('stream-player-container');
                if (!container) return;

                const domain = window.location.hostname;
                const src = `https://player.twitch.tv/?channel=${stream.channel_name}&parent=${domain}&muted=${this.muted}&autoplay=true&controls=false`;

                const iframe = document.createElement('iframe');
                iframe.src = src;
                iframe.frameBorder = '0';
                iframe.scrolling = 'no';
                iframe.allowFullscreen = true;
                iframe.className = 'w-full h-full';
                iframe.allow = 'autoplay; fullscreen';

                container.innerHTML = '';
                container.appendChild(iframe);
                this.iframe = iframe;

                console.log('Player loaded for:', stream.channel_name);
            },

            getCurrentStream() {
                return this.streams[this.currentIndex] || null;
            },

            updateVisibility() {
                const widget = document.getElementById('stream-widget');
                if (widget) {
                    widget.style.display = this.visible ? 'block' : 'none';
                }
                this.updateRestoreButton();
            },

            updateRestoreButton() {
                const btn = document.getElementById('stream-restore-btn');
                if (btn) {
                    btn.style.display = (!this.visible && this.streams.length > 0) ? 'block' : 'none';
                }
            },

            // User actions
            nextStream() {
                if (this.streams.length <= 1) return;
                this.currentIndex = (this.currentIndex + 1) % this.streams.length;
                this.createWidget();
            },

            previousStream() {
                if (this.streams.length <= 1) return;
                this.currentIndex = (this.currentIndex - 1 + this.streams.length) % this.streams.length;
                this.createWidget();
            },

            minimize() {
                this.minimized = true;
                this.savePreferences();
                // Remove iframe when minimizing to stop playback
                const container = document.getElementById('stream-player-container');
                if (container) {
                    container.innerHTML = '';
                }
                this.iframe = null;
                this.createWidget();
            },

            resume() {
                this.minimized = false;
                this.savePreferences();
                this.createWidget();
            },

            close() {
                this.visible = false;
                this.savePreferences();
                this.removeWidget(); // Remove widget completely when closed
            },

            show() {
                this.visible = true;
                this.savePreferences();
                this.createWidget(); // Recreate widget when showing
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
            },

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
                                        ${this.getNavigationButtons('w-3 h-3')}
                                        <button onclick="window.streamWidgetInstance.resume()" class="p-1 text-gray-400 hover:text-purple-400 hover:bg-slate-700 rounded transition-colors" title="Resume">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/>
                                            </svg>
                                        </button>
                                        <button onclick="window.streamWidgetInstance.close()" class="p-1 text-gray-400 hover:text-red-400 hover:bg-slate-700 rounded transition-colors" title="Close">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
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
                                    ${this.getNavigationButtons('w-4 h-4')}
                                    <button onclick="window.streamWidgetInstance.minimize()" class="p-1 text-gray-400 hover:text-white hover:bg-slate-700 rounded transition-colors" title="Minimize">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                        </svg>
                                    </button>
                                    <button onclick="window.streamWidgetInstance.close()" class="p-1 text-gray-400 hover:text-red-400 hover:bg-slate-700 rounded transition-colors" title="Close">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="relative group">
                                <div class="w-80 h-48 bg-slate-800" id="stream-player-container"></div>
                                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/90 via-black/50 to-transparent p-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <h3 class="text-white text-sm font-medium leading-tight line-clamp-2 mb-1">${stream.title || 'Untitled Stream'}</h3>
                                    <div class="flex items-center justify-between text-xs">
                                        <div class="flex items-center space-x-2 text-gray-300">
                                            <span class="font-medium">${stream.channel_name}</span>
                                            <span>•</span>
                                            <span>${stream.game_category || 'No Category'}</span>
                                        </div>
                                        <div class="flex items-center space-x-1 text-gray-400">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                            </svg>
                                            <span>${this.getDuration(stream.started_at)}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="p-3 bg-slate-800/50 border-t border-slate-700/50">
                                <div class="flex space-x-2">
                                    <a href="https://twitch.tv/${stream.channel_name}" target="_blank" class="flex-1 bg-purple-600 hover:bg-purple-700 text-white text-xs py-2 px-3 rounded-lg font-medium text-center transition-colors flex items-center justify-center space-x-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 101.414 1.414L15 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5z"/>
                                            <path d="M5 5a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-3a1 1 0 10-2 0v3H5V7h3a1 1 0 000-2H5z"/>
                                        </svg>
                                        <span>Watch Full</span>
                                    </a>
                                    ${stream.partner?.promo_code ? `
                                        <button onclick="navigator.clipboard.writeText('${stream.partner.promo_code}')" class="bg-slate-700 hover:bg-slate-600 text-gray-300 text-xs py-2 px-3 rounded-lg font-medium transition-colors flex items-center space-x-1" title="Copy promo code">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                            </svg>
                                            <span>${stream.partner.promo_code}</span>
                                        </button>
                                    ` : ''}
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            },

            getNavigationButtons(size) {
                if (this.streams.length <= 1) return '';

                return `
                    <button onclick="window.streamWidgetInstance.previousStream()" class="p-1 text-gray-400 hover:text-white hover:bg-slate-700 rounded transition-colors" title="Previous">
                        <svg class="${size}" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                    <button onclick="window.streamWidgetInstance.nextStream()" class="p-1 text-gray-400 hover:text-white hover:bg-slate-700 rounded transition-colors" title="Next">
                        <svg class="${size}" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                `;
            }
        };
    };
</script>
