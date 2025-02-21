<?php

namespace App\Filament\Widgets;

use App\Actions\CalculateDateRange;
use App\Enums\OrderStatus;
use App\Models\Game\Status;
use App\Models\Game\Wallet;
use App\Models\Payment\Order;
use App\Models\User\Member;
use App\Models\User\User;
use Carbon\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class StatsOverview extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 0;

    // Inside the getStats() method of StatsOverview class
    public function getStats(): array
    {
        // Get filters with proper defaults
        $period = $this->filters['period'] ?? 'this_month';
        $startDate = $this->parseDate($this->filters['startDate'] ?? null);
        $endDate = $this->parseDate($this->filters['endDate'] ?? null);

        // Use the action to calculate date range if needed
        if (! $startDate || ! $endDate) {
            [$startDate, $endDate] = app(CalculateDateRange::class)->handle($period);
        }

        // Calculate revenue for the selected period
        $revenue = Order::where('status', OrderStatus::COMPLETED)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');

        // Format the revenue with currency
        $formattedRevenue = '€ '.number_format($revenue, 2);

        // Count registered users for the selected period
        $registeredUsers = User::query()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        // Calculate percentage increase from previous period
        $previousPeriodStart = (clone $startDate)->subDays($startDate->diffInDays($endDate));
        $previousPeriodEnd = (clone $startDate)->subDay();

        $previousPeriodUsers = User::query()
            ->whereBetween('created_at', [$previousPeriodStart, $previousPeriodEnd])
            ->count();

        $userIncrease = $previousPeriodUsers > 0
            ? round((($registeredUsers - $previousPeriodUsers) / $previousPeriodUsers) * 100, 1)
            : 0;

        $userIncreaseDescription = $userIncrease >= 0
            ? "+{$userIncrease}% from previous period"
            : "{$userIncrease}% from previous period";

        $totalOnline = Status::where('ConnectStat', true)->count();

        // Calculate total resources (Tokens, Credits, Zen)
        $totalTokens = Member::sum('tokens');
        $totalCredits = Wallet::sum('WCoinC');
        $totalZen = Wallet::sum('zen');

        // Format numbers for display
        $formattedTokens = Number::format($totalTokens);
        $formattedCredits = Number::format($totalCredits);
        $formattedZen = Number::abbreviate($totalZen, precision: 2);

        return [
            Stat::make('Revenue', $formattedRevenue)
                ->description('Total revenue for selected period')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->icon('heroicon-o-banknotes')
                ->color('success'),

            Stat::make('Registered Users', $registeredUsers)
                ->description($userIncreaseDescription)
                ->descriptionIcon($userIncrease >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->icon('heroicon-o-user-plus')
                ->color($userIncrease >= 0 ? 'success' : 'danger')
                ->chart($this->generateUserRegistrationChart($startDate, $endDate)),

            Stat::make('Total Online', $totalOnline)
                ->description('Currently active users')
                ->color('info')
                ->icon('heroicon-s-signal'),

            Stat::make('Total Resources', '')
                ->description("Tokens: {$formattedTokens} | Credits: {$formattedCredits} | Zen: {$formattedZen}")
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ])
                ->icon('heroicon-o-circle-stack'),
        ];
    }

    protected function generateUserRegistrationChart($startDate, $endDate): array
    {
        $diffInDays = $startDate->diffInDays($endDate);

        if ($diffInDays > 30) {
            // If range is large, group by week
            $data = [];
            $currentDate = clone $startDate;

            while ($currentDate->lte($endDate)) {
                $weekEnd = (clone $currentDate)->addDays(6)->min($endDate);

                $count = User::query()
                    ->whereBetween('created_at', [$currentDate, $weekEnd])
                    ->count();

                $data[] = $count;
                $currentDate = (clone $weekEnd)->addDay();
            }

            return $data;
        } else {
            // If range is smaller, show daily data
            $data = [];
            $currentDate = clone $startDate;

            while ($currentDate->lte($endDate)) {
                $count = User::query()
                    ->whereDate('created_at', $currentDate)
                    ->count();

                $data[] = $count;
                $currentDate->addDay();
            }

            return $data;
        }
    }

    protected function parseDate($dateValue)
    {
        // Reuse the same method from RevenueChart
        if ($dateValue instanceof Carbon) {
            return clone $dateValue;
        }

        return $dateValue ? Carbon::parse($dateValue) : null;
    }
}
