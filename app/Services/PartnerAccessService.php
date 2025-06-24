<?php

namespace App\Services;

use App\Enums\Partner\ApplicationStatus;
use App\Models\Partner\Partner;
use App\Models\Partner\PartnerApplication;
use App\Models\User\User;

class PartnerAccessService
{
    public function __construct(private User $user) {}

    public function getRedirectRoute(): string
    {
        if ($this->isActivePartner()) {
            return 'partners.dashboard';
        }

        if ($this->hasPendingApplication()) {
            return 'partners.status';
        }

        if ($this->isInCooldownPeriod()) {
            return 'partners.status';
        }

        return 'partners.apply';
    }

    public function canAccessForm(): bool
    {
        return ! $this->isActivePartner()
            && ! $this->hasPendingApplication()
            && ! $this->isInCooldownPeriod();
    }

    private function isActivePartner(): bool
    {
        return Partner::where('user_id', $this->user->id)
            ->where('status', 'active')
            ->exists();
    }

    private function hasPendingApplication(): bool
    {
        return $this->getLatestApplication()?->status === ApplicationStatus::PENDING;
    }

    private function isInCooldownPeriod(): bool
    {
        $application = $this->getLatestApplication();

        if (! $application || $application->status !== ApplicationStatus::REJECTED) {
            return false;
        }

        return now()->lessThan($application->created_at->addMonths(6));
    }

    private function getLatestApplication(): ?PartnerApplication
    {
        return PartnerApplication::where('user_id', $this->user->id)->latest()->first();
    }
}
