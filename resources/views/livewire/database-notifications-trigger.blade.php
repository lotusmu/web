<?php

use Livewire\Attributes\Computed;
use Livewire\Volt\Component;

new class extends Component {
    #[Computed]
    public function unreadNotificationsCount()
    {
        return auth()->user()->unreadNotifications()->count();
    }
} ?>

<div class="relative">
    <flux:modal.trigger name="database-notifications-modal">
        <flux:button
            :tooltip="__('Notifications')"
            variant="subtle"
            size="sm"
            icon="bell"/>

        @if($this->unreadNotificationsCount > 0)
            <div
                class="absolute top-0 right-0 bg-red-600 text-white text-[11px] text-center min-w-4 min-h-4 rounded-full">
                {{ $this->unreadNotificationsCount }}
            </div>
        @endif
    </flux:modal.trigger>
</div>
