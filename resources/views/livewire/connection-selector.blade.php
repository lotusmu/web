<?php

use App\Actions\SwitchGameServer;
use App\Models\Utility\GameServer;
use Illuminate\Support\Collection;
use Livewire\Volt\Component;
use Filament\Notifications\Notification;

new class extends Component {

    public $filament = false;

    public $selectedServerId;

    public $serverOptions;

    public $triggerType;

    public function mount($triggerType = 'navbar'): void
    {
        $this->serverOptions    = $this->getServerOptions();
        $this->selectedServerId = session('selected_server_id', $this->serverOptions->keys()->first());
        $this->triggerType      = $triggerType;
    }

    public function updateServer($newServerId, $referer = null): void
    {
        try {
            if ( ! is_numeric($newServerId)) {
                throw new InvalidArgumentException('Invalid server ID format');
            }

            $server = GameServer::where('id', $newServerId)
                ->where('is_active', true)
                ->firstOrFail();

            $this->selectedServerId = $newServerId;

            app(SwitchGameServer::class)->execute($newServerId);

            $this->sendNotification($server);

            $this->redirect($referer ?? request()->header('Referer'), navigate: true);
        } catch (Exception $e) {
            $this->sendErrorNotification($e->getMessage());
        }
    }

    private function sendNotification(GameServer $server): void
    {
        $message = __('Switched to :server - x:rate', [
            'server' => $server->name,
            'rate'   => $server->experience_rate
        ]);

        if ($this->filament) {
            Notification::make()
                ->title(__('Success!'))
                ->body($message)
                ->success()
                ->send();
        } else {
            Flux::toast(
                text: $message,
                heading: __('Server Switched'),
                variant: 'success',
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

    public function getServerOptions(): Collection
    {
        static $requestCache = null;

        if ($requestCache !== null) {
            return $requestCache;
        }

        $requestCache = Cache::remember('all_server_options', now()->addMinutes(5), function () {
            return GameServer::where('is_active', true)
                ->get(['id', 'name', 'connection_name', 'experience_rate', 'online_multiplier', 'port'])
                ->mapWithKeys(function ($server) {
                    $status = $server->getStatus();

                    return [
                        $server->id => [
                            'name'            => $server->name,
                            'experience_rate' => $server->experience_rate,
                            'online_count'    => $status['multiplied_count'],
                            'is_online'       => $status['is_online'],
                            'last_updated'    => $status['last_updated'],
                            'port'            => $server->port,
                        ]
                    ];
                });
        });

        return $requestCache;
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
        {{--        Trigger dropdown --}}
        @if($this->triggerType === 'navbar')
            <flux:navbar.item icon-trailing="chevron-down">
                @if(isset($serverOptions[$selectedServerId]))
                    <span>
                    {{ $serverOptions[$selectedServerId]['name'] }} - x{{ $serverOptions[$selectedServerId]['experience_rate'] }}
                    </span>

                    <flux:badge variant="pill" insert="top bottom" size="sm" icon="users"
                                :color="$serverOptions[$selectedServerId]['is_online'] ? 'emerald' : 'rose'"
                                class="ml-2">
                        {{ $serverOptions[$selectedServerId]['online_count'] }}
                    </flux:badge>
                @else
                    Default
                @endif
            </flux:navbar.item>

        @elseif($this->triggerType === 'navlist')

            <flux:navlist.item icon-trailing="chevron-down">
                @if(isset($serverOptions[$selectedServerId]))
                    <span>
                    {{ $serverOptions[$selectedServerId]['name'] }} - x{{ $serverOptions[$selectedServerId]['experience_rate'] }}
                    </span>

                    <flux:badge variant="pill" insert="top bottom" size="sm" icon="users"
                                :color="$serverOptions[$selectedServerId]['is_online'] ? 'emerald' : 'rose'"
                                class="ml-2">
                        {{ $serverOptions[$selectedServerId]['online_count'] }}
                    </flux:badge>
                @else
                    Default
                @endif
            </flux:navlist.item>
        @endif

        {{--        Dropdown menu --}}
        <flux:menu>
            <flux:menu.radio.group>
                @foreach($serverOptions as $id => $server)
                    <flux:menu.radio
                        wire:click="updateServer({{ $id }})"
                        :checked="$selectedServerId == $id"
                    >
                        <div class="flex items-center justify-between w-full">
                            <span>{{ $server['name'] }} - x{{ $server['experience_rate'] }}</span>

                            <flux:badge variant="pill" inset="top bottom" size="sm" icon="users"
                                        :color="$serverOptions[$id]['is_online'] ? 'emerald' : 'rose'"
                                        class="ml-4">
                                {{ $serverOptions[$id]['online_count'] }}
                            </flux:badge>
                        </div>
                    </flux:menu.radio>
                @endforeach
            </flux:menu.radio.group>
        </flux:menu>
    </flux:dropdown>
@endif
