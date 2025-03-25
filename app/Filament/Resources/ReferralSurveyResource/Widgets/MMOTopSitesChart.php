<?php

namespace App\Filament\Resources\ReferralSurveyResource\Widgets;

use App\Enums\Survey\MMOTopSite;
use App\Models\User\ReferralSurvey;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class MMOTopSitesChart extends ChartWidget
{
    protected static ?string $heading = 'MMO Top Sites Distribution';

    protected static ?string $maxHeight = '250px';

    protected int|string|array $columnSpan = '50%';

    protected function getData(): array
    {
        $query = ReferralSurvey::query()
            ->where('completed', true)
            ->where('referral_source', 'mmo_top_site')
            ->whereNotNull('mmo_top_site');

        $sites = $query->select(DB::raw('mmo_top_site as site_value'), DB::raw('count(*) as count'))
            ->groupBy('mmo_top_site')
            ->orderBy('count', 'desc')
            ->get();

        $labels = $sites->map(function ($site) {
            return MMOTopSite::from($site->site_value)->shortLabel();
        })->toArray();

        $counts = $sites->pluck('count')->toArray();

        // Using the color methods from MMOTopSite enum to generate colors
        // We'll need to add chart color methods to the MMOTopSite enum
        $backgroundColors = $sites->map(function ($site) {
            return $this->getBackgroundColorForSite(MMOTopSite::from($site->site_value));
        })->toArray();

        $borderColors = $sites->map(function ($site) {
            return $this->getBorderColorForSite(MMOTopSite::from($site->site_value));
        })->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'MMO Top Sites',
                    'data' => $counts,
                    'backgroundColor' => $backgroundColors,
                    'borderColor' => $borderColors,
                    'borderWidth' => '2',
                    'borderRadius' => '10',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getBackgroundColorForSite(MMOTopSite $site): string
    {
        // Convert Filament color to rgba with transparency
        return match ($site) {
            MMOTopSite::MMOAnons => 'rgba(16, 185, 129, 0.5)', // Emerald
            MMOTopSite::MMOTopRu => 'rgba(14, 165, 233, 0.5)', // Sky
            MMOTopSite::ServeraMU => 'rgba(245, 158, 11, 0.5)', // Amber
            MMOTopSite::Other => 'rgba(158, 158, 158, 0.5)', // Gray
        };
    }

    protected function getBorderColorForSite(MMOTopSite $site): string
    {
        // Convert Filament color to rgb for border
        return match ($site) {
            MMOTopSite::MMOAnons => 'rgb(16, 185, 129)', // Emerald
            MMOTopSite::MMOTopRu => 'rgb(14, 165, 233)', // Sky
            MMOTopSite::ServeraMU => 'rgb(245, 158, 11)', // Amber
            MMOTopSite::Other => 'rgb(158, 158, 158)', // Gray
        };
    }

    public function getDescription(): ?string
    {
        return 'Distribution by MMO site';
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
                'tooltip' => [
                    'enabled' => true,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'precision' => 0, // Only show whole numbers
                    ],
                ],
            ],
        ];
    }
}
