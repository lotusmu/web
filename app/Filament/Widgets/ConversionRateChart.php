<?php

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Models\Payment\Order;
use App\Models\User\User;
use Carbon\Carbon;
use Exception;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Facades\DB;
use Log;

class ConversionRateChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Free-to-Paid Conversion';

    protected static ?string $pollingInterval = '60s';

    protected static ?int $sort = 4;

    protected ?float $conversionRate = null;

    protected ?float $avgDaysToFirstPurchase = null;

    protected function getData(): array
    {
        // Calculate conversion stats for all users regardless of date range
        $this->calculateConversionStats();

        // Get time to first purchase distribution data for all users
        $timeToFirstPurchase = $this->getTimeToFirstPurchaseData();

        // Define colors for the time periods - using a gradient from green to orange
        $backgroundColors = [
            '0-1 day' => 'rgba(16, 185, 129, 0.5)',    // Green
            '2-7 days' => 'rgba(59, 130, 246, 0.5)',   // Blue
            '8-14 days' => 'rgba(99, 102, 241, 0.5)',  // Indigo
            '15-30 days' => 'rgba(139, 92, 246, 0.5)', // Purple
            '31-60 days' => 'rgba(236, 72, 153, 0.5)', // Pink
            '61+ days' => 'rgba(239, 68, 68, 0.5)',     // Red
        ];

        $borderColors = [
            '0-1 day' => 'rgb(16, 185, 129)',    // Green
            '2-7 days' => 'rgb(59, 130, 246)',   // Blue
            '8-14 days' => 'rgb(99, 102, 241)',  // Indigo
            '15-30 days' => 'rgb(139, 92, 246)', // Purple
            '31-60 days' => 'rgb(236, 72, 153)', // Pink
            '61+ days' => 'rgb(239, 68, 68)',     // Red
        ];

        // Map the colors to the data
        $bgColors = $timeToFirstPurchase->pluck('period')
            ->map(fn ($period) => $backgroundColors[$period] ?? 'rgba(107, 114, 128, 0.5)')
            ->toArray();

        $bdColors = $timeToFirstPurchase->pluck('period')
            ->map(fn ($period) => $borderColors[$period] ?? 'rgb(107, 114, 128)')
            ->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Users',
                    'data' => $timeToFirstPurchase->pluck('count')->toArray(),
                    'backgroundColor' => $bgColors,
                    'borderColor' => $bdColors,
                    'borderWidth' => '2',
                    'borderRadius' => '10',
                ],
            ],
            'labels' => $timeToFirstPurchase->pluck('period')->toArray(),
        ];
    }

    protected function calculateConversionStats(): void
    {
        try {
            // Get total user count
            $totalUsersCount = User::count();

            if ($totalUsersCount === 0) {
                $this->conversionRate = 0;
                $this->avgDaysToFirstPurchase = 0;

                return;
            }

            // Get all users who have made a purchase
            $paidUsersData = DB::table('users')
                ->select('users.id', 'users.created_at as registration_date', DB::raw('MIN(orders.created_at) as first_purchase_date'))
                ->join('orders', 'users.id', '=', 'orders.user_id')
                ->where('orders.status', OrderStatus::COMPLETED->value)
                ->groupBy('users.id', 'users.created_at')
                ->get();

            $paidUsersCount = $paidUsersData->count();

            // Calculate conversion rate
            $this->conversionRate = $totalUsersCount > 0 ? round(($paidUsersCount / $totalUsersCount) * 100, 1) : 0;

            // Calculate average days to first purchase
            if ($paidUsersCount > 0) {
                $totalDaysDiff = 0;
                foreach ($paidUsersData as $userData) {
                    $registrationDate = Carbon::parse($userData->registration_date);
                    $firstPurchaseDate = Carbon::parse($userData->first_purchase_date);
                    $totalDaysDiff += $registrationDate->diffInDays($firstPurchaseDate);
                }
                $this->avgDaysToFirstPurchase = round($totalDaysDiff / $paidUsersCount, 1);
            } else {
                $this->avgDaysToFirstPurchase = 0;
            }
        } catch (Exception $e) {
            Log::error('Error calculating conversion stats: '.$e->getMessage());
            $this->conversionRate = null;
            $this->avgDaysToFirstPurchase = null;
        }
    }

    protected function getTimeToFirstPurchaseData()
    {
        try {
            // Get all users and their registration dates
            $registrationDates = User::pluck('created_at', 'id');

            // Get first purchase date for each user
            $firstPurchases = Order::select('user_id', DB::raw('MIN(created_at) as first_purchase_date'))
                ->where('status', OrderStatus::COMPLETED)
                ->groupBy('user_id')
                ->get();

            // Initialize distribution buckets
            $distribution = $this->getEmptyTimeDistributionArray();

            // For each user with a purchase
            foreach ($firstPurchases as $purchase) {
                if (! isset($registrationDates[$purchase->user_id])) {
                    continue;
                }

                $regDate = Carbon::parse($registrationDates[$purchase->user_id]);
                $purchaseDate = Carbon::parse($purchase->first_purchase_date);
                $daysDiff = $regDate->diffInDays($purchaseDate);

                // Add to appropriate bucket
                if ($daysDiff <= 1) {
                    $distribution['0-1 day']++;
                } elseif ($daysDiff <= 7) {
                    $distribution['2-7 days']++;
                } elseif ($daysDiff <= 14) {
                    $distribution['8-14 days']++;
                } elseif ($daysDiff <= 30) {
                    $distribution['15-30 days']++;
                } elseif ($daysDiff <= 60) {
                    $distribution['31-60 days']++;
                } else {
                    $distribution['61+ days']++;
                }
            }

            return collect($distribution)->map(function ($count, $period) {
                return [
                    'period' => $period,
                    'count' => $count,
                ];
            })->values();

        } catch (Exception $e) {
            Log::error('Error getting time to first purchase: '.$e->getMessage());

            return $this->getEmptyTimeDistribution();
        }
    }

    protected function getEmptyTimeDistributionArray()
    {
        return [
            '0-1 day' => 0,
            '2-7 days' => 0,
            '8-14 days' => 0,
            '15-30 days' => 0,
            '31-60 days' => 0,
            '61+ days' => 0,
        ];
    }

    protected function getEmptyTimeDistribution()
    {
        return collect($this->getEmptyTimeDistributionArray())->map(function ($count, $period) {
            return [
                'period' => $period,
                'count' => $count,
            ];
        })->values();
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
        $conversionText = $this->conversionRate !== null ?
            "{$this->conversionRate}% of users made a purchase" :
            'Conversion rate from free to paid players';

        $avgDaysText = $this->avgDaysToFirstPurchase !== null ?
            " • Avg. {$this->avgDaysToFirstPurchase} days to first purchase" :
            '';

        return $conversionText.$avgDaysText;
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
