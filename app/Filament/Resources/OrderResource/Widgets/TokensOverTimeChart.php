<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Actions\CalculateDateRange;
use App\Enums\OrderStatus;
use App\Filament\Resources\OrderResource\Pages\ListOrders;
use App\Models\Payment\Order;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Illuminate\Support\Collection;

class TokensOverTimeChart extends ChartWidget
{
    use InteractsWithPageTable;

    protected static ?string $pollingInterval = null;

    protected static ?string $heading = 'Tokens Over Time';

    protected int|string|array $columnSpan = [
        'default' => 'full',
        'lg' => 2,
    ];

    protected function getTablePage(): string
    {
        return ListOrders::class;
    }

    protected function getData(): array
    {
        $query = $this->getPageTableQuery();

        $startDate = Carbon::parse($query->min('orders.created_at') ?? now()->subMonth());
        $endDate = Carbon::parse($query->max('orders.created_at') ?? now());

        $granularity = app(CalculateDateRange::class)->determineDataGranularity($startDate, $endDate);

        $dataPoints = $this->getDataPoints($startDate, $endDate, $granularity);

        return [
            'datasets' => [
                [
                    'label' => 'Purchased Tokens',
                    'data' => $dataPoints->pluck('purchased')->toArray(),
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'fill' => true,
                    'tension' => 0.3,
                ],
            ],
            'labels' => $dataPoints->pluck('label')->toArray(),
        ];
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'precision' => 0,
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
                'tooltip' => [
                    'mode' => 'index',
                    'intersect' => false,
                ],
            ],
        ];
    }

    public function getDescription(): ?string
    {
        return 'Total purchased tokens.';
    }

    protected function getType(): string
    {
        return 'line';
    }

    private function getDataPoints(Carbon $startDate, Carbon $endDate, string $granularity): Collection
    {
        $dataPoints = collect();
        $totalPurchased = 0;

        switch ($granularity) {
            case 'hourly':
                $current = clone $startDate;
                while ($current <= $endDate) {
                    $nextHour = (clone $current)->addHour();

                    $purchased = $this->getPurchasedTokens($current, $nextHour);
                    $totalPurchased += $purchased;

                    $dataPoints->push([
                        'label' => $current->format('H:i'),
                        'purchased' => $totalPurchased,
                    ]);

                    $current = $nextHour;
                }
                break;

            case 'daily':
                $current = clone $startDate;
                while ($current <= $endDate) {
                    $nextDay = (clone $current)->addDay();

                    $purchased = $this->getPurchasedTokens($current, $nextDay);
                    $totalPurchased += $purchased;

                    $dataPoints->push([
                        'label' => $current->format('M d'),
                        'purchased' => $totalPurchased,
                    ]);

                    $current = $nextDay;
                }
                break;

            case 'weekly':
                $current = clone $startDate;
                while ($current <= $endDate) {
                    $nextWeek = (clone $current)->addWeek();
                    if ($nextWeek > $endDate) {
                        $nextWeek = clone $endDate;
                    }

                    $purchased = $this->getPurchasedTokens($current, $nextWeek);
                    $totalPurchased += $purchased;

                    $dataPoints->push([
                        'label' => $current->format('M d').' - '.$nextWeek->format('M d'),
                        'purchased' => $totalPurchased,
                    ]);

                    $current = (clone $nextWeek)->addDay()->startOfDay();
                    if ($current > $endDate) {
                        break;
                    }
                }
                break;

            case 'monthly':
            default:
                $current = clone $startDate->startOfMonth();
                while ($current <= $endDate) {
                    $nextMonth = (clone $current)->addMonth();

                    $purchased = $this->getPurchasedTokens($current, $nextMonth);
                    $totalPurchased += $purchased;

                    $dataPoints->push([
                        'label' => $current->format('M Y'),
                        'purchased' => $totalPurchased,
                    ]);

                    $current = $nextMonth;
                }
                break;
        }

        return $dataPoints;
    }

    private function getPurchasedTokens(Carbon $start, Carbon $end): int
    {
        return Order::where('status', OrderStatus::COMPLETED)
            ->whereBetween('orders.created_at', [$start, $end])
            ->join('token_packages', 'orders.token_package_id', '=', 'token_packages.id')
            ->sum('token_packages.tokens_amount');
    }
}
