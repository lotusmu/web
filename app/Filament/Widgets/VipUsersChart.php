<?php

namespace App\Filament\Widgets;

use App\Actions\CalculateDateRange;
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

    // Store the VIP percentage to display in the chart description
    protected ?float $vipPercentage = null;

    protected function getData(): array
    {
        // Get filters with proper defaults
        $period = $this->filters['period'] ?? 'last_7_days';
        $startDate = $this->parseDate($this->filters['startDate'] ?? null);
        $endDate = $this->parseDate($this->filters['endDate'] ?? null);

        // Use the action to calculate date range if needed
        if (! $startDate || ! $endDate) {
            [$startDate, $endDate] = app(CalculateDateRange::class)->handle($period);
        }

        // Calculate VIP percentage
        $this->calculateVipPercentage($startDate, $endDate);

        // Get only VIP users distribution data (excluding Regular)
        $accountLevelData = $this->getVipDistribution($startDate, $endDate);

        // Prepare colors for each VIP level
        $colors = [
            AccountLevel::Bronze->value => 'rgb(180, 83, 9)',    // Bronze color
            AccountLevel::Silver->value => 'rgb(148, 163, 184)', // Silver color
            AccountLevel::Gold->value => 'rgb(234, 179, 8)',     // Gold color
        ];

        // Extract levels and counts
        $labels = $accountLevelData->pluck('level_label')->toArray();
        $counts = $accountLevelData->pluck('count')->toArray();
        $backgroundColors = $accountLevelData->pluck('level')->map(fn ($level) => $colors[$level])->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'VIP Users',
                    'data' => $counts,
                    'backgroundColor' => $backgroundColors,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function calculateVipPercentage($startDate, $endDate): void
    {
        try {
            // Get total user count in date range
            $totalUsers = User::whereBetween('created_at', [$startDate, $endDate])->count();

            if ($totalUsers === 0) {
                $this->vipPercentage = 0;

                return;
            }

            // Get user IDs created within the date range
            $userIds = User::whereBetween('created_at', [$startDate, $endDate])
                ->pluck('name')
                ->toArray();

            // Get VIP member count
            $vipCount = Member::on('gamedb_main')
                ->whereIn('memb___id', $userIds)
                ->where('AccountLevel', '!=', AccountLevel::Regular)
                ->count();

            // Calculate percentage
            $this->vipPercentage = $totalUsers > 0 ? round(($vipCount / $totalUsers) * 100, 1) : 0;

        } catch (Exception $e) {
            Log::error('Error calculating VIP percentage: '.$e->getMessage());
            $this->vipPercentage = null;
        }
    }

    protected function getVipDistribution($startDate, $endDate)
    {
        try {
            // Get user IDs created within the date range
            $userIds = User::whereBetween('created_at', [$startDate, $endDate])
                ->pluck('name')
                ->toArray();

            if (empty($userIds)) {
                // Handle case where no users were created in this period
                return $this->getVipLevelsWithZeros();
            }

            // Get only VIP members from the game database that match these users
            $vipMembers = Member::on('gamedb_main')
                ->whereIn('memb___id', $userIds)
                ->where('AccountLevel', '!=', AccountLevel::Regular)
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
            return "{$this->vipPercentage}% of users have VIP status";
        }

        return 'Distribution of VIP users by level';
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
