@php
    $advantages = [
        __('Keep your character stats private'),
        __('Hide your location from other players'),
        __('Full account information privacy')
    ];
@endphp

<flux:card class="flex-1 space-y-6 bg-zinc-800 dark:!bg-white !border-zinc-950 dark:!border-white">
    <flux:heading class="flex items-center gap-2 !text-white dark:!text-zinc-800">
        <flux:icon.eye-slash/>
        <span>{{__('Stealth Mode')}}</span>
    </flux:heading>

    <div class="space-y-2">
        @foreach($advantages as $advantage)
            <div class="flex gap-2 items-center">
                <flux:icon.check variant="mini" class="dark:text-emerald-500 text-emerald-400"/>
                <flux:text class="dark:!text-zinc-500 !text-white/70">
                    {{ __($advantage) }}
                </flux:text>
            </div>
        @endforeach
    </div>
</flux:card>
