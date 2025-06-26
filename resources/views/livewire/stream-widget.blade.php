<?php

use App\Actions\Stream\LoadActiveStreamsAction;
use App\Http\Resources\StreamResource;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {
    public $streams = [];

    public function mount(LoadActiveStreamsAction $loadStreams): void
    {
        $this->loadStreams($loadStreams);
    }

    public function loadStreams(LoadActiveStreamsAction $loadStreams): void
    {
        $streams       = $loadStreams->handle();
        $this->streams = StreamResource::collection($streams)->resolve();
    }

    #[On('refresh-streams')]
    public function refreshStreams(LoadActiveStreamsAction $loadStreams)
    {
        $this->loadStreams($loadStreams);
        $this->dispatch('streams-updated');
    }
}; ?>

<div>
    @if(!empty($streams))
        <div
            wire:poll.60s="refreshStreams"
            x-data="streamWidget(@js($streams))"
            x-init="init()"
        >
            <!-- Show when expanded -->
            <div x-show="visible && streams.length > 0 && !minimized" x-cloak>
                <x-stream-widget.expanded/>
            </div>

            <!-- Show when minimized -->
            <div x-show="visible && streams.length > 0 && minimized" x-cloak>
                <x-stream-widget.minimized/>
            </div>

            <!-- Restore button when hidden -->
            <div x-show="!visible && streams.length > 0" x-cloak>
                <x-stream-widget.restore-button/>
            </div>
        </div>
    @endif
</div>
