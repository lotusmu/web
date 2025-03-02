<?php

use App\Actions\Banners\RetrieveBanner;
use App\Actions\Banners\DismissBanner;
use App\Models\Utility\UpdateBanner;
use Livewire\Volt\Component;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Cookie;

new class extends Component {
    protected RetrieveBanner $retrieveBanner;
    protected DismissBanner $dismissBanner;

    public function boot(RetrieveBanner $retrieveBanner, DismissBanner $dismissBanner)
    {
        $this->retrieveBanner = $retrieveBanner;
        $this->dismissBanner  = $dismissBanner;
    }

    #[Computed]
    public function banner(): ?UpdateBanner
    {
        return $this->retrieveBanner->getAnnouncement();
    }

    public function dismiss(): void
    {
        if ($this->banner) {
            $this->dismissBanner->dismiss($this->banner);
        }
    }

    #[Computed]
    public function isBannerDismissed(): bool
    {
        if ( ! $this->banner) {
            return false;
        }

        return $this->dismissBanner->isDismissed($this->banner);
    }
}; ?>

<div class="w-full">
    @if($this->banner && !$this->isBannerDismissed)
        <div
            class="h-fit bg-[repeating-linear-gradient(35deg,#e0f2fe_0px,#e0f2fe_20px,#cce9fd_20px,#cce9fd_40px)]">
            <div class="flex items-center justify-between w-full px-6 mx-auto h-full">
                <div class="flex-1"></div>

                <div class="flex items-center gap-2 py-2">
                    @if($this->banner->url)
                        <flux:link variant="ghost"
                                   :accent="false"
                                   href="{{ $this->banner->url }}"
                                   class="!text-zinc-900 text-sm">
                            {{ $this->banner->content }}
                        </flux:link>
                    @else
                        <flux:text class="!text-zinc-900">
                            {{ $this->banner->content }}
                        </flux:text>
                    @endif
                </div>

                <div class="flex-1 flex justify-end ml-6">
                    <button wire:click="dismiss" class="p-1 text-zinc-700 hover:text-zinc-950">
                        <flux:icon.x-mark class="w-6 h-6"/>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
