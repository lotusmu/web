@php
    $disadvantages = [
        'Character stats are visible to everyone',
        'Players can track your location',
        'Account information is public',
    ];
@endphp

<flux:card class="flex-1 space-y-6">
    <flux:heading class="flex items-center gap-2">
        <flux:icon.eye/>
        <span>{{__('Normal Mode')}}</span>
    </flux:heading>

    <div class="space-y-2">
        @foreach($disadvantages as $disadvantage)
            <div class="flex gap-2 items-center">
                <flux:icon.x-mark variant="mini" class="text-red-500 dark:text-red-400"/>
                <flux:text>
                    {{ __($disadvantage) }}
                </flux:text>
            </div>
        @endforeach
    </div>
</flux:card>
