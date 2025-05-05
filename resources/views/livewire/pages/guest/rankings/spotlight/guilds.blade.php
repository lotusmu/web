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
        return Guild::query()
            ->select([
                'G_Name',
                'G_Mark',
                'G_Master',
                'CS_Wins',
            ])
            ->where('G_Name', $this->castle->OWNER_GUILD)
            ->withCount('members')
            ->first();
    }
}; ?>

<div
    class="flex items-center justify-center gap-4 min-h-72 sm:bg-gradient-to-t from-zinc-950/10 dark:from-white/10 to-transparent to-90% rounded-xl sm:p-6">
    <img src="{{ $this->guild->getMarkUrl(124) }}"
         alt="Guild Mark"
         class="shrink-0 rounded-xl border border-zinc-200 dark:border-white/10 max-sm:self-start max-sm:mt-[2.75rem]"
    />

    <div class="flex flex-col items-start">
        <flux:heading size="xl" class="flex items-center max-sm:flex-col-reverse gap-2">
            <flux:link variant="ghost" :href="route('guild', ['name' => $this->guild->G_Name])">
                {{ $this->guild->G_Name }}
            </flux:link>

            <flux:badge variant="pill" size="sm" color="amber">
                {{__('Castle Owner')}}
            </flux:badge>
        </flux:heading>

        <flux:subheading class="flex items-center gap-2">
            <flux:icon.crown variant="micro" color="orange"/>
            {{ __('Castle Lord:') }}
            <flux:link variant="ghost" :href="route('character', ['name' => $this->guild->G_Master])">
                {{ $this->guild->G_Master }}
            </flux:link>
        </flux:subheading>

        <div class="flex items-center max-sm:flex-col max-sm:items-start max-sm:gap-3 gap-6 mt-6">
            <div class="flex items-center gap-2">
                <flux:icon.castle variant="micro"/>
                <flux:text>
                    {{ __('CS Wins') }}:
                    <span>{{ $this->guild->CS_Wins }}</span>
                </flux:text>
            </div>

            <div class="flex items-center gap-2">
                <flux:icon.members variant="micro"/>
                <flux:text>
                    {{ __('Members') }}:
                    <span>{{ $this->guild->members_count }}</span>
                </flux:text>
            </div>
        </div>
    </div>
</div>
