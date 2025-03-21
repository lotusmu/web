<?php

namespace App\Actions;

use Carbon\Carbon;

class CalculateDateRange
{
    public function handle(string $period, ?Carbon $customStartDate = null, ?Carbon $customEndDate = null): array
    {
        // If custom dates are provided, use them
        if ($period === 'custom' && $customStartDate && $customEndDate) {
            return [$customStartDate, $customEndDate];
        }

        $now = now();

        return match ($period) {
            'today' => [$now->copy()->startOfDay(), $now->copy()->endOfDay()],
            'yesterday' => [$now->copy()->subDay()->startOfDay(), $now->copy()->subDay()->endOfDay()],
            'last_7_days' => [$now->copy()->subDays(6)->startOfDay(), $now->copy()->endOfDay()],
            'last_4_weeks' => [$now->copy()->subWeeks(4)->startOfDay(), $now->copy()->endOfDay()],
            'last_3_months' => [$now->copy()->subMonths(3)->startOfDay(), $now->copy()->endOfDay()],
            'last_12_months' => [$now->copy()->subMonths(12)->startOfDay(), $now->copy()->endOfDay()],
            'month_to_date' => [$now->copy()->startOfMonth(), $now->copy()->endOfDay()],
            'quarter_to_date' => [$now->copy()->startOfQuarter(), $now->copy()->endOfDay()],
            'year_to_date' => [$now->copy()->startOfYear(), $now->copy()->endOfDay()],
            'all_time' => [Carbon::parse('2025-01-01'), $now->copy()->endOfDay()],
            default => [$now->copy()->subMonth(), $now->copy()],
        };
    }

    public function determineDataGranularity(Carbon $startDate, Carbon $endDate): string
    {
        $diffInHours = $startDate->diffInHours($endDate);
        $diffInDays = $startDate->diffInDays($endDate);

        if ($diffInHours <= 48) {
            return 'hourly';
        } elseif ($diffInDays <= 31) {
            return 'daily';
        } elseif ($diffInDays <= 90) {
            return 'weekly';
        } else {
            return 'monthly';
        }
    }
}
