<?php

namespace App\Livewire\Pages\Guest\Content;

use App\Actions\Stream\LoadActiveStreamsAction;
use App\Actions\Stream\SwitchViewModeAction;
use App\Http\Resources\StreamResource;
use App\Livewire\BaseComponent;

class Streams extends BaseComponent
{
    public $streams = [];

    public $selectedStreamId = null;

    public $viewMode = 'grid';

    public $isPollingEnabled = true;

    public function mount(LoadActiveStreamsAction $loadStreams): void
    {
        $this->viewMode = request()->cookie('streams_view_mode', 'grid');
        $this->loadStreams($loadStreams);
    }

    public function loadStreams(LoadActiveStreamsAction $loadStreams): void
    {
        $streams = $loadStreams->handle();
        $this->streams = StreamResource::collection($streams)->resolve();

        if (! $this->selectedStreamId && ! empty($this->streams)) {
            $this->selectedStreamId = $this->streams[0]['id'];
        }
    }

    public function updatedSelectedStreamId($value): void
    {
        $streamExists = collect($this->streams)->contains('id', $value);
        if (! $streamExists && ! empty($this->streams)) {
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

    protected function getViewName(): string
    {
        return 'pages.guest.content.streams';
    }

    protected function getLayoutType(): string
    {
        return 'guest';
    }
}
