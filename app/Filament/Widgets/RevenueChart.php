<?php

namespace App\Filament\Widgets;

use App\Actions\CalculateDateRange;
use App\Models\Payment\Order;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class RevenueChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Revenue';

    protected static ?int $sort = 1;

    protected function getData(): array
    {
        // Get filters with proper defaults - but DON'T modify them
        $period = $this->filters['period'] ?? 'this_month';

        // Get start and end dates as local variables, not modifying filters
        $endDateValue = $this->filters['endDate'] ?? null;
        $startDateValue = $this->filters['startDate'] ?? null;

        // Convert to Carbon instances without modifying the original filters
        $startDate = $startDateValue instanceof Carbon
            ? clone $startDateValue
            : ($startDateValue ? Carbon::parse($startDateValue) : null);

        $endDate = $endDateValue instanceof Carbon
            ? clone $endDateValue
            : ($endDateValue ? Carbon::parse($endDateValue) : null);

        // Use the action to calculate date range
        if (! $startDate || ! $endDate) {
            [$startDate, $endDate] = app(CalculateDateRange::class)->handle($period);
        }

        // Create new Carbon instances for comparison to avoid modifying the originals
        if ((clone $startDate)->gt(clone $endDate)) {
            [$startDate, $endDate] = [$endDate, $startDate];
        }

        // Determine appropriate granularity based on date range
        $granularity = app(CalculateDateRange::class)->determineDataGranularity($startDate, $endDate);

        // Get appropriate data based on granularity
        $data = match ($granularity) {
            'hourly' => $this->getHourlyData($startDate, $endDate),
            'daily' => $this->getDailyData($startDate, $endDate),
            'weekly' => $this->getWeeklyData($startDate, $endDate),
            'monthly' => $this->getMonthlyData($startDate, $endDate),
        };

        return [
            'datasets' => [
                [
                    'label' => 'Revenue',
                    'data' => $data['values'],
                    'backgroundColor' => 'rgba(59, 130, 246, 0.5)',
                    'borderColor' => 'rgb(59, 130, 246)',
                ],
            ],
            'labels' => $data['labels'],
        ];
    }

    protected function getHourlyData($startDate, $endDate): array
    {
        $labels = [];
        $values = [];

        for ($date = clone $startDate; $date->lte($endDate); $date->addHour()) {
            $labels[] = $date->format('H:i');

            $value = Order::whereBetween('created_at', [$date, (clone $date)->addHour()->subSecond()])
                ->where('status', 'completed')
                ->sum('amount');

            $values[] = $value;
        }

        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }

    protected function getDailyData($startDate, $endDate): array
    {
        $labels = [];
        $values = [];

        for ($date = clone $startDate; $date->lte($endDate); $date->addDay()) {
            $labels[] = $date->format('M d');

            $value = Order::whereDate('created_at', $date)
                ->where('status', 'completed')
                ->sum('amount');

            $values[] = $value;
        }

        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }

    protected function getWeeklyData($startDate, $endDate): array
    {
        $labels = [];
        $values = [];

        $currentDate = clone $startDate;
        while ($currentDate->lte($endDate)) {
            $weekEnd = (clone $currentDate)->addDays(6)->min($endDate);

            $labels[] = $currentDate->format('M d').' - '.$weekEnd->format('M d');

            $value = Order::whereBetween('created_at', [$currentDate, $weekEnd->endOfDay()])
                ->where('status', 'completed')
                ->sum('amount');

            $values[] = $value;

            $currentDate = (clone $weekEnd)->addDay();
        }

        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }

    protected function getMonthlyData($startDate, $endDate): array
    {
        $labels = [];
        $values = [];

        $currentDate = (clone $startDate)->startOfMonth();
        $endMonth = (clone $endDate)->endOfMonth();

        while ($currentDate->lte($endMonth)) {
            $monthEnd = (clone $currentDate)->endOfMonth();

            $labels[] = $currentDate->format('M Y');

            $value = Order::whereBetween('created_at', [$currentDate, $monthEnd])
                ->where('status', 'completed')
                ->sum('amount');

            $values[] = $value;

            $currentDate = (clone $monthEnd)->addDay();
        }

        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getDateRangeForPeriod($period): array
    {
        $now = now();

        return match ($period) {
            'today' => [$now->startOfDay(), $now->clone()->endOfDay()],
            'yesterday' => [$now->subDay()->startOfDay(), $now->clone()->endOfDay()],
            'this_week' => [$now->startOfWeek(), $now->clone()->endOfWeek()],
            'last_7_days' => [$now->subDays(6)->startOfDay(), $now->clone()->endOfDay()],
            'this_month' => [$now->startOfMonth(), $now->clone()->endOfMonth()],
            'year_to_date' => [$now->startOfYear(), $now->clone()->endOfDay()],
            'all_time' => [Carbon::parse('2025-01-01'), $now],
            default => [$now->subMonth(), $now],
        };
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
        ];
    }
}
