<?php

use App\Actions\Stream\LoadActiveStreamsAction;
use App\Actions\Stream\SwitchViewModeAction;
use App\Http\Resources\StreamResource;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public $streams = [];
    public $selectedStreamId = null;
    public $viewMode = 'grid';
    public $isPollingEnabled = true;

    public function mount(LoadActiveStreamsAction $loadStreams): void
    {
        // Load saved view preference
        $this->viewMode = request()->cookie('streams_view_mode', 'grid');
        $this->loadStreams($loadStreams);
    }

    public function loadStreams(LoadActiveStreamsAction $loadStreams): void
    {
        $streams       = $loadStreams->handle();
        $this->streams = StreamResource::collection($streams)->resolve();

        // Auto-select first stream if none selected
        if ( ! $this->selectedStreamId && ! empty($this->streams)) {
            $this->selectedStreamId = $this->streams[0]['id'];
        }
    }

    #[On('refresh-streams')]
    public function refreshStreams(LoadActiveStreamsAction $loadStreams): void
    {
        $this->loadStreams($loadStreams);
    }

    public function updatedSelectedStreamId($value): void
    {
        // Validate stream exists
        $streamExists = collect($this->streams)->contains('id', $value);
        if ( ! $streamExists && ! empty($this->streams)) {
            $this->selectedStreamId = $this->streams[0]['id'];
        }
    }

    public function updatedViewMode($value, SwitchViewModeAction $switchViewMode): void
    {
        $this->viewMode = $switchViewMode->handle($value);
    }

    public function pausePolling(): void
    {
        $this->isPollingEnabled = false;
    }

    public function resumePolling(): void
    {
        $this->isPollingEnabled = true;
    }

    public function getTotalViewersProperty(): int
    {
        return collect($this->streams)->sum('average_viewers');
    }

    public function getSelectedStreamProperty(): ?array
    {
        return collect($this->streams)->firstWhere('id', $this->selectedStreamId);
    }
}; ?>

<flux:main container>
    <x-page-header
        :title="__('Live Streaming Now')"
        :kicker="__('Live')"
        :description="__('Watch our content creators live and discover amazing gameplay moments.')"
    />

    <div
        @if($isPollingEnabled) wire:poll.120s="refreshStreams" @endif
    x-data="{
            ...window.streamsPageState(@js($streams), @js($selectedStreamId), '{{ $viewMode }}'),
            ...window.streamsPagePlayer()
        }"
        x-init="init()"
    >
        <header class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-12">
            <flux:spacer/>

            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></div>
                    <flux:text class="font-medium">{{ count($streams) }} {{ __('Live') }}</flux:text>
                    <flux:text>•</flux:text>
                    <flux:text>{{ number_format($this->totalViewers) }} {{ __('viewers') }}</flux:text>
                </div>

                <flux:radio.group wire:model.live="viewMode" variant="segmented" size="sm">
                    <flux:radio value="grid" icon="squares-2x2"/>
                    <flux:radio value="featured" icon="tv"/>
                </flux:radio.group>
            </div>
        </header>

        @if(empty($streams))
            <x-streams.empty-state/>
        @else
            <!-- Featured View -->
            <div x-show="viewMode === 'featured'" x-cloak>
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                    <!-- Main Player -->
                    <div class="lg:col-span-3">
                        <flux:card class="overflow-hidden">
                            <div class="relative bg-zinc-900 aspect-video">
                                <div id="main-stream-player" class="w-full h-full">
                                    <!-- Player will be loaded by JavaScript -->
                                </div>
                            </div>

                            <div class="mt-4" x-show="selectedStream">
                                <flux:heading
                                    x-text="selectedStream?.title || '{{ __('Untitled Stream') }}'">
                                </flux:heading>

                                <div class="flex items-center gap-2 mt-2">
                                    <flux:text x-text="selectedStream?.channel_name"></flux:text>
                                    <flux:text>•</flux:text>
                                    <flux:text
                                        x-text="selectedStream?.game_category || '{{ __('No Category') }}'"></flux:text>
                                    <flux:spacer/>
                                    <flux:text
                                        x-text="(selectedStream?.average_viewers || 0).toLocaleString() + ' {{ __('viewers') }}'"></flux:text>
                                    <flux:text>•</flux:text>
                                    <flux:text x-text="getDuration(selectedStream?.started_at)"></flux:text>
                                </div>
                            </div>
                        </flux:card>
                    </div>

                    <!-- Stream Selection -->
                    <div>
                        <flux:radio.group
                            label="{{ __('Live Channels') }}"
                            variant="cards"
                            :indicator="false"
                            wire:model.live="selectedStreamId"
                            class="flex flex-col"
                        >
                            @foreach($streams as $stream)
                                <flux:tooltip content="{{ $stream['title'] ?? __('Untitled Stream') }}" position="left">
                                    <flux:radio value="{{ $stream['id'] }}">
                                        <div class="w-full">
                                            <flux:heading class="flex items-center">
                                                <span>{{ $stream['channel_name'] }}</span>
                                                <flux:spacer/>
                                                <div class="flex items-center gap-2">
                                                    <div
                                                        class="w-2 h-2 bg-red-500 rounded-full flex-shrink-0 animate-pulse"></div>
                                                    <flux:text size="sm">
                                                        {{ number_format($stream['average_viewers'] ?? 0) }} {{ __('viewers') }}
                                                    </flux:text>
                                                </div>
                                            </flux:heading>
                                            <flux:subheading size="sm">
                                                {{ $stream['game_category'] ?? __('No Category') }}
                                            </flux:subheading>
                                        </div>
                                    </flux:radio>
                                </flux:tooltip>
                            @endforeach
                        </flux:radio.group>
                    </div>
                </div>
            </div>

            <!-- Grid View -->
            <div x-show="viewMode === 'grid'" x-cloak>
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($streams as $stream)
                        <flux:card class="overflow-hidden">
                            <div class="relative bg-zinc-900 aspect-video">
                                <div id="stream-player-{{ $stream['id'] }}" class="w-full h-full">
                                    <!-- Player will be loaded lazily by JavaScript -->
                                </div>
                            </div>

                            <div class="mt-4">
                                <flux:heading class="truncate">
                                    {{ $stream['title'] ?? __('Untitled Stream') }}
                                </flux:heading>
                                <flux:subheading>{{ $stream['channel_name'] }}</flux:subheading>

                                <div class="flex items-center gap-2 mt-1">
                                    <flux:text size="sm">{{ $stream['game_category'] ?? __('No Category') }}</flux:text>
                                    <flux:text size="sm">•</flux:text>
                                    <flux:text
                                        size="sm">{{ number_format($stream['average_viewers'] ?? 0) }} {{ __('viewers') }}</flux:text>
                                </div>

                                <div class="mt-4">
                                    <flux:button
                                        href="https://twitch.tv/{{ $stream['channel_name'] }}"
                                        external
                                        variant="filled"
                                        icon="arrow-top-right-on-square"
                                        size="sm"
                                        class="w-full"
                                    >
                                        {{ __('Watch on Twitch') }}
                                    </flux:button>
                                </div>
                            </div>
                        </flux:card>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</flux:main>
