<?php

namespace App\Filament\Pages;

use App\Actions\CalculateDateRange;
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

                            // Set dates without triggering further reactivity
                            $set('startDate', $start, false);
                            $set('endDate', $end, false);
                        }),

                    DatePicker::make('startDate')
                        ->label('From Date')
                        ->hint('Select start date')
                        ->reactive()
                        ->native(false)
                        ->minDate(fn () => Carbon::parse('2023-01-01')) // Minimum date
                        ->afterStateUpdated(function (callable $set, $state, callable $get) {
                            // Only set to custom if the date was changed *by the user*
                            if ($get('period') !== 'custom') {
                                $set('period', 'custom');
                            }

                            // If end date exists and is before start date, adjust end date
                            $endDate = $get('endDate');
                            if ($endDate && Carbon::parse($state)->isAfter($endDate)) {
                                $set('endDate', $state, false);
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
                                $set('startDate', $state, false);
                            }
                        }),
                ]),
        ]);
    }
}
