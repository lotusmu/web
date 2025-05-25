<?php

use App\Models\Partner\Partner;
use App\Models\Partner\PromoCodeUsage;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {
    public Partner $partner;
    public array $stats = [];

    public function mount()
    {
        $this->partner = Partner::where('user_id', auth()->id())->firstOrFail();

        $this->stats = [
            'total_referrals'      => PromoCodeUsage::where('partner_id', $this->partner->id)->count(),
            'this_month_referrals' => PromoCodeUsage::where('partner_id', $this->partner->id)
                ->whereMonth('created_at', now()->month)
                ->count(),
            'total_commission'     => PromoCodeUsage::where('partner_id', $this->partner->id)
                ->sum('commission_amount'),
            'pending_commission'   => PromoCodeUsage::where('partner_id', $this->partner->id)
                ->whereNull('paid_at')
                ->sum('commission_amount'),
        ];
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
                {{ $stats['total_referrals'] }}
            </flux:heading>
        </flux:card>


        <flux:card class="!p-4">
            <flux:subheading size="sm">
                {{ __('This Month') }}
            </flux:subheading>
            <flux:heading size="lg" class="text-green-600">
                {{ $stats['this_month_referrals'] }}
            </flux:heading>
        </flux:card>

        <flux:card class="!p-4">
            <flux:subheading size="sm">
                {{ __('Total Earned') }}
            </flux:subheading>
            <flux:heading size="lg" class="text-purple-600">
                ${{ number_format($stats['total_commission'], 2) }}
            </flux:heading>
        </flux:card>

        <flux:card class="!p-4">
            <flux:subheading size="sm">
                {{ __('Pending Payout') }}
            </flux:subheading>
            <flux:heading size="lg" class="text-orange-600">
                ${{ number_format($stats['pending_commission'], 2) }}
            </flux:heading>
        </flux:card>
    </div>

    <!-- Promo Code -->
    <flux:card>
        <flux:heading>
            {{ __('Promo Code') }}
        </flux:heading>

        <flux:subheading>
            {{ __('Share this code with your audience to earn :rate% commission from their donations.', ['rate' => $partner->commission_rate]) }}
        </flux:subheading>

        <flux:input.group class="mt-4">
            <flux:input.group.prefix>
                {{ __('Promo Code') }}
            </flux:input.group.prefix>

            <flux:input :value="$partner->promo_code" readonly copyable/>
        </flux:input.group>

    </flux:card>

    <!-- Account Details -->
    <flux:card>
        <flяux:heading>{{ __('Account Details') }}</flяux:heading>

        <div class="mt-4 space-y-4">
            <div class="flex justify-between">
                <flux:subheading>{{ __('Partner Level') }}</flux:subheading>
                <flux:badge color="blue" inset="top bottom">{{ $partner->level->getLabel() }}</flux:badge>
            </div>
            <div class="flex justify-between">
                <flux:subheading>{{ __('Commission Rate') }}</flux:subheading>
                <flux:subheading class="font-semibold">{{ $partner->commission_rate }}%</flux:subheading>
            </div>
            <div class="flex justify-between">
                <flux:text>{{ __('Approved') }}</flux:text>
                <flux:text>{{ $partner->approved_at->format('M j, Y') }}</flux:text>
            </div>
        </div>
    </flux:card>

    <!-- Channels -->
    <flux:card>
        <flux:heading>{{ __('Your Channels') }}</flux:heading>
        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($partner->channels as $channel)
                <flux:input.group>
                    <flux:input.group.prefix>{{ ucfirst($channel['platform']) }}</flux:input.group.prefix>
                    <flux:input readonly :value="$channel['name']"/>
                </flux:input.group>
            @endforeach
        </div>
    </flux:card>
</div>
