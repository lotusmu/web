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
use Illuminate\Support\HtmlString;
use Illuminate\Support\Number;

class StatsOverview extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?string $pollingInterval = '60s';

    protected static ?int $sort = 0;

    public function getStats(): array
    {
        [$startDate, $endDate] = $this->resolveDateRange();

        return [
            $this->buildRevenueStat($startDate, $endDate),
            $this->buildUserStat($startDate, $endDate),
            $this->buildOnlineStat(),
            $this->buildResourceStat(),
        ];
    }

    protected function resolveDateRange(): array
    {
        $period = $this->filters['period'] ?? 'last_7_days';
        $startDate = $this->parseDate($this->filters['startDate'] ?? null);
        $endDate = $this->parseDate($this->filters['endDate'] ?? null);

        if (! $startDate || ! $endDate) {
            return app(CalculateDateRange::class)->handle($period);
        }

        return [$startDate, $endDate];
    }

    protected function buildRevenueStat(Carbon $start, Carbon $end): Stat
    {
        $revenue = Order::where('status', OrderStatus::COMPLETED)
            ->whereBetween('created_at', [$start, $end])
            ->sum('amount');

        return Stat::make('Revenue', '€ '.number_format($revenue, 2))
            ->description('Total revenue for selected period')
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->icon('heroicon-o-banknotes')
            ->color('success');
    }

    protected function buildUserStat(Carbon $start, Carbon $end): Stat
    {
        $currentUsers = User::whereBetween('created_at', [$start, $end])
            ->count();
        [$previousUsers, $increase] = $this->calculateUserIncrease($start, $end, $currentUsers);

        return Stat::make('Registered Users', $currentUsers)
            ->description($this->formatIncrease($increase))
            ->descriptionIcon($this->getTrendIcon($increase))
            ->icon('heroicon-o-user-plus')
            ->color($this->getTrendColor($increase))
            ->chart($this->generateUserRegistrationChart($start, $end));
    }

    protected function calculateUserIncrease(Carbon $start, Carbon $end, int $current): array
    {
        $previousStart = (clone $start)->subDays($start->diffInDays($end));
        $previousEnd = (clone $start)->subDay();

        $previousUsers = User::whereBetween('created_at', [$previousStart, $previousEnd])->count();
        $increase = $previousUsers > 0 ? round((($current - $previousUsers) / $previousUsers) * 100, 1) : 0;

        return [$previousUsers, $increase];
    }

    protected function buildOnlineStat(): Stat
    {
        return Stat::make('Total Online', Status::where('ConnectStat', true)->count())
            ->description('Currently active users')
            ->color('info')
            ->icon('heroicon-o-signal');
    }

    protected function buildResourceStat(): Stat
    {
        $tokens = Number::format(Member::sum('tokens'));
        $credits = Number::format(Wallet::sum('WCoinC'));
        $zen = Number::abbreviate(Wallet::sum('zen'), precision: 2);

        return Stat::make('Total Resources', '')
            ->icon('heroicon-o-circle-stack')
            ->description(new HtmlString($this->resourceHtml($tokens, $credits, $zen)));
    }

    protected function resourceHtml(string $tokens, string $credits, string $zen): string
    {
        return <<<HTML
        <div class="flex items-start gap-4">
            <div><div class="text-xs">Tokens</div><div class="text-base font-semibold">$tokens</div></div>
            <div><div class="text-xs">Credits</div><div class="text-base font-semibold">$credits</div></div>
            <div><div class="text-xs">Zen</div><div class="text-base font-semibold">$zen</div></div>
        </div>
        HTML;
    }

    protected function generateUserRegistrationChart(Carbon $start, Carbon $end): array
    {
        return $start->diffInDays($end) > 30
            ? $this->groupByWeek($start, $end)
            : $this->groupByDay($start, $end);
    }

    protected function groupByWeek(Carbon $start, Carbon $end): array
    {
        $data = [];
        $current = clone $start;

        while ($current->lte($end)) {
            $weekEnd = (clone $current)->addDays(6)->min($end);
            $data[] = User::whereBetween('created_at', [$current, $weekEnd])->count();
            $current = (clone $weekEnd)->addDay();
        }

        return $data;
    }

    protected function groupByDay(Carbon $start, Carbon $end): array
    {
        $data = [];
        $current = clone $start;

        while ($current->lte($end)) {
            $data[] = User::whereDate('created_at', $current)->count();
            $current->addDay();
        }

        return $data;
    }

    protected function formatIncrease(float $increase): string
    {
        return sprintf('%s%.1f%% from previous period', $increase >= 0 ? '+' : '', $increase);
    }

    protected function getTrendIcon(float $increase): string
    {
        return $increase >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';
    }

    protected function getTrendColor(float $increase): string
    {
        return $increase >= 0 ? 'success' : 'danger';
    }

    protected function parseDate($date): ?Carbon
    {
        return $date instanceof Carbon ? clone $date : ($date ? Carbon::parse($date) : null);
    }
}
