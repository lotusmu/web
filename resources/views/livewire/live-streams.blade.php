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

<div>
    <!-- Hidden polling for data refresh -->
    <div wire:poll.60s="refreshStreams" style="display: none;"></div>

    <!-- Send data to global widget -->
    <script>
        // Send initial data when component is ready
        document.addEventListener('livewire:navigated', function () {
            setTimeout(() => {
                if (window.globalStreamWidget) {
                    console.log('Sending streams data after navigation');
                    window.globalStreamWidget.updateStreams(@json($streams));
                }
            }, 100);
        });

        // Also send on DOM ready (for initial page load)
        document.addEventListener('DOMContentLoaded', function () {
            setTimeout(() => {
                if (window.globalStreamWidget) {
                    console.log('Sending initial streams data');
                    window.globalStreamWidget.updateStreams(@json($streams));
                }
            }, 300);
        });

        // Force update after any potential page changes
        if (typeof Livewire !== 'undefined') {
            Livewire.hook('morph.updated', () => {
                setTimeout(() => {
                    if (window.globalStreamWidget) {
                        console.log('Sending streams data after morph');
                        window.globalStreamWidget.updateStreams(@json($streams));
                    }
                }, 50);
            });
        }
    </script>

    <!-- Listen for Livewire updates -->
    <div x-data="{
        init() {
            Livewire.on('streams-updated', () => {
                if (window.globalStreamWidget) {
                    console.log('Streams updated event received');
                    window.globalStreamWidget.updateStreams($wire.streams);
                }
            });
        }
    }"></div>
</div>
