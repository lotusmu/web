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
        x-data="streamsPage(@js($streams), @js($selectedStreamId), '{{ $viewMode }}')"
        x-init="init()"
    >
        <x-streams.header
            :streams="$streams"
            :total-viewers="$this->totalViewers"
            :view-mode="$viewMode"
        />

        @if(empty($streams))
            <x-streams.empty-state/>
        @else
            <!-- Featured View -->
            <div x-show="viewMode === 'featured'" x-cloak>
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                    <x-streams.featured-player :streams="$streams"/>
                    <x-streams.stream-selector :streams="$streams"/>
                </div>
            </div>

            <!-- Grid View -->
            <div x-show="viewMode === 'grid'" x-cloak>
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($streams as $stream)
                        <x-streams.stream-card :stream="$stream"/>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</flux:main>
