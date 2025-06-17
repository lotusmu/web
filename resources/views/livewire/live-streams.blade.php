<?php

use App\Models\Stream\StreamSession;
use Livewire\Volt\Component;

new class extends Component {
    public $liveStreams = [];
    public $currentStreamIndex = 0;
    public $minimized = false;
    public $visible = true;

    public function mount()
    {
        $this->loadLiveStreams();
    }

    public function loadLiveStreams()
    {
        $this->liveStreams = StreamSession::with(['partner.user'])
            ->whereNull('ended_at')
            ->orderByDesc('average_viewers')
            ->get()
            ->toArray();

        if (empty($this->liveStreams) || $this->currentStreamIndex >= count($this->liveStreams)) {
            $this->currentStreamIndex = 0;
        }
    }

    public function getCurrentStream()
    {
        if (empty($this->liveStreams)) {
            return null;
        }

        return (object) $this->liveStreams[$this->currentStreamIndex];
    }

    public function nextStream()
    {
        if (count($this->liveStreams) > 1) {
            $this->currentStreamIndex = ($this->currentStreamIndex + 1) % count($this->liveStreams);
        }
    }

    public function previousStream()
    {
        if (count($this->liveStreams) > 1) {
            $this->currentStreamIndex = ($this->currentStreamIndex - 1 + count($this->liveStreams)) % count($this->liveStreams);
        }
    }

    public function minimize()
    {
        $this->minimized = true;
    }

    public function resume()
    {
        $this->minimized = false;
    }

    public function close()
    {
        $this->visible = false;
    }

    public function getDurationForStream(): string
    {
        $currentStream = $this->getCurrentStream();
        if ( ! $currentStream) return '';

        $startTime = new DateTime($currentStream->started_at);
        $now       = new DateTime();
        $diff      = $now->diff($startTime);

        if ($diff->h > 0) {
            return $diff->h.'h '.$diff->i.'m';
        }

        return $diff->i.'m';
    }

    public function getTwitchEmbedUrl(): string
    {
        $currentStream = $this->getCurrentStream();
        if ( ! $currentStream) return '';

        $domain = parse_url(config('app.url'), PHP_URL_HOST);

        return "https://player.twitch.tv/?channel={$currentStream->channel_name}&parent={$domain}&muted=false&autoplay=true&controls=false";
    }
}; ?>

