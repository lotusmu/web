<?php

namespace App\Filament\Pages;

use App\Actions\CalculateDateRange;
use App\Models\User\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Pages\Dashboard as DashboardPage;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Illuminate\Support\Carbon;

class Dashboard extends DashboardPage
{
    use HasFiltersForm;

    public function filtersForm(Form $form): Form
    {
        return $form->schema([
            Section::make('Dashboard Filters')
                ->description('Select a time period to filter dashboard data')
                ->icon('heroicon-o-calendar')
                ->columns(3)
                ->schema([
                    Select::make('period')
                        ->label('Quick Select')
                        ->options([
                            'today' => 'Today',
                            'yesterday' => 'Yesterday',
                            'this_week' => 'This Week',
                            'last_7_days' => 'Last 7 Days',
                            'this_month' => 'This Month',
                            'year_to_date' => 'Year to Date',
                            'all_time' => 'All Time',
                            'custom' => 'Custom',
                        ])
                        ->default('this_month')
                        ->native(false)
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set) {
                            if ($state === 'custom') {
                                return;
                            }

                            // Set date range based on selected period using the Action
                            [$start, $end] = app(CalculateDateRange::class)->handle($state);
                            $set('startDate', $start);
                            $set('endDate', $end);
                        }),

                    DatePicker::make('startDate')
                        ->label('From Date')
                        ->hint('Select start date')
                        ->reactive()
                        ->native(false)
                        ->afterStateUpdated(function (callable $set, $state, callable $get) {
                            $set('period', 'custom');

                            // If end date exists and is before start date, adjust end date
                            $endDate = $get('endDate');
                            if ($endDate && Carbon::parse($state)->isAfter($endDate)) {
                                $set('endDate', $state);
                            }
                        }),
                    DatePicker::make('endDate')
                        ->label('To Date')
                        ->hint('Select end date')
                        ->reactive()
                        ->native(false)
                        ->afterStateUpdated(function (callable $set, $state, callable $get) {
                            $set('period', 'custom');

                            // If start date exists and is after end date, adjust start date
                            $startDate = $get('startDate');
                            if ($startDate && Carbon::parse($state)->isBefore($startDate)) {
                                $set('startDate', $state);
                            }
                        }),
                ]),
        ]);
    }

    protected function getDateRangeForPeriod($period): array
    {
        $now = now();

        $firstUserDate = User::orderBy('created_at')->first()?->created_at;
        $earliestDate = $firstUserDate ? $firstUserDate->startOfDay() : Carbon::parse('2025-01-01');

        return match ($period) {
            'today' => [$now->clone()->startOfDay(), $now->clone()->endOfDay()],
            'yesterday' => [$now->clone()->subDay()->startOfDay(), $now->clone()->subDay()->endOfDay()],
            'this_week' => [$now->clone()->startOfWeek(), $now->clone()->endOfWeek()],
            'last_7_days' => [$now->clone()->subDays(6)->startOfDay(), $now->clone()->endOfDay()],
            'this_month' => [$now->clone()->startOfMonth(), $now->clone()->endOfMonth()],
            'year_to_date' => [$now->clone()->startOfYear(), $now->clone()->endOfDay()],
            'all_time' => [$earliestDate, $now->clone()],
            default => [$now->clone()->subMonth(), $now->clone()],
        };
    }
}
