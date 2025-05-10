<?php

use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {
    public $unreadCount = 0;

    public function mount()
    {
        $this->refreshUnreadCount();
    }

    public function refreshUnreadCount()
    {
        $this->unreadCount = auth()->user()->unreadNotifications()->count();
    }

    #[On('notifications-updated')]
    public function handleNotificationsUpdated()
    {
        $this->refreshUnreadCount();
    }
} ?>

<div class="relative">
    <flux:modal.trigger name="notifications-modal">
        <flux:button
            :tooltip="__('Notifications')"
            variant="subtle"
            size="sm"
            icon="bell"/>

        @if($unreadCount > 0)
            <div
                class="absolute top-0 right-0 bg-red-600 text-white text-[11px] text-center min-w-4 min-h-4 rounded-full">
                {{ $unreadCount }}
            </div>
        @endif
    </flux:modal.trigger>
</div>
