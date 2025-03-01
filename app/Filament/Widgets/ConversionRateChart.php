<?php

namespace App\Filament\Widgets;

use App\Actions\CalculateDateRange;
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
        // Get filters with proper defaults
        $period = $this->filters['period'] ?? 'last_7_days';
        $startDate = $this->parseDate($this->filters['startDate'] ?? null);
        $endDate = $this->parseDate($this->filters['endDate'] ?? null);

        // Use the action to calculate date range if needed
        if (! $startDate || ! $endDate) {
            [$startDate, $endDate] = app(CalculateDateRange::class)->handle($period);
        }

        // Calculate conversion stats - but expand the timeframe to look for purchases
        $this->calculateConversionStats($startDate, $endDate);

        // Get time to first purchase distribution data
        $timeToFirstPurchase = $this->getTimeToFirstPurchaseData($startDate, $endDate);

        return [
            'datasets' => [
                [
                    'label' => 'Users',
                    'data' => $timeToFirstPurchase->pluck('count')->toArray(),
                    'backgroundColor' => 'rgba(234, 88, 12, 0.5)', // Orange color
                    'borderColor' => 'rgb(234, 88, 12)',
                    'borderWidth' => '2',
                    'borderRadius' => '10',
                ],
            ],
            'labels' => $timeToFirstPurchase->pluck('period')->toArray(),
        ];
    }

    protected function calculateConversionStats($startDate, $endDate): void
    {
        try {
            // Get total user count registered in the period
            $totalUsersCount = User::whereBetween('created_at', [$startDate, $endDate])->count();

            if ($totalUsersCount === 0) {
                $this->conversionRate = 0;
                $this->avgDaysToFirstPurchase = 0;

                return;
            }

            // Get users who registered in the period and made any purchase (completed order)
            $userIds = User::whereBetween('created_at', [$startDate, $endDate])
                ->pluck('id')
                ->toArray();

            if (empty($userIds)) {
                $this->conversionRate = 0;
                $this->avgDaysToFirstPurchase = 0;

                return;
            }

            // Look for any completed orders for these users (even outside date range)
            $paidUsersData = DB::table('orders')
                ->select('user_id', DB::raw('MIN(created_at) as first_purchase_date'))
                ->whereIn('user_id', $userIds)
                ->where('status', OrderStatus::COMPLETED->value)
                ->groupBy('user_id')
                ->get();

            $paidUsersCount = $paidUsersData->count();

            // Get registration dates for users who made purchases
            $registrationDates = User::whereIn('id', $paidUsersData->pluck('user_id')->toArray())
                ->pluck('created_at', 'id');

            // Calculate conversion rate
            $this->conversionRate = $totalUsersCount > 0 ? round(($paidUsersCount / $totalUsersCount) * 100, 1) : 0;

            // Calculate average days to first purchase
            if ($paidUsersCount > 0) {
                $totalDaysDiff = 0;
                foreach ($paidUsersData as $userData) {
                    $registrationDate = Carbon::parse($registrationDates[$userData->user_id]);
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

    protected function getTimeToFirstPurchaseData($startDate, $endDate)
    {
        try {
            // First get all users who registered within the date range
            $users = User::whereBetween('created_at', [$startDate, $endDate])->get();

            if ($users->isEmpty()) {
                return $this->getEmptyTimeDistribution();
            }

            $userIds = $users->pluck('id')->toArray();
            $registrationDates = $users->pluck('created_at', 'id');

            // Then get completed orders of these users (even outside the date range)
            $orders = Order::whereIn('user_id', $userIds)
                ->where('status', OrderStatus::COMPLETED)
                ->orderBy('created_at')
                ->get()
                ->groupBy('user_id');

            // Initialize buckets for the time ranges
            $distribution = $this->getEmptyTimeDistributionArray();

            // For each user with at least one order
            foreach ($orders as $userId => $userOrders) {
                if ($userOrders->isEmpty()) {
                    continue;
                }

                // Get first order
                $firstOrder = $userOrders->first();
                $regDate = Carbon::parse($registrationDates[$userId]);
                $purchaseDate = Carbon::parse($firstOrder->created_at);

                // Calculate days difference
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
            Log::error('Error getting time to first purchase: '.$e->getMessage().$e->getTraceAsString());

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
