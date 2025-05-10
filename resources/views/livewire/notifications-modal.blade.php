<?php

use Illuminate\Notifications\DatabaseNotification;
use Livewire\Attributes\Computed;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    #[Computed]
    public function notifications()
    {
        return auth()->user()
            ->notifications()
            ->latest()
            ->simplePaginate(5);
    }

    #[Computed]
    public function unreadNotificationsCount()
    {
        return auth()->user()->unreadNotifications()->count();
    }

    public function markAsRead(string $notificationId)
    {
        $notification = DatabaseNotification::findOrFail($notificationId);

        if ($notification->notifiable_id !== auth()->id()) {
            return;
        }

        $notification->markAsRead();

        $this->dispatch('notifications-updated');
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();

        $this->dispatch('notifications-updated');
    }

    public function delete(string $notificationId)
    {
        $notification = DatabaseNotification::findOrFail($notificationId);

        if ($notification->notifiable_id !== auth()->id()) {
            return;
        }

        $notification->delete();

        $this->dispatch('notifications-updated');
    }

    public function deleteAll()
    {
        auth()->user()->notifications()->delete();

        $this->dispatch('notifications-updated');
    }
} ?>

<div>
    <flux:modal name="notifications-modal" variant="flyout">
        <div class="space-y-4 min-w-[34rem]">
            <header class="flex items-start flex-col space-y-4">
                <flux:heading size="lg">
                    {{ __('Notifications') }}
                </flux:heading>

                <x-notifications.header-actions :notifications="$this->notifications"/>
            </header>

            <flux:separator variant="subtle"/>

            <div class="space-y-2 overflow-y-auto">
                @forelse($this->notifications as $notification)
                    <x-notifications.item :notification="$notification"/>
                @empty
                    <x-notifications.empty/>
                @endforelse
            </div>

            @if($this->notifications->hasPages())
                <flux:pagination :paginator="$this->notifications"/>
            @endif
        </div>
    </flux:modal>
</div>
