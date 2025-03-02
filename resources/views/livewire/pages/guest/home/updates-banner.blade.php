<?php

use App\Actions\Banners\RetrieveBanner;
use App\Enums\Utility\UpdateBannerType;
use Livewire\Volt\Component;
use App\Models\Utility\UpdateBanner;
use App\Models\Utility\GameServer;
use Livewire\Attributes\Computed;

new class extends Component {
    protected RetrieveBanner $retrieveBanner;

    public function boot(RetrieveBanner $retrieveBanner)
    {
        $this->retrieveBanner = $retrieveBanner;
    }

    #[Computed]
    public function banner(): ?UpdateBanner
    {
        if ($this->shouldShowLaunchServer()) {
            return null;
        }

        return $this->retrieveBanner->getUpdate();
    }

    #[Computed]
    public function launchServer(): ?GameServer
    {
        return $this->retrieveBanner->getLaunchServer();
    }

    #[Computed]
    public function shouldShowLaunchServer(): bool
    {
        return $this->retrieveBanner->shouldShowLaunchServer($this->launchServer);
    }

    public function getCountdownData(): string
    {
        if ( ! $this->launchServer || ! $this->launchServer->launch_date) {
            return '{}';
        }

        $launchDate               = $this->launchServer->launch_date;
        $isPastLaunch             = now()->gt($launchDate) ? 'true' : 'false';
        $isWithinPostLaunchPeriod = (now()->gt($launchDate) && now()->diffInDays($launchDate) <= 7) ? 'true' : 'false';

        return <<<JS
    {
        launchDate: '{$launchDate->format('Y-m-d H:i:s')}',
        days: 0,
        hours: 0,
        minutes: 0,
        seconds: 0,
        isPastLaunch: {$isPastLaunch},
        isWithinPostLaunchPeriod: {$isWithinPostLaunchPeriod},
        updateCountdown() {
            const now = new Date().getTime();
            const launch = new Date(this.launchDate).getTime();
            const diff = launch - now;

            if (diff <= 0) {
                this.isPastLaunch = true;
                return;
            }

            this.days = Math.floor(diff / (1000 * 60 * 60 * 24));
            this.hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            this.minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            this.seconds = Math.floor((diff % (1000 * 60)) / 1000);
        }
    }
    JS;
    }
}; ?>

<div>
    @if($this->banner)
        <div class="mt-0 sm:mt-32 lg:mt-16">
            @if($this->banner->url)
                <a href="{{ $this->banner->url }}" wire:navigate.hover
                   class="flex flex-wrap gap-3 sm:gap-6 items-center group">
                    <flux:badge variant="pill"
                                :color="$this->banner->type->color()"
                                :icon="$this->banner->type->icon()"
                    >
                        {{ $this->banner->type->getLabel() }}
                    </flux:badge>
                    <flux:text class="flex items-center group-hover:opacity-90 transition-opacity">
                        {{ $this->banner->content }}
                        <flux:icon.chevron-right variant="micro"/>
                    </flux:text>
                </a>
            @else
                <div class="flex flex-wrap gap-3 sm:gap-6 items-center">
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
    @elseif($this->launchServer && $this->launchServer->launch_date)
        <div
            x-data="{{ $this->getCountdownData() }}"
            x-init="updateCountdown(); setInterval(() => updateCountdown(), 1000)"
            class="mt-0 sm:mt-32 lg:mt-16 flex flex-wrap gap-3 sm:gap-6 items-center"
        >
            <flux:badge variant="pill"
                        :color="UpdateBannerType::LAUNCHING->color()"
                        :icon="UpdateBannerType::LAUNCHING->icon()"
            >
                <span x-text="isPastLaunch ? 'Now Live!' : '{{ UpdateBannerType::LAUNCHING->getLabel() }}'"></span>
            </flux:badge>

            <flux:text class="flex flex-wrap items-center">
                <template x-if="isPastLaunch">
                    <span>{{ $this->launchServer->name }} {{ __('has launched!') }}</span>
                </template>
                <template x-if="!isPastLaunch">
                    <div class="flex flex-wrap items-center">
                        <span
                            class="mr-1 whitespace-normal sm:whitespace-nowrap">{{ $this->launchServer->name }} {{__('launching in')}}:</span>
                        <div class="flex flex-nowrap">
                            <span class="font-semibold" x-text="days"></span>d
                            <span class="font-semibold ml-1" x-text="hours"></span>h
                            <span class="font-semibold ml-1" x-text="minutes"></span>m
                            <span class="font-semibold ml-1" x-text="seconds"></span>s
                        </div>
                    </div>
                </template>
            </flux:text>
        </div>
    @endif
</div>
