<?php

use App\Models\Game\Guild;
use Livewire\Volt\Component;

new class extends Component {
    public ?Guild $guild = null;

    public function mount(Guild $guild)
    {
        $this->guild = $guild;
    }
}; ?>

<header class="flex items-center gap-4">
    <img src="{{ $this->guild->getMarkUrl(72) }}"
         alt="Guild Mark"
         class="shrink-0 rounded-xl border border-zinc-200 dark:border-white/10"
    />

    <div class="flex flex-col items-start">
        <flux:heading size="xl" class="flex items-center gap-2">
            <flux:link variant="ghost" :href="route('guild', [ 'name' => $this->guild->G_Name ])">
                {{ $this->guild->G_Name }}
            </flux:link>

            <flux:badge variant="pill" size="sm" inset="top bottom" color="amber">
                {{__('Castle Owner')}}
            </flux:badge>
        </flux:heading>

        <flux:subheading class="flex items-center gap-2">
            <flux:icon.crown variant="micro" color="orange"/>
            {{ __('Castle Lord:') }}
            <flux:link variant="ghost" :href="route('character' , [ 'name' => $this->guild->G_Master ])">
                {{ $this->guild->G_Master }}
            </flux:link>
        </flux:subheading>
    </div>
</header>
