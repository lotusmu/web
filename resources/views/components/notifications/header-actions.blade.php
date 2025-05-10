@if($notifications->isNotEmpty())
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
