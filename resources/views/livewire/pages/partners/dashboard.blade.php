<?php

use App\Actions\Partner\DownloadBrandAssetsAction;
use App\Enums\Game\BankItem;
use App\Models\Partner\Partner;
use App\Models\Partner\PartnerBrandAsset;
use App\Models\Partner\PartnerFarmPackage;
use App\Models\Partner\PromoCodeUsage;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {
    #[Computed]
    public function partner(): Partner
    {
        return Partner::where('user_id', auth()->id())->firstOrFail();
    }

    #[Computed]
    public function totalReferrals(): int
    {
        return PromoCodeUsage::where('partner_id', $this->partner->id)->count();
    }

    #[Computed]
    public function thisMonthReferrals(): int
    {
        return PromoCodeUsage::where('partner_id', $this->partner->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
    }

    #[Computed]
    public function totalTokens(): int
    {
        return $this->partner->getTotalTokensEarned();
    }

    #[Computed]
    public function tokensThisMonth(): int
    {
        return $this->partner->getTokensEarnedThisMonth();
    }

    #[Computed]
    public function brandAssets()
    {
        return PartnerBrandAsset::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function downloadAsset($assetId, DownloadBrandAssetsAction $action)
    {
        try {
            return $action->handle($assetId, auth()->user());
        } catch (Exception $e) {
            if ($e->getMessage() !== 'Rate limit exceeded.') {
                Flux::toast(
                    text: $e->getMessage(),
                    heading: __('Download Failed'),
                    variant: 'danger'
                );
            }
        }
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
    <flux:card class="space-y-6">
        <div>
            <flux:heading>
                {{ __('Promo Code') }}
            </flux:heading>

            <flux:subheading>
                {{ __('Share this code with your audience to earn :rate% tokens from their donations.', ['rate' => $this->partner->token_percentage]) }}
            </flux:subheading>
        </div>

        <flux:input.group>
            <flux:input.group.prefix>
                {{ __('Promo Code') }}
            </flux:input.group.prefix>

            <flux:input :value="$this->partner->promo_code" readonly copyable/>
        </flux:input.group>
    </flux:card>

    <!-- Partner Level & Benefits -->
    <flux:card class="space-y-6">
        <flux:heading>{{ __('Partner Level & Benefits') }}</flux:heading>

        <!-- Current Level -->
        <flux:card
            class="!bg-gradient-to-r from-purple-600/15 to-blue-600/15 dark:from-purple-600/30 dark:to-blue-600/30 backdrop-blur-lg rounded-xl border !border-purple-500/30">
            <div class="flex items-center justify-between mb-4">
                <flux:subheading class="flex items-center gap-2">
                    <flux:icon.star variant="solid" class="text-blue-500"/>
                    {{ __('Current Level') }}
                </flux:subheading>
                <flux:badge color="{{ $this->partner->level->badgeColor() }}"
                            inset="top bottom"
                            size="sm">
                    {{ $this->partner->level->getLabel() }}
                </flux:badge>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex items-center gap-3">
                    <flux:icon.currency-dollar variant="solid" class="text-green-500 flex-shrink-0"/>
                    <div>
                        <flux:text class="font-semibold">
                            {{ $this->partner->token_percentage }}
                            % {{ __('Commission') }}
                        </flux:text>
                        <flux:text size="sm"
                        >{{ __('From donations with your promo code') }}</flux:text>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <flux:icon.gift variant="solid" class="text-purple-500 flex-shrink-0"/>
                    <div>
                        <flux:text class="font-semibold">{{ __('Weekly Farm Rewards') }}</flux:text>
                        <flux:text size="sm">
                            @php
                                $farmPackage = PartnerFarmPackage::active()->forLevel($this->partner->level)->first();
                            @endphp
                            @if($farmPackage && $farmPackage->items)
                                @foreach($farmPackage->items as $item)
                                    @php
                                        $bankItem = BankItem::tryFrom($item['item_index'] ?? null);
                                        $itemName = $bankItem ? $bankItem->getName() : 'Unknown Item';
                                        $level = $item['item_level'] ?? 0;
                                        $quantity = $item['quantity'] ?? 1;

                                        if ($bankItem === BankItem::LOCHS_FEATHER && $level === 1) {
                                            $itemName = "Monarch's Crest";
                                        }
                                    @endphp
                                    {{ $quantity }}
                                    x {{ $itemName }}{{ $level > 0 && $bankItem !== BankItem::LOCHS_FEATHER ? " (Level $level)" : '' }}@if(!$loop->last)
                                        ,
                                    @endif
                                @endforeach
                            @else
                                {{ __('No rewards configured') }}
                            @endif
                        </flux:text>
                    </div>
                </div>
            </div>
        </flux:card>

        @if($this->partner->level->getNextLevel())
            <!-- Next Level Preview -->
            @php
                $nextLevel = $this->partner->level->getNextLevel();
                $nextFarmPackage = PartnerFarmPackage::active()->forLevel($nextLevel)->first();
            @endphp
            <flux:card
                class="!bg-gradient-to-r from-slate-50 to-slate-100 dark:from-slate-800/50 dark:to-slate-700/50 rounded-lg border !border-slate-200 dark:!border-slate-700">
                <div class="flex items-center justify-between mb-4">
                    <flux:subheading class="flex items-center gap-2">
                        <flux:icon.arrow-up variant="solid"/>
                        {{ __('Next Level Benefits') }}
                    </flux:subheading>
                    <flux:badge color="{{ $nextLevel->badgeColor() }}"
                                inset="top bottom"
                                size="sm">
                        {{ $nextLevel->getLabel() }}
                    </flux:badge>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-center gap-3">
                        <flux:icon.currency-dollar variant="solid" class="text-green-500 flex-shrink-0"/>
                        <div>
                            <flux:text class="font-semibold">{{ $nextLevel->getTokenPercentage() }}
                                % {{ __('Commission') }}</flux:text>
                            <flux:text size="sm">
                                {{ __('+:increase% increase', ['increase' => $nextLevel->getTokenPercentage() - $this->partner->level->getTokenPercentage()]) }}
                            </flux:text>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <flux:icon.gift variant="solid" class="text-purple-500 flex-shrink-0"/>
                        <div>
                            <flux:text class="font-semibold">{{ __('Enhanced Farm Rewards') }}</flux:text>
                            <flux:text size="sm">
                                @if($nextFarmPackage && $nextFarmPackage->items)
                                    @foreach($nextFarmPackage->items as $item)
                                        @php
                                            $bankItem = BankItem::tryFrom($item['item_index'] ?? null);
                                            $itemName = $bankItem ? $bankItem->getName() : 'Unknown Item';
                                            $level = $item['item_level'] ?? 0;
                                            $quantity = $item['quantity'] ?? 1;

                                            if ($bankItem === BankItem::LOCHS_FEATHER && $level === 1) {
                                                $itemName = "Monarch's Crest";
                                            }
                                        @endphp
                                        {{ $quantity }}
                                        x {{ $itemName }}{{ $level > 0 && $bankItem !== BankItem::LOCHS_FEATHER ? " (Level $level)" : '' }}@if(!$loop->last)
                                            ,
                                        @endif
                                    @endforeach
                                @else
                                    {{ __('Better rewards await!') }}
                                @endif
                            </flux:text>
                        </div>
                    </div>
                </div>

                <flux:separator class="my-4"/>

                <div>
                    <flux:text size="sm">
                        {{ __('Keep creating great content to unlock the next level!') }}
                    </flux:text>
                </div>
            </flux:card>
        @else
            <!-- Max Level Reached -->
            <div
                class="p-4 bg-gradient-to-r from-yellow-50 to-orange-50 dark:from-yellow-900/20 dark:to-orange-900/20 rounded-lg border border-yellow-200 dark:border-yellow-800">
                <div class="flex items-center gap-3">
                    <flux:icon.trophy class="text-yellow-500"/>
                    <div>
                        <flux:text
                            class="font-semibold text-yellow-700 dark:text-yellow-300">{{ __('Maximum Level Reached!') }}</flux:text>
                        <flux:text
                            size="sm">{{ __('You\'ve achieved the highest partner level. Congratulations!') }}</flux:text>
                    </div>
                </div>
            </div>
        @endif

        <flux:separator/>

        <!-- Account Info -->
        <div class="flex justify-between items-center">
            <flux:text>{{ __('Partner since') }}</flux:text>
            <flux:text>{{ $this->partner->approved_at->format('M j, Y') }}</flux:text>
        </div>
    </flux:card>

    <!-- Rules & Resources Section -->
    <flux:card class="space-y-6">
        <flux:heading>{{ __('Partner Rules & Resources') }}</flux:heading>

        <div>
            <flux:heading>{{ __('Content Requirements') }}</flux:heading>
            <flux:subheading>
                • {{ __('Include server name in stream titles (e.g., "Lotus Mu, Valaris - x5").') }}<br>
                • {{ __('Display brand banners or logo during streams/videos.') }}<br>
                • {{ __('Maintain regular content schedule as specified in your application.') }}
            </flux:subheading>
        </div>

        <div>
            <flux:heading>{{ __('Weekly Review Process') }}</flux:heading>
            <flux:subheading>
                {{ __('Each week your content will be reviewed. Upon approval, you\'ll receive:') }}<br>
                • {{ __('VIP status for the following week.') }}<br>
                • {{ __('Farm rewards based on your partner level.') }}<br>
                • {{ __('Discord streamer role permissions.') }}
            </flux:subheading>
        </div>

        @if($this->brandAssets->count() > 0)
            <div class="space-y-4">
                <div>
                    <flux:heading>{{ __('Brand Resources') }}</flux:heading>
                    <flux:subheading>
                        {{ __('Download brand assets and banners for your content:') }}
                    </flux:subheading>
                </div>

                <flux:table>
                    <flux:columns>
                        <flux:column>{{ __('Server') }}</flux:column>
                        <flux:column></flux:column>
                    </flux:columns>

                    <flux:rows>
                        @foreach($this->brandAssets as $asset)
                            <flux:row>
                                <flux:cell>
                                    <flux:text class="font-medium">{{ $asset->name }}</flux:text>
                                </flux:cell>
                                <flux:cell align="end">
                                    <flux:button
                                        icon="arrow-down-tray"
                                        variant="filled"
                                        size="sm"
                                        wire:click="downloadAsset({{ $asset->id }})"
                                    >
                                        {{ __('Download') }}
                                    </flux:button>
                                </flux:cell>
                            </flux:row>
                        @endforeach
                    </flux:rows>
                </flux:table>
            </div>
        @endif
    </flux:card>
</div>
