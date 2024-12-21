<?php

use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {
    public int $treasury = 0;

    public function mount(int $treasury)
    {
        $this->treasury = $treasury;
    }

    #[On('treasury-updated')]
    public function updateTreasury(int $treasury): void
    {
        $this->treasury = $treasury;
    }
}; ?>

<flux:card class="space-y-6 bg-zinc-800 dark:!bg-white !border-zinc-950 dark:!border-white relative">
    <flux:heading size="lg" class="flex items-center gap-2 !text-white dark:!text-zinc-800">
        <flux:icon.piggy-bank variant="mini" color="orange"/>
        <span>{{__('Castle Treasury')}}</span>

        <flux:icon.piggy-bank class="absolute max-sm:w-20 w-28 max-sm:h-20 h-28 mr-4 right-0 bottom-0 opacity-25"/>
    </flux:heading>

    <div>
        <div class="text-white dark:text-zinc-800 text-3xl font-black mb-2">
            {{ number_format($this->treasury) }}
        </div>

        <flux:subheading class="dark:!text-zinc-500 !text-white/70">
            {{ __('Available Zen') }}
        </flux:subheading>
    </div>
</flux:card>
