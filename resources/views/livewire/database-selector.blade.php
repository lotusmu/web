<div>
    <x-filament::dropdown>
        <x-slot name="trigger">
            <x-filament::button
                icon="heroicon-o-server-stack"
                color="gray">
                <span class="hidden sm:block">
                Current Server:
                @if(isset($databaseOptions[$selectedDatabase]))
                        {{ $databaseOptions[$selectedDatabase]['name'] }} -
                        x{{ $databaseOptions[$selectedDatabase]['experience_rate'] }}
                    @else
                        Unknown
                    @endif
                    </span>
            </x-filament::button>
        </x-slot>

        <x-filament::dropdown.list>
            @foreach($databaseOptions as $value => $server)
                <x-filament::dropdown.list.item
                    wire:click="updateDatabase('{{ $value }}')"
                    :color="$selectedDatabase === $value ? 'primary' : 'gray'"
                >
                    {{ $server['name'] }} - x{{ $server['experience_rate'] }}
                </x-filament::dropdown.list.item>
            @endforeach
        </x-filament::dropdown.list>
    </x-filament::dropdown>
</div>
