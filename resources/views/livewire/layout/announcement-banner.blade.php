<?php

use App\Enums\Utility\UpdateBannerType;
use App\Models\Utility\UpdateBanner;
use Livewire\Volt\Component;
use Livewire\Attributes\Computed;

new class extends Component {
    #[Computed]
    public function banner(): ?UpdateBanner
    {
        return UpdateBanner::where('is_active', true)
            ->where('type', UpdateBannerType::ANNOUNCEMENT)
            ->first();
    }

    public function dismiss(): void
    {
        $cookieData = [
            'id'        => $this->banner->id,
            'timestamp' => $this->banner->updated_at->timestamp,
            'checksum'  => md5($this->banner->content.$this->banner->url)
        ];

        Cookie::queue('announcement_dismissed', json_encode($cookieData), 60 * 24 * 30);
    }

    public function isBannerDismissed(): bool
    {
        if ( ! $this->banner) {
            return false;
        }

        $cookieValue = request()->cookie('announcement_dismissed');

        if ( ! $cookieValue) {
            return false;
        }

        try {
            $dismissed = json_decode($cookieValue, true);

            if ( ! is_array($dismissed) ||
                ! isset($dismissed['id']) ||
                ! isset($dismissed['timestamp']) ||
                ! isset($dismissed['checksum'])
            ) {
                return false;
            }

            return $dismissed['id'] === $this->banner->id
                && $dismissed['timestamp'] === $this->banner->updated_at->timestamp
                && $dismissed['checksum'] === md5($this->banner->content.$this->banner->url);

        } catch (Exception $e) {
            return false;
        }
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
