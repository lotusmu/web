<x-filament-panels::page>

    <div class="flex items-start gap-4">
        {{ $this->ticketInfolist }}

        <div class="flex flex-col gap-4">
            <x-filament::section>
                {{ $this->form }}
            </x-filament::section>

            <x-filament::section>
                <x-slot:heading>
                    Conversation
                </x-slot:heading>

                <div class="space-y-4 mt-4">
                    @foreach ($replies as $reply)
                        <div class="bg-gray-100 rounded-lg p-4">
                            <div class="prose max-w-none mb-2">
                                {!! $reply->content !!}
                            </div>
                            <p class="text-sm text-gray-600">
                                By {{ $reply->user->name }} on {{ $reply->created_at->format('M d, Y H:i') }}
                            </p>
                        </div>
                    @endforeach
                </div>

                <x-filament-panels::form wire:submit="addReply">
                    {{ $this->replyForm }}

                    <x-filament::button type="submit">
                        Reply
                    </x-filament::button>
                </x-filament-panels::form>
            </x-filament::section>
        </div>
    </div>
</x-filament-panels::page>
