<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Enums\OrderStatus;
use App\Filament\Resources\OrderResource\Pages\ListOrders;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Symfony\Component\Intl\Countries;

class RevenueByCountryChart extends ChartWidget
{
    use InteractsWithPageTable;

    protected static ?string $pollingInterval = null;

    protected static ?string $maxHeight = '200px';

    protected static ?string $heading = 'Revenue by Country';

    protected function getTablePage(): string
    {
        return ListOrders::class;
    }

    protected function getData(): array
    {
        $query = $this->getPageTableQuery();

        $revenueByCountry = $query
            ->reorder()
            ->where('status', OrderStatus::COMPLETED)
            ->selectRaw("
                COALESCE(
                    JSON_UNQUOTE(JSON_EXTRACT(payment_data, '$.customer_details.address.country')),
                    JSON_UNQUOTE(JSON_EXTRACT(payment_data, '$.payer.address.country_code'))
                ) as country,
                SUM(amount) as total_revenue
            ")
            ->whereRaw("(
                JSON_EXTRACT(payment_data, '$.customer_details.address.country') IS NOT NULL
                OR JSON_EXTRACT(payment_data, '$.payer.address.country_code') IS NOT NULL
            )")
            ->groupBy('country')
            ->pluck('total_revenue', 'country');

        $countries = $revenueByCountry->keys();
        $labels = $countries->map(fn ($code) => Countries::getName($code))->toArray();

        $colors = $countries->map(fn ($code) => $this->generateColorFromCode($code))->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Revenue',
                    'data' => $revenueByCountry->values(),
                    'backgroundColor' => $colors,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
            'scales' => [
                'x' => [
                    'display' => false,
                ],
                'y' => [
                    'display' => false,
                ],
            ],
            'elements' => [
                'arc' => [
                    'borderWidth' => 0,
                ],
            ],
        ];
    }

    public function getDescription(): ?string
    {
        return 'Geographic distribution of revenue.';
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    private function generateColorFromCode(string $code): string
    {
        // Hash the code to get a consistent number
        $hash = crc32($code);

        // Use the hash to generate an RGB color
        $r = ($hash & 0xFF0000) >> 16; // Extract red
        $g = ($hash & 0x00FF00) >> 8;  // Extract green
        $b = $hash & 0x0000FF;         // Extract blue

        // Convert to a hex color code
        return sprintf('#%02X%02X%02X', $r, $g, $b);
    }
}
