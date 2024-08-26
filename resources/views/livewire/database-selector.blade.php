<div>
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
</div>
