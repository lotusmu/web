<div class="space-y-6">
    <header>
        <flux:heading size="lg">
            {{ __('Appearance Settings') }}
        </flux:heading>

        <x-flux::subheading>
            {{ __('Update your appearance settings.') }}
        </x-flux::subheading>
    </header>

    <flux:radio.group x-data variant="segmented" x-model="$flux.appearance">
        <flux:radio value="light" icon="sun">{{__('Light')}}</flux:radio>
        <flux:radio value="dark" icon="moon">{{__('Dark')}}</flux:radio>
        <flux:radio value="system" icon="computer-desktop">{{__('System')}}</flux:radio>
    </flux:radio.group>
</div>