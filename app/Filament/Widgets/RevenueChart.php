<?php

namespace App\Filament\Widgets;

use App\Actions\CalculateDateRange;
use App\Models\Payment\Order;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class RevenueChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Revenue';

    protected static ?int $sort = 1;

    protected function getData(): array
    {
        // Get filters with proper defaults
        $period = $this->filters['period'] ?? 'this_month';

        // Get start and end dates
        $endDateValue = $this->filters['endDate'] ?? null;
        $startDateValue = $this->filters['startDate'] ?? null;

        // Convert to Carbon instances
        $startDate = $startDateValue instanceof Carbon
            ? clone $startDateValue
            : ($startDateValue ? Carbon::parse($startDateValue) : null);

        $endDate = $endDateValue instanceof Carbon
            ? clone $endDateValue
            : ($endDateValue ? Carbon::parse($endDateValue) : null);

        // Use the action to calculate date range if needed
        if (! $startDate || ! $endDate) {
            [$startDate, $endDate] = app(CalculateDateRange::class)->handle($period);
        }

        // Determine appropriate interval based on date range
        $granularity = app(CalculateDateRange::class)->determineDataGranularity($startDate, $endDate);

        // Generate trend data based on granularity
        if ($granularity === 'weekly') {
            // Create custom weekly data points directly from the database
            $weeklyData = collect();
            $currentWeekStart = clone $startDate;

            while ($currentWeekStart->lte($endDate)) {
                $weekEnd = (clone $currentWeekStart)->addDays(6)->min($endDate);

                // Query orders for this specific week
                $weeklySum = Order::where('status', 'completed')
                    ->whereBetween('created_at', [$currentWeekStart, $weekEnd])
                    ->sum('amount');

                // Create a label for this week
                $weekLabel = $currentWeekStart->format('M d').' - '.$weekEnd->format('M d');

                // Create a weekly data point
                $weeklyData->push(new TrendValue(
                    $weekLabel, // Use the formatted label directly as the date
                    $weeklySum
                ));

                $currentWeekStart = (clone $weekEnd)->addDay();
            }

            $data = $weeklyData;
        } else {
            // For other granularities, use the Trend package
            $query = Order::where('status', 'completed')
                ->whereBetween('created_at', [$startDate, $endDate]);

            $data = match ($granularity) {
                'hourly' => Trend::query($query)
                    ->between($startDate, $endDate)
                    ->perHour()
                    ->sum('amount'),
                'daily' => Trend::query($query)
                    ->between($startDate, $endDate)
                    ->perDay()
                    ->sum('amount'),
                'monthly' => Trend::query($query)
                    ->between($startDate, $endDate)
                    ->perMonth()
                    ->sum('amount'),
                default => collect(), // Shouldn't reach here
            };
        }

        return [
            'datasets' => [
                [
                    'label' => 'Revenue',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => 'rgba(59, 130, 246, 0.5)',
                    'borderColor' => 'rgb(59, 130, 246)',
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) =>
                // For weekly, we've already formatted the label in the date field
            $granularity === 'weekly' ? $value->date : $this->formatLabel($value->date, $granularity)
            ),
        ];
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
