<?php

namespace App\Filament\Resources\ReferralSurveyResource\Widgets;

use App\Enums\Survey\ReferralSource;
use App\Models\User\ReferralSurvey;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class SurveyStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalSurveys = ReferralSurvey::whereNotNull('shown_at')->count();
        $completedCount = ReferralSurvey::where('completed', true)->count();
        $dismissedCount = ReferralSurvey::where('dismissed', true)->count();

        $completionRate = $totalSurveys > 0 ? round(($completedCount / $totalSurveys) * 100, 1) : 0;
        $dismissRate = $totalSurveys > 0 ? round(($dismissedCount / $totalSurveys) * 100, 1) : 0;

        $topSource = ReferralSurvey::whereNotNull('referral_source')
            ->where('completed', true)
            ->select(DB::raw('referral_source as source_value'), DB::raw('count(*) as count'))
            ->groupBy('referral_source')
            ->orderByDesc('count')
            ->first();

        $topSourceLabel = 'No data';
        if ($topSource) {
            $topSourceLabel = ReferralSource::from($topSource->source_value)->getLabel();
        }

        return [
            Stat::make('Total Surveys', $totalSurveys)
                ->description('Surveys shown to users')
                ->color('gray'),

            Stat::make('Completion Rate', $completionRate.'%')
                ->description($completedCount.' completed')
                ->color('success'),

            Stat::make('Dismiss Rate', $dismissRate.'%')
                ->description($dismissedCount.' dismissed')
                ->color('danger'),

            Stat::make('Top Referral', $topSourceLabel)
                ->description($topSource ? $topSource->count.' users' : 'No data')
                ->color('primary'),
        ];
    }
}
