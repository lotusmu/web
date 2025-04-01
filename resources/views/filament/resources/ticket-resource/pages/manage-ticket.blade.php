<x-filament-panels::page>
    {{ $this->ticketInfolist }}

    <x-filament::section>
        <x-slot:heading>
            Conversation
        </x-slot:heading>

        @if ($replies->isEmpty())
            <x-filament-tables::empty-state icon="heroicon-o-x-mark" heading="No replies"/>
        @else
            <div class="space-y-4">
                @foreach ($replies as $reply)
                    <x-filament::section
                        compact
                        icon="heroicon-m-user"
                        :heading="$reply->user->name === 'kodovoime'
                                ? __('Support')
                                : ($reply->user->name ?? __('Unknown User'))">

                        <div class="prose dark:prose-invert break-words">
                            {!! $reply->content !!}
                        </div>

                        <div
                            class="flex justify-end mt-4 text-sm text-gray-500 dark:text-gray-400">
                            {{ $reply->created_at->format('M d, Y H:i') }}
                        </div>
                    </x-filament::section>
                @endforeach
            </div>
        @endif
    </x-filament::section>

    <x-filament-panels::form wire:submit="addReply">
        <x-filament::section>
            <x-slot:heading>
                Reply
            </x-slot:heading>

            <div class="space-y-6">
                {{ $this->form }}

                <x-filament::button type="submit">
                    Reply
                </x-filament::button>
            </div>
        </x-filament::section>
    </x-filament-panels::form>
</x-filament-panels::page>
