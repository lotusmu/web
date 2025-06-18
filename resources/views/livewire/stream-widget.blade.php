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
    x-data="streamWidget(@js($streams))"
    x-init="init()"
>
    @persist('stream-widget-container')
    <div id="stream-widget-persistent-container">
        <template x-if="visible && streams.length > 0">
            <div x-show="minimized">
                <x-stream-widget.minimized/>
            </div>
        </template>

        <template x-if="visible && streams.length > 0">
            <div x-show="!minimized">
                <x-stream-widget.expanded/>
            </div>
        </template>
    </div>
    @endpersist

    @persist('stream-restore-btn')
    <x-stream-widget.restore-button/>
    @endpersist
</div>

<script>
    window.streamWidget = function (initialStreams) {
        return {
            visible: localStorage.getItem('stream-widget-visible') !== 'false',
            minimized: localStorage.getItem('stream-widget-minimized') === 'true',
            muted: localStorage.getItem('stream-widget-muted') !== 'false',
            streams: initialStreams,
            currentIndex: 0,
            iframe: null,

            init() {
                // Prevent multiple initializations
                if (window.streamWidgetInstance) {
                    return;
                }

                window.streamWidgetInstance = this;

                if (this.streams.length > 0 && !this.minimized && this.visible) {
                    this.$nextTick(() => this.loadPlayer());
                }

                // Listen for Livewire updates
                this.$wire.on('streams-updated', () => {
                    this.updateStreams(this.$wire.streams);
                });
            },

            updateStreams(newStreams) {
                this.streams = newStreams || [];

                if (this.currentIndex >= this.streams.length) {
                    this.currentIndex = 0;
                }

                // Reload player if expanded and visible
                if (!this.minimized && this.visible && this.streams.length > 0) {
                    this.$nextTick(() => this.loadPlayer());
                }
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
            },

            getCurrentStream() {
                return this.streams[this.currentIndex] || null;
            },

            // User actions
            nextStream() {
                if (this.streams.length <= 1) return;
                this.currentIndex = (this.currentIndex + 1) % this.streams.length;
                this.loadPlayer();
            },

            previousStream() {
                if (this.streams.length <= 1) return;
                this.currentIndex = (this.currentIndex - 1 + this.streams.length) % this.streams.length;
                this.loadPlayer();
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
            },

            resume() {
                this.minimized = false;
                this.savePreferences();
                this.$nextTick(() => this.loadPlayer());
            },

            close() {
                this.visible = false;
                this.savePreferences();
                // Clean up iframe
                const container = document.getElementById('stream-player-container');
                if (container) {
                    container.innerHTML = '';
                }
                this.iframe = null;
            },

            show() {
                this.visible = true;
                this.savePreferences();
                if (!this.minimized) {
                    this.$nextTick(() => this.loadPlayer());
                }
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
            }
        };
    };
</script>
