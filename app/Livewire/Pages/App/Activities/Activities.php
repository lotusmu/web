<?php

namespace App\Livewire\Pages\App\Activities;

use App\Enums\Utility\ActivityType;
use App\Livewire\BaseComponent;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Spatie\Activitylog\Models\Activity;

class Activities extends BaseComponent
{
    #[Computed]
    public function activities()
    {
        return Activity::forSubject(Auth::user())
            ->latest()
            ->simplePaginate(10);
    }

    public function setBadgeColor($activity): string
    {
        $activityType = $this->getActivityType($activity);

        return $activityType->getColor();
    }

    private function getActivityType($activity): ActivityType
    {
        $activityType = $activity->properties['activity_type'] ?? null;

        if ($activityType === null) {
            return ActivityType::DEFAULT;
        }

        return ActivityType::tryFrom((string) $activityType) ?? ActivityType::DEFAULT;
    }

    protected function getViewName(): string
    {
        return 'pages.activities.index';
    }

    protected function getLayoutType(): string
    {
        return 'app';
    }
}
