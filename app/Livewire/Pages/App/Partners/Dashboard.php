<?php

namespace App\Livewire\Pages\App\Partners;

use App\Actions\Partner\DownloadBrandAssetsAction;
use App\Enums\Partner\PartnerLevel;
use App\Livewire\BaseComponent;
use App\Models\Partner\Partner;
use App\Models\Partner\PartnerBrandAsset;
use App\Models\Partner\PartnerFarmPackage;
use App\Models\Partner\PromoCodeUsage;
use Livewire\Attributes\Computed;

class Dashboard extends BaseComponent
{
    public int $previewLevelIndex = 0;

    public function mount()
    {
        // Set initial preview to next level if available, otherwise first level
        $currentLevelIndex = array_search($this->partner->level, PartnerLevel::cases());
        $this->previewLevelIndex = $currentLevelIndex !== false && $currentLevelIndex < count(PartnerLevel::cases()) - 1
            ? $currentLevelIndex + 1
            : 0;
    }

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

    #[Computed]
    public function allLevels(): array
    {
        return PartnerLevel::cases();
    }

    #[Computed]
    public function previewLevel(): PartnerLevel
    {
        return $this->allLevels[$this->previewLevelIndex];
    }

    #[Computed]
    public function previewFarmPackage()
    {
        return PartnerFarmPackage::active()->forLevel($this->previewLevel)->first();
    }

    #[Computed]
    public function canNavigatePrevious(): bool
    {
        return $this->previewLevelIndex > 0;
    }

    #[Computed]
    public function canNavigateNext(): bool
    {
        return $this->previewLevelIndex < count($this->allLevels) - 1;
    }

    #[Computed]
    public function isCurrentLevel(): bool
    {
        return $this->previewLevel === $this->partner->level;
    }

    #[Computed]
    public function isNextLevel(): bool
    {
        $currentLevelIndex = array_search($this->partner->level, PartnerLevel::cases());

        return $currentLevelIndex !== false && $this->previewLevelIndex === $currentLevelIndex + 1;
    }

    public function previousLevel()
    {
        if ($this->canNavigatePrevious) {
            $this->previewLevelIndex--;
        }
    }

    public function nextLevel()
    {
        if ($this->canNavigateNext) {
            $this->previewLevelIndex++;
        }
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

    protected function getViewName(): string
    {
        return 'pages.app.partners.dashboard';
    }

    protected function getLayoutType(): string
    {
        return 'app';
    }
}
