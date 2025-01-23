<?php

use Livewire\Volt\Component;
use App\Models\Utility\UpdateBanner;
use Livewire\Attributes\Computed;

new class extends Component {
    #[Computed]
    public function banner(): ?UpdateBanner
    {
        return UpdateBanner::getActive();
    }
}; ?>

<div>
    @if($this->banner)
        <div class="mt-24 sm:mt-32 lg:mt-16">
            @if($this->banner->url)
                {{-- With URL version --}}
                <a href="{{ $this->banner->url }}" class="inline-flex space-x-6" wire:navigate>
                    <flux:badge variant="pill"
                                :color="$this->banner->type->color()"
                                :icon="$this->banner->type->icon()"
                    >
                        {{ $this->banner->type->getLabel() }}
                    </flux:badge>
                    <flux:text class="flex items-center">
                        {{ $this->banner->content }}
                        <flux:icon.chevron-right variant="micro"/>
                    </flux:text>
                </a>
            @else
                {{-- Without URL version --}}
                <div class="inline-flex space-x-6">
                    <flux:badge variant="pill"
                                :color="$this->banner->type->color()"
                                :icon="$this->banner->type->icon()"
                    >
                        {{ $this->banner->type->getLabel() }}
                    </flux:badge>
                    <flux:text class="flex items-center">
                        {{ $this->banner->content }}
                    </flux:text>
                </div>
            @endif
        </div>
    @endif
</div>
