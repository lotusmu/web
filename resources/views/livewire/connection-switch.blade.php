<?php

use App\Models\Concerns\GameConnectionSelector;
use Livewire\Volt\Component;

new class extends Component {
    use GameConnectionSelector;

    public $filament = false;

    public function mount(): void
    {
        $this->loadConnectionOptions();
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
