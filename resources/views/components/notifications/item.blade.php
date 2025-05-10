<?php

use Illuminate\Notifications\DatabaseNotification;

?>

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
                        x-data="{}"
                        x-on:click.prevent="
                            $wire.markAsRead('{{ $notification->id }}').then(() => {
                                window.location.href = '{{ $action['url'] ?? '#' }}';
                            })
                        "
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
