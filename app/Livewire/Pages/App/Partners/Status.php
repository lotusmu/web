<?php

namespace App\Livewire\Pages\App\Partners;

use App\Enums\Partner\ApplicationStatus;
use App\Livewire\BaseComponent;
use App\Models\Partner\PartnerApplication;

class Status extends BaseComponent
{
    public ?PartnerApplication $application = null;

    public function mount()
    {
        $this->application = PartnerApplication::where('user_id', auth()->id())
            ->latest()
            ->first();
    }

    public function getCanReapplyProperty(): bool
    {
        if (! $this->application || $this->application->status !== ApplicationStatus::REJECTED) {
            return false;
        }

        $sixMonthsAfter = $this->application->created_at->addMonths(6);

        return now()->greaterThanOrEqualTo($sixMonthsAfter);
    }

    public function getReapplyDateProperty(): ?string
    {
        if (! $this->application || $this->application->status !== ApplicationStatus::REJECTED) {
            return null;
        }

        return $this->application->created_at->addMonths(6)->format('M j, Y');
    }

    protected function getViewName(): string
    {
        return 'pages.app.partners.status';
    }

    protected function getLayoutType(): string
    {
        return 'app';
    }
}
