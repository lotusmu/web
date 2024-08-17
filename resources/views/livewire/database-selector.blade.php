<div>
    <x-filament::button
        color="secondary"
        icon="heroicon-o-server-stack"
        wire:click="$dispatch('open-modal', { id: 'database-selector' })"
    >
        Select Server
    </x-filament::button>

    <x-filament::modal id="database-selector" width="sm">
        <x-slot name="heading">
            Select Database
        </x-slot>

        <form wire:submit="save">
            {{ $this->form }}

            <x-slot name="footer">
                <x-filament::button type="submit">
                    Change Database
                </x-filament::button>
            </x-slot>
        </form>
    </x-filament::modal>
</div>
