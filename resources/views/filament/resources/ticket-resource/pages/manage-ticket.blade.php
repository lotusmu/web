<x-filament-panels::page>
    <x-filament::card>
        <h2 class="text-2xl font-bold mb-4">{{ $ticket->title }}</h2>
        <p><strong>Status:</strong> {{ $ticket->status }}</p>
        <p><strong>Priority:</strong> {{ $ticket->priority }}</p>
        <p><strong>Category:</strong> {{ $ticket->category->name }}</p>
        <p><strong>Description:</strong> {{ $ticket->description }}</p>
    </x-filament::card>

    <x-filament::card class="mt-6">
        <h3 class="text-xl font-semibold mb-4">Replies</h3>
        @foreach ($replies as $reply)
            <div class="mb-4 p-4 bg-gray-100 rounded">
                <p class="mb-2">{{ $reply->content }}</p>
                <p class="text-sm text-gray-600">By {{ $reply->user->name }}
                    on {{ $reply->created_at->format('M d, Y H:i') }}</p>
            </div>
        @endforeach

        <form wire:submit.prevent="addReply" class="mt-6">
            {{ $this->form }}
            <x-filament::button type="submit" class="mt-4">
                Reply
            </x-filament::button>
        </form>
    </x-filament::card>
</x-filament-panels::page>
