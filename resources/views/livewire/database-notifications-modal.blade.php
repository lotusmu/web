<?php

use Illuminate\Notifications\DatabaseNotification;
use Livewire\Attributes\Computed;
use Livewire\Volt\Component;

new class extends Component {
    #[Computed]
    public function notifications()
    {
        return auth()->user()
            ->notifications()
            ->latest()
            ->paginate(10);
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

        $this->dispatch('notification-read');
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();

        $this->dispatch('all-notifications-read');
    }

    public function delete(string $notificationId)
    {
        $notification = DatabaseNotification::findOrFail($notificationId);

        if ($notification->notifiable_id !== auth()->id()) {
            return;
        }

        $notification->delete();

        $this->dispatch('notification-deleted');
    }

    public function deleteAll()
    {
        auth()->user()->notifications()->delete();

        $this->dispatch('all-notifications-deleted');
    }
} ?>

<div>
    <flux:modal name="database-notifications-modal" variant="flyout">
        <div class="space-y-4 min-w-[34rem]">
            <header class="flex items-start flex-col space-y-4">
                <flux:heading size="lg">
                    {{ __('Notifications') }}
                </flux:heading>

                @if($this->notifications->isNotEmpty())
                    <div class="flex gap-2 w-full">
                        <flux:spacer/>

                        <flux:button
                            variant="subtle"
                            icon="check"
                            wire:click="markAllAsRead"
                        >
                            {{ __('Mark all as read') }}
                        </flux:button>

                        <flux:button
                            inset="right"
                            variant="subtle"
                            icon="trash"
                            wire:click="deleteAll"
                        >
                            {{ __('Delete all') }}
                        </flux:button>
                    </div>
                @endif
            </header>

            <flux:separator variant="subtle"/>

            <div class="space-y-2 overflow-y-auto">
                @forelse($this->notifications as $notification)
                    <flux:card
                        class="flex items-start gap-4 {{ $notification->read_at ?? '!bg-[color-mix(in_oklab,_var(--color-compliment),_transparent_90%)] !border-[color-mix(in_oklab,_var(--color-compliment-content),_transparent_70%)]' }}"
                    >
                        <div>
                            <flux:icon.bell-alert
                                class="size-5 {{ $notification->read_at ?? '!text-[var(--color-compliment-content)]' }}"/>
                        </div>

                        <div class="w-full">
                            <div class="flex items-start justify-between gap-2 w-full">
                                <div>
                                    <flux:heading
                                        class="{{ $notification->read_at ?? '!text-[var(--color-compliment-content)]' }}">
                                        {{ $notification->data['title'] ?? __('Notification') }}
                                    </flux:heading>

                                    <flux:subheading>
                                        {{ $notification->data['body'] ?? '' }}
                                    </flux:subheading>
                                </div>

                                <flux:spacer/>

                                <div class="flex items-center gap-2">
                                    <div class="flex gap-1">
                                        @if(!$notification->read_at)
                                            <flux:button
                                                variant="subtle"
                                                icon="check"
                                                wire:click="markAsRead('{{ $notification->id }}')"
                                                :tooltip="__('Mark as read')"
                                            />
                                        @endif

                                        <flux:button
                                            variant="subtle"
                                            icon="trash"
                                            wire:click="delete('{{ $notification->id }}')"
                                            :tooltip="__('Delete')"
                                        />
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center mt-4">
                                @if($notification->data['actions'] ?? false)
                                    @foreach($notification->data['actions'] as $action)
                                        <flux:link
                                            class="text-sm"
                                            :href="$action['url'] ?? '#'"
                                            wire:click="markAsRead('{{ $notification->id }}')"
                                        >
                                            {{ $action['label'] }}
                                        </flux:link>
                                    @endforeach
                                @endif

                                <flux:spacer/>

                                <flux:text size="sm" class="text-right">
                                    {{ $notification->created_at->diffForHumans() }}
                                </flux:text>
                            </div>
                        </div>

                    </flux:card>
                @empty
                    <div class="py-8 text-center flex flex-col items-center">
                        <div class="mb-4 p-4 bg-zinc-400/10 dark:bg-white/10 rounded-full">
                            <flux:icon.bell-slash class="size-12"/>
                        </div>

                        <flux:heading size="lg">
                            {{ __('No notifications') }}
                        </flux:heading>

                        <flux:subheading size="sm">
                            {{ __('Please check again later.') }}
                        </flux:subheading>
                    </div>
                @endforelse
            </div>

            @if($this->notifications->hasPages())
                <div class="mt-4">
                    {{ $this->notifications->links() }}
                </div>
            @endif
        </div>
    </flux:modal>
</div>
