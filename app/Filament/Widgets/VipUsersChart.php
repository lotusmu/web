<?php

namespace App\Filament\Widgets;

use App\Enums\Game\AccountLevel;
use App\Models\User\Member;
use App\Models\User\User;
use Carbon\Carbon;
use Exception;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Log;

class VipUsersChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'VIP Distribution';

    protected static ?string $pollingInterval = '60s';

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = [
        'default' => 'full',
        'lg' => 4,
    ];

    // Store the VIP percentage to display in the chart description
    protected ?float $vipPercentage = null;

    protected function getData(): array
    {
        // Calculate VIP percentage for all users
        $this->calculateVipPercentage();

        // Get VIP users distribution data (excluding Regular)
        $accountLevelData = $this->getVipDistribution();

        // Prepare colors for each VIP level
        $colors = [
            AccountLevel::Bronze->value => 'rgb(180, 83, 9, 0.5)',    // Bronze color
            AccountLevel::Silver->value => 'rgb(148, 163, 184, 0.5)', // Silver color
            AccountLevel::Gold->value => 'rgb(234, 179, 8, 0.5)',     // Gold color
        ];

        $borderColors = [
            AccountLevel::Bronze->value => 'rgb(180, 83, 9)',    // Bronze color
            AccountLevel::Silver->value => 'rgb(148, 163, 184)', // Silver color
            AccountLevel::Gold->value => 'rgb(234, 179, 8)',     // Gold color
        ];

        // Extract levels and counts
        $labels = $accountLevelData->pluck('level_label')->toArray();
        $counts = $accountLevelData->pluck('count')->toArray();
        $backgroundColors = $accountLevelData->pluck('level')->map(fn ($level) => $colors[$level])->toArray();
        $backgroundBorderColors = $accountLevelData->pluck('level')->map(fn ($level) => $borderColors[$level])->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'VIP Users',
                    'data' => $counts,
                    'backgroundColor' => $backgroundColors,
                    'borderColor' => $backgroundBorderColors,
                    'borderWidth' => '2',
                    'borderRadius' => '10',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function calculateVipPercentage(): void
    {
        try {
            // Get total user count
            $totalUsers = User::count();

            if ($totalUsers === 0) {
                $this->vipPercentage = 0;

                return;
            }

            // Get VIP member count (active VIP subscriptions only)
            $vipCount = Member::on('gamedb_main')
                ->where('AccountLevel', '!=', AccountLevel::Regular)
                ->where('AccountExpireDate', '>=', now())
                ->count();

            // Calculate percentage
            $this->vipPercentage = $totalUsers > 0 ? round(($vipCount / $totalUsers) * 100, 1) : 0;

        } catch (Exception $e) {
            Log::error('Error calculating VIP percentage: '.$e->getMessage());
            $this->vipPercentage = null;
        }
    }

    protected function getVipDistribution()
    {
        try {
            // Get all active VIP members without date filtering
            $vipMembers = Member::on('gamedb_main')
                ->where('AccountLevel', '!=', AccountLevel::Regular)
                ->where('AccountExpireDate', '>=', now())
                ->get(['memb___id', 'AccountLevel']);

            // Count members by account level
            $accountLevelCounts = $vipMembers
                ->groupBy('AccountLevel')
                ->map(function ($group) {
                    return [
                        'level' => $group->first()->AccountLevel->value,
                        'level_label' => $group->first()->AccountLevel->getLabel(),
                        'count' => $group->count(),
                    ];
                })
                ->values();

        } catch (Exception $e) {
            Log::error('Error in VipUsersChart: '.$e->getMessage());

            return $this->getVipLevelsWithZeros();
        }

        // Make sure all VIP levels are represented
        return $this->ensureAllVipLevelsPresent($accountLevelCounts);
    }

    protected function getVipLevelsWithZeros()
    {
        return collect([
            AccountLevel::Bronze,
            AccountLevel::Silver,
            AccountLevel::Gold,
        ])->map(function ($level) {
            return [
                'level' => $level->value,
                'level_label' => $level->getLabel(),
                'count' => 0,
            ];
        });
    }

    protected function ensureAllVipLevelsPresent($accountLevelCounts)
    {
        $allVipLevels = collect([
            AccountLevel::Bronze,
            AccountLevel::Silver,
            AccountLevel::Gold,
        ]);

        $result = $allVipLevels->map(function ($level) use ($accountLevelCounts) {
            $existing = $accountLevelCounts->firstWhere('level', $level->value);

            if ($existing) {
                return $existing;
            }

            return [
                'level' => $level->value,
                'level_label' => $level->getLabel(),
                'count' => 0,
            ];
        });

        return $result;
    }

    protected function parseDate($dateValue): ?Carbon
    {
        if ($dateValue instanceof Carbon) {
            return clone $dateValue;
        }

        return $dateValue ? Carbon::parse($dateValue) : null;
    }

    public function getDescription(): ?string
    {
        if ($this->vipPercentage !== null) {
            return "{$this->vipPercentage}% of users have VIP status • Active members by package";
        }

        return 'Distribution of VIP users by level • Showing active subscriptions';
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
