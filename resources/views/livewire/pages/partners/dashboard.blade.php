<?php

use App\Models\Partner\Partner;
use App\Models\Partner\PromoCodeUsage;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {
    public function getPartnerProperty(): Partner
    {
        return Partner::where('user_id', auth()->id())->firstOrFail();
    }

    public function getTotalReferralsProperty(): int
    {
        return PromoCodeUsage::where('partner_id', $this->partner->id)->count();
    }

    public function getThisMonthReferralsProperty(): int
    {
        return PromoCodeUsage::where('partner_id', $this->partner->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
    }

    public function getTotalTokensProperty(): int
    {
        return $this->partner->getTotalTokensEarned();
    }

    public function getTokensThisMonthProperty(): int
    {
        return $this->partner->getTokensEarnedThisMonth();
    }
}; ?>

<div class="space-y-6">
    <header>
        <flux:heading size="xl">
            {{ __('Partner Dashboard') }}
        </flux:heading>
        <flux:subheading>
            {{ __('Here\'s your partner overview.') }}
        </flux:subheading>
    </header>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <flux:card class="!p-4">
            <flux:subheading size="sm">
                {{ __('Total Referrals') }}
            </flux:subheading>
            <flux:heading size="lg" class="text-blue-600">
                {{ number_format($this->totalReferrals) }}
            </flux:heading>
        </flux:card>

        <flux:card class="!p-4">
            <flux:subheading size="sm">
                {{ __('This Month') }}
            </flux:subheading>
            <flux:heading size="lg" class="text-green-600">
                {{ number_format($this->thisMonthReferrals) }}
            </flux:heading>
        </flux:card>

        <flux:card class="!p-4">
            <flux:subheading size="sm">
                {{ __('Total Tokens') }}
            </flux:subheading>
            <flux:heading size="lg" class="text-purple-600">
                {{ number_format($this->totalTokens) }}
            </flux:heading>
        </flux:card>

        <flux:card class="!p-4">
            <flux:subheading size="sm">
                {{ __('Tokens This Month') }}
            </flux:subheading>
            <flux:heading size="lg" class="text-orange-600">
                {{ number_format($this->tokensThisMonth) }}
            </flux:heading>
        </flux:card>
    </div>

    <!-- Promo Code -->
    <flux:card>
        <flux:heading>
            {{ __('Promo Code') }}
        </flux:heading>

        <flux:subheading>
            {{ __('Share this code with your audience to earn :rate% tokens from their donations.', ['rate' => $this->partner->token_percentage]) }}
        </flux:subheading>

        <flux:input.group class="mt-4">
            <flux:input.group.prefix>
                {{ __('Promo Code') }}
            </flux:input.group.prefix>

            <flux:input :value="$this->partner->promo_code" readonly copyable/>
        </flux:input.group>
    </flux:card>

    <!-- Account Details -->
    <flux:card>
        <flux:heading>{{ __('Account Details') }}</flux:heading>

        <div class="mt-4 space-y-4">
            <div class="flex justify-between">
                <flux:subheading>{{ __('Partner Level') }}</flux:subheading>
                <flux:badge color="{{ $this->partner->level->badgeColor() }}"
                            inset="top bottom">{{ $this->partner->level->getLabel() }}</flux:badge>
            </div>
            <div class="flex justify-between">
                <flux:subheading>{{ __('Token Percentage') }}</flux:subheading>
                <flux:subheading class="font-semibold">{{ $this->partner->token_percentage }}%</flux:subheading>
            </div>
            <div class="flex justify-between">
                <flux:text>{{ __('Approved') }}</flux:text>
                <flux:text>{{ $this->partner->approved_at->format('M j, Y') }}</flux:text>
            </div>
        </div>
    </flux:card>

    <!-- Channels -->
    <flux:card>
        <flux:heading>{{ __('Your Channels') }}</flux:heading>
        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($this->partner->channels as $channel)
                <flux:input.group>
                    <flux:input.group.prefix>{{ ucfirst($channel['platform']) }}</flux:input.group.prefix>
                    <flux:input readonly :value="$channel['name']"/>
                </flux:input.group>
            @endforeach
        </div>
    </flux:card>

    <!-- Rules & Resources Section -->
    <flux:card>
        <flux:heading>{{ __('Partner Rules & Resources') }}</flux:heading>

        <div class="mt-4 space-y-4">
            <div>
                <flux:subheading>{{ __('Content Requirements') }}</flux:subheading>
                <flux:text class="mt-2">
                    • {{ __('Include your promo code in stream titles and video descriptions') }}<br>
                    • {{ __('Display brand banners during streams/videos') }}<br>
                    • {{ __('Maintain regular content schedule as specified in your application') }}
                </flux:text>
            </div>

            <div>
                <flux:subheading>{{ __('Weekly Review Process') }}</flux:subheading>
                <flux:text class="mt-2">
                    {{ __('Each week your content will be reviewed. Upon approval, you\'ll receive:') }}<br>
                    • {{ __('VIP status for the following week') }}<br>
                    • {{ __('Farm rewards based on your partner level') }}<br>
                    • {{ __('Discord streamer role permissions') }}
                </flux:text>
            </div>

            <div>
                <flux:subheading>{{ __('Brand Resources') }}</flux:subheading>
                <flux:text class="mt-2">
                    {{ __('Download brand assets and banners for your content:') }}
                </flux:text>
                <div class="mt-2 flex gap-2">
                    <flux:button variant="outline" size="sm">
                        {{ __('Stream Banners') }}
                    </flux:button>
                    <flux:button variant="outline" size="sm">
                        {{ __('Video Templates') }}
                    </flux:button>
                    <flux:button variant="outline" size="sm">
                        {{ __('Logo Pack') }}
                    </flux:button>
                </div>
            </div>
        </div>
    </flux:card>
</div>
