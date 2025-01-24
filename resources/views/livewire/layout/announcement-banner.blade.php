<?php

use App\Enums\Utility\UpdateBannerType;
use App\Models\Utility\UpdateBanner;
use Livewire\Volt\Component;
use Livewire\Attributes\Computed;

new class extends Component {
    public function dismiss(): void
    {
        session()->put('announcement_dismissed_id', $this->banner->id);
        session()->put('announcement_dismissed_checksum', md5($this->banner->content.$this->banner->url));
    }

    #[Computed]
    public function banner(): ?UpdateBanner
    {
        $cacheKey = 'active_announcement_banner';

        if (cache()->has($cacheKey)) {
            return cache()->get($cacheKey);
        }

        $banner = UpdateBanner::where('is_active', true)
            ->where('type', UpdateBannerType::ANNOUNCEMENT)
            ->first();

        if ($banner) {
            cache()->put($cacheKey, $banner, now()->addDay());
        }

        return $banner;
    }

    public function resetDismiss(): void
    {
        session()->forget('announcement_dismissed_id');
        session()->forget('announcement_dismissed_checksum');
    }

    public function isBannerDismissed(): bool
    {
        if ( ! $this->banner) {
            return false;
        }

        $dismissedId       = session('announcement_dismissed_id');
        $dismissedChecksum = session('announcement_dismissed_checksum');

        return $dismissedId === $this->banner->id && $dismissedChecksum === md5($this->banner->content.$this->banner->url);
    }
}; ?>

<div class="w-full">
    @if($this->banner && !$this->isBannerDismissed())
        <div
            class="h-fit bg-[repeating-linear-gradient(35deg,#e0f2fe_0px,#e0f2fe_20px,#cce9fd_20px,#cce9fd_40px)]">
            <div class="flex items-center justify-between w-full px-6 mx-auto h-full">
                <div class="flex-1"></div>

                <div class="flex items-center gap-2 py-2">
                    @if($this->banner->url)
                        {{-- With URL version --}}
                        <flux:link variant="ghost"
                                   :accent="false"
                                   href="{{ $this->banner->url }}"
                                   class="!text-zinc-900 text-sm">
                            {{ $this->banner->content }}
                        </flux:link>
                    @else
                        {{-- Without URL version --}}
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
