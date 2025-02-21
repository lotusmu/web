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
            'today' => [$now->clone()->startOfDay(), $now->clone()->endOfDay()],
            'yesterday' => [$now->clone()->subDay()->startOfDay(), $now->clone()->subDay()->endOfDay()],
            'this_week' => [$now->clone()->startOfWeek(), $now->clone()->endOfWeek()],
            'last_7_days' => [$now->clone()->subDays(6)->startOfDay(), $now->clone()->endOfDay()],
            'this_month' => [$now->clone()->startOfMonth(), $now->clone()->endOfMonth()],
            'year_to_date' => [$now->clone()->startOfYear(), $now->clone()->endOfDay()],
            'all_time' => [Carbon::parse('2025-01-01'), $now->clone()],
            default => [$now->clone()->subMonth(), $now->clone()],
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
