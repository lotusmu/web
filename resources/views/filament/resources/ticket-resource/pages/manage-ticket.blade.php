<x-filament-panels::page>
    {{ $this->ticketInfolist }}

    <x-filament::section>
        <x-slot:heading>
            Conversation
        </x-slot:heading>

        <div class="space-y-4">
            @foreach ($replies as $reply)
                <x-filament::card>
                    <div class="prose dark:prose-invert max-w-none">
                        {!! $reply->content !!}
                    </div>

                    <div class="flex justify-end mt-4 text-sm text-gray-500 dark:text-gray-400">
                        By {{ $reply->user->name }} on {{ $reply->created_at->format('M d, Y H:i') }}
                    </div>
                </x-filament::card>
            @endforeach
        </div>
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
