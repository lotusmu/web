<?php

namespace App\Filament\Widgets;

use App\Actions\CalculateDateRange;
use App\Models\User\User;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class RegisteredUsersChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Registered Users';

    protected static ?string $pollingInterval = '60s';

    protected static ?int $sort = 2; // After RevenueChart, which has sort 1

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

        // Determine appropriate interval based on date range
        $granularity = app(CalculateDateRange::class)->determineDataGranularity($startDate, $endDate);

        // Get trend data based on granularity
        $data = $this->getTrendData($startDate, $endDate, $granularity);

        return [
            'datasets' => [
                [
                    'label' => 'Registered Users',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => 'rgba(139, 92, 246, 0.5)', // Purple color (different from Revenue)
                    'borderColor' => 'rgb(139, 92, 246)',
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $granularity === 'weekly' ? $value->date : $this->formatLabel($value->date, $granularity)
            ),
        ];
    }

    protected function parseDate($dateValue): ?Carbon
    {
        if ($dateValue instanceof Carbon) {
            return clone $dateValue;
        }

        return $dateValue ? Carbon::parse($dateValue) : null;
    }

    protected function getTrendData(Carbon $startDate, Carbon $endDate, string $granularity)
    {
        $query = User::whereBetween('created_at', [$startDate, $endDate]);

        // Handle weekly data separately since it needs custom processing
        if ($granularity === 'weekly') {
            return $this->getWeeklyTrend($startDate, $endDate);
        }

        return match ($granularity) {
            'hourly' => Trend::query($query)
                ->between($startDate, $endDate)
                ->perHour()
                ->count(),
            'daily' => Trend::query($query)
                ->between($startDate, $endDate)
                ->perDay()
                ->count(),
            'monthly' => Trend::query($query)
                ->between($startDate, $endDate)
                ->perMonth()
                ->count(),
            default => collect(),
        };
    }

    protected function getWeeklyTrend(Carbon $startDate, Carbon $endDate)
    {
        $weeklyData = collect();
        $currentWeekStart = clone $startDate;

        while ($currentWeekStart->lte($endDate)) {
            $weekEnd = (clone $currentWeekStart)->addDays(6)->min($endDate);

            // Query users registered in this specific week
            $weeklyCount = User::whereBetween('created_at', [$currentWeekStart, $weekEnd])
                ->count();

            // Create a label for this week
            $weekLabel = $currentWeekStart->format('M d').' - '.$weekEnd->format('M d');

            // Create a weekly data point
            $weeklyData->push(new TrendValue(
                $weekLabel,
                $weeklyCount
            ));

            $currentWeekStart = (clone $weekEnd)->addDay();
        }

        return $weeklyData;
    }

    protected function formatLabel(string $date, string $granularity): string
    {
        $carbonDate = Carbon::parse($date);

        return match ($granularity) {
            'hourly' => $carbonDate->format('H:i'),
            'daily' => $carbonDate->format('M d'),
            'monthly' => $carbonDate->format('M Y'),
            default => $carbonDate->format('M d'),
        };
    }

    protected function getType(): string
    {
        return 'line';
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