<div>
    @if($this->visible && !empty($this->liveStreams))
        @php $currentStream = $this->getCurrentStream(); @endphp

        <div class="fixed bottom-4 right-4 z-50"
             x-data="{
                showPromoToast: false,
                isVisible: @js($this->visible),
                isMinimized: @js($this->minimized),
                init() {
                    // Load states from localStorage
                    const savedVisible = localStorage.getItem('stream-widget-visible');
                    const savedMinimized = localStorage.getItem('stream-widget-minimized');

                    if (savedVisible !== null) {
                        this.isVisible = savedVisible === 'true';
                        if (!this.isVisible) {
                            $wire.close();
                        }
                    }

                    if (savedMinimized !== null) {
                        this.isMinimized = savedMinimized === 'true';
                        if (this.isMinimized) {
                            $wire.minimize();
                        }
                    }
                },
                handleMinimize() {
                    $wire.minimize();
                    localStorage.setItem('stream-widget-minimized', 'true');
                    this.isMinimized = true;
                },
                handleResume() {
                    $wire.resume();
                    localStorage.setItem('stream-widget-minimized', 'false');
                    this.isMinimized = false;
                },
                handleClose() {
                    $wire.close();
                    localStorage.setItem('stream-widget-visible', 'false');
                    this.isVisible = false;
                }
             }"
             x-show="isVisible"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-y-4 scale-95"
             x-transition:enter-end="opacity-100 transform translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 transform translate-y-4 scale-95">

            @if($this->minimized)
                <!-- Minimized State -->
                <div
                    class="bg-slate-900/95 backdrop-blur-lg rounded-lg border border-purple-500/30 shadow-xl overflow-hidden">
                    <div class="flex items-center justify-between p-2 pr-3">
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                            <span class="text-white text-xs font-medium">{{ $currentStream->channel_name }}</span>
                            <span class="text-gray-400 text-xs">•</span>
                            <span
                                class="text-gray-400 text-xs">{{ number_format($currentStream->average_viewers) }}</span>
                            @if(count($this->liveStreams) > 1)
                                <span class="text-gray-500 text-xs">({{ $this->currentStreamIndex + 1 }}/{{ count($this->liveStreams) }})</span>
                            @endif
                        </div>

                        <div class="flex items-center space-x-1">
                            @if(count($this->liveStreams) > 1)
                                <button wire:click="previousStream"
                                        class="p-1 text-gray-400 hover:text-purple-400 hover:bg-slate-700 rounded transition-colors"
                                        title="Previous">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                              d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                </button>
                                <button wire:click="nextStream"
                                        class="p-1 text-gray-400 hover:text-purple-400 hover:bg-slate-700 rounded transition-colors"
                                        title="Next">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                              d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            @endif

                            <button @click="handleResume()"
                                    class="p-1 text-gray-400 hover:text-purple-400 hover:bg-slate-700 rounded transition-colors"
                                    title="Resume">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/>
                                </svg>
                            </button>

                            <button @click="handleClose()"
                                    class="p-1 text-gray-400 hover:text-red-400 hover:bg-slate-700 rounded transition-colors"
                                    title="Close">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @else
                <!-- Full Stream Widget -->
                <div
                    class="bg-slate-900/95 backdrop-blur-lg rounded-xl border border-purple-500/30 shadow-2xl shadow-purple-500/20 overflow-hidden transition-all duration-300 hover:shadow-purple-500/30">

                    <!-- Header -->
                    <div
                        class="flex items-center justify-between p-3 bg-gradient-to-r from-purple-600/20 to-blue-600/20 border-b border-slate-700/50">
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></div>
                            <span class="text-white text-sm font-medium">LIVE</span>
                            <span class="text-gray-300 text-xs">{{ number_format($currentStream->average_viewers) }} viewers</span>
                            @if(count($this->liveStreams) > 1)
                                <span class="text-gray-400 text-xs">•</span>
                                <span
                                    class="text-gray-400 text-xs">{{ $this->currentStreamIndex + 1 }}/{{ count($this->liveStreams) }}</span>
                            @endif
                        </div>

                        <div class="flex items-center space-x-1">
                            @if(count($this->liveStreams) > 1)
                                <button wire:click="previousStream"
                                        class="p-1 text-gray-400 hover:text-white hover:bg-slate-700 rounded transition-colors"
                                        title="Previous">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                              d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                </button>
                                <button wire:click="nextStream"
                                        class="p-1 text-gray-400 hover:text-white hover:bg-slate-700 rounded transition-colors"
                                        title="Next">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                              d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            @endif

                            <button @click="handleMinimize()"
                                    class="p-1 text-gray-400 hover:text-white hover:bg-slate-700 rounded transition-colors"
                                    title="Minimize">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                </svg>
                            </button>

                            <button @click="handleClose()"
                                    class="p-1 text-gray-400 hover:text-red-400 hover:bg-slate-700 rounded transition-colors"
                                    title="Close">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Stream Player -->
                    <div class="relative group">
                        <div class="w-80 h-48 bg-slate-800">
                            <iframe
                                src="{{ $this->getTwitchEmbedUrl() }}"
                                frameborder="0"
                                scrolling="no"
                                allowfullscreen="true"
                                width="320"
                                height="192"
                                class="w-full h-full"
                            ></iframe>
                        </div>

                        <!-- Stream Info Overlay - Only show on hover -->
                        <div
                            class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/90 via-black/50 to-transparent p-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <h3 class="text-white text-sm font-medium leading-tight line-clamp-2 mb-1">
                                {{ $currentStream->title ?: 'Untitled Stream' }}
                            </h3>

                            <div class="flex items-center justify-between text-xs">
                                <div class="flex items-center space-x-2 text-gray-300">
                                    <span class="font-medium">{{ $currentStream->channel_name }}</span>
                                    <span>•</span>
                                    <span>{{ $currentStream->game_category }}</span>
                                </div>

                                <div class="flex items-center space-x-1 text-gray-400">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                              d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                    <span>{{ $this->getDurationForStream() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Bar -->
                    <div class="p-3 bg-slate-800/50 border-t border-slate-700/50">
                        <div class="flex space-x-2">
                            <a
                                href="https://twitch.tv/{{ $currentStream->channel_name }}"
                                target="_blank"
                                class="flex-1 bg-purple-600 hover:bg-purple-700 text-white text-xs py-2 px-3 rounded-lg font-medium text-center transition-colors flex items-center justify-center space-x-1"
                            >
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 101.414 1.414L15 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5z"/>
                                    <path
                                        d="M5 5a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-3a1 1 0 10-2 0v3H5V7h3a1 1 0 000-2H5z"/>
                                </svg>
                                <span>Watch Full</span>
                            </a>

                            @if($currentStream->partner['promo_code'])
                                <button
                                    @click="navigator.clipboard.writeText('{{ $currentStream->partner['promo_code'] }}'); showPromoToast = true; setTimeout(() => showPromoToast = false, 2000)"
                                    class="bg-slate-700 hover:bg-slate-600 text-gray-300 text-xs py-2 px-3 rounded-lg font-medium transition-colors flex items-center space-x-1"
                                    title="Copy promo code"
                                >
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                    <span>{{ $currentStream->partner['promo_code'] }}</span>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Toast Notification -->
            <div
                x-show="showPromoToast"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform translate-y-2"
                class="absolute -top-12 left-1/2 transform -translate-x-1/2 bg-green-600 text-white text-xs py-2 px-3 rounded-lg shadow-lg whitespace-nowrap"
                style="display: none;"
            >
                Promo code copied!
            </div>
        </div>
    @endif

    <!-- Hidden restore button when widget is closed -->
    <div x-data="{
            canRestore: false,
            init() {
                // Check if widget was closed and there are live streams
                const wasClosed = localStorage.getItem('stream-widget-visible') === 'false';
                this.canRestore = wasClosed && @js(!empty($this->liveStreams));
            }
         }"
         x-show="canRestore && !$wire.visible"
         class="fixed bottom-4 right-4 z-40">

        <button @click="
                    localStorage.setItem('stream-widget-visible', 'true');
                    $wire.set('visible', true);
                    canRestore = false;
                "
                class="bg-purple-600/90 hover:bg-purple-700 text-white p-2 rounded-full shadow-lg backdrop-blur-sm border border-purple-500/50 transition-all duration-300 hover:scale-110"
                title="Show live streams">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path
                    d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/>
            </svg>
        </button>
    </div>
</div>
