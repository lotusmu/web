<?php

use App\Models\Utility\GameServer;
use Illuminate\Support\Collection;
use Livewire\Volt\Component;
use Filament\Notifications\Notification;

new class extends Component {

    public $filament = false;

    public $selectedServerId;

    public $serverOptions;

    public function mount(): void
    {
        $this->serverOptions    = $this->getServerOptions();
        $this->selectedServerId = session('selected_server_id', $this->serverOptions->keys()->first());
    }

    public function updateServer($newServerId, $referer = null): void
    {
        $this->selectedServerId = $newServerId;
        $server                 = GameServer::findOrFail($newServerId);

        try {
            session([
                'selected_server_id' => $newServerId,
                'game_db_connection' => $server->connection_name,
            ]);

            $this->sendNotification($server);

            $this->redirect($referer ?? request()->header('Referer'));
        } catch (Exception $e) {
            $this->sendErrorNotification($e->getMessage());
        }
    }

    private function sendNotification(GameServer $server): void
    {
        $message = "Switched to {$server->name} - x{$server->experience_rate}";

        if ($this->filament) {
            Notification::make()
                ->title('Success!')
                ->body($message)
                ->success()
                ->send();
        } else {
            Flux::toast(
                variant: 'success',
                heading: __('Server Switched'),
                text: __($message),
            );
        }
    }

    private function sendErrorNotification(string $message): void
    {
        if ($this->filament) {
            Notification::make()
                ->title('Error')
                ->body('Failed to switch server: '.$message)
                ->danger()
                ->send();
        } else {
            Flux::toast(
                variant: 'danger',
                heading: __('Server Switch Failed'),
                text: __('Failed to switch server: '.$message),
            );
        }
    }

    private function getServerOptions(): Collection
    {
        return GameServer::where('is_active', true)
            ->get(['id', 'name', 'experience_rate'])
            ->mapWithKeys(function ($server) {
                return [
                    $server->id => [
                        'name'            => $server->name,
                        'experience_rate' => $server->experience_rate,
                    ]
                ];
            });
    }
}; ?>

@if($filament)
    {{-- Filament-specific markup --}}
    <x-filament::dropdown>
        <x-slot name="trigger">
            <x-filament::button
                icon="heroicon-o-server-stack"
                color="gray"
                tooltip="Change the current Server"
                class="flex items-center gap-0 sm:gap-1.5"
            >
                <span class="hidden sm:block">
                    Current Server:
                    @if(isset($serverOptions[$selectedServerId]))
                        {{ $serverOptions[$selectedServerId]['name'] }} -
                        x{{ $serverOptions[$selectedServerId]['experience_rate'] }}
                    @else
                        Default
                    @endif
                </span>
            </x-filament::button>
        </x-slot>

        <x-filament::dropdown.list>
            @foreach($serverOptions as $id => $server)
                <x-filament::dropdown.list.item
                    wire:click="updateServer({{ $id }})"
                    :color="$selectedServerId == $id ? 'primary' : 'gray'"
                    :icon="$selectedServerId == $id ? 'heroicon-c-check' : ''"
                >
                    {{ $server['name'] }} - x{{ $server['experience_rate'] }}
                </x-filament::dropdown.list.item>
            @endforeach
        </x-filament::dropdown.list>
    </x-filament::dropdown>
@else
    {{-- Regular markup --}}
    <flux:dropdown>
        <flux:button icon-trailing="chevron-down" variant="ghost">
            @if(isset($serverOptions[$selectedServerId]))
                {{ $serverOptions[$selectedServerId]['name'] }} -
                x{{ $serverOptions[$selectedServerId]['experience_rate'] }}
            @else
                Default
            @endif
        </flux:button>

        <flux:menu>
            <flux:menu.radio.group>
                @foreach($serverOptions as $id => $server)
                    <flux:menu.radio
                        wire:click="updateServer({{ $id }})"
                        :checked="$selectedServerId == $id"
                    >
                        {{ $server['name'] }} -
                        x{{ $server['experience_rate'] }}
                    </flux:menu.radio>
                @endforeach
            </flux:menu.radio.group>
        </flux:menu>
    </flux:dropdown>
@endif
