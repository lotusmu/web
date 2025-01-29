<?php

use App\Models\Game\CastleData;
use App\Models\Game\Guild;
use Livewire\Attributes\Computed;
use Livewire\Volt\Component;

new class extends Component {
    public ?CastleData $castle = null;

    public function mount()
    {
        $this->castle = CastleData::first();
    }

    #[Computed]
    public function guild()
    {
        return Guild::where('G_Name', $this->castle->OWNER_GUILD)->first();
    }
}; ?>

<div
    class="flex items-center justify-center gap-4 min-h-64 sm:bg-gradient-to-t from-zinc-950/10 dark:from-white/10 to-transparent to-90% rounded-xl sm:p-6">
    <img src="{{ $this->guild->getMarkUrl(124) }}"
         alt="Guild Mark"
         class="shrink-0 rounded-xl border border-zinc-200 dark:border-white/10 max-sm:self-start max-sm:mt-[2.75rem]"
    />

    <div class="flex flex-col items-start">
        <flux:heading size="xl" class="flex items-center max-sm:flex-col-reverse gap-2">
            <flux:link variant="ghost" href="#">
                {{ $this->guild->G_Name }}
            </flux:link>

            <flux:badge variant="pill" size="sm" color="amber">
                {{__('Castle Owner')}}
            </flux:badge>
        </flux:heading>

        <flux:subheading class="flex items-center gap-2">
            <flux:icon.crown variant="micro" color="orange"/>
            {{ __('Castle Lord:') }}
            <flux:link variant="ghost" href="#">
                {{ $this->guild->G_Master }}
            </flux:link>
        </flux:subheading>

        <div class="flex items-center max-sm:flex-col max-sm:items-start max-sm:gap-3 gap-6 mt-6">
            <div class="flex items-center gap-2">
                <flux:icon.castle variant="micro"/>
                <flux:text>
                    {{ __('CS Wins') }}: 4
                </flux:text>
            </div>

            <div class="flex items-center gap-2">
                <flux:icon.members variant="micro"/>
                <flux:text>
                    {{ __('Members') }}: 30
                </flux:text>
            </div>
        </div>
    </div>
</div>
