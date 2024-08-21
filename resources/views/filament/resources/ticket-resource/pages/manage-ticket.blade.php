<x-filament-panels::page>
    <x-filament-panels::form wire:submit="save">
        {{ $this->form }}

        <x-filament::button type="submit" class="mt-4">
            Save Changes
        </x-filament::button>
    </x-filament-panels::form>

    <x-filament::section>
        <h2 class="text-lg font-medium">Replies</h2>
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

        <x-filament-panels::form wire:submit="addReply" class="mt-4">
            {{ $this->replyForm }}

            <x-filament::button type="submit" class="mt-4">
                Add Reply
            </x-filament::button>
        </x-filament-panels::form>
    </x-filament::section>
</x-filament-panels::page>
