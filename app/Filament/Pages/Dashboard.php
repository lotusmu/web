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

    public function getColumns(): int|string|array
    {
        return [
            'default' => 1,
            'md' => 12,
        ];
    }

    public function filtersForm(Form $form): Form
    {
        return $form->schema([
            Section::make('Dashboard Filters')
                ->icon('heroicon-o-calendar')
                ->columns(3)
                ->schema([
                    Select::make('period')
                        ->label('Quick Select')
                        ->options([
                            'today' => 'Today',
                            'yesterday' => 'Yesterday',
                            'last_7_days' => 'Last 7 days',
                            'last_4_weeks' => 'Last 4 weeks',
                            'last_3_months' => 'Last 3 months',
                            'last_12_months' => 'Last 12 months',
                            'month_to_date' => 'Month to date',
                            'quarter_to_date' => 'Quarter to date',
                            'year_to_date' => 'Year to date',
                            'all_time' => 'All time',
                            'custom' => 'Custom',
                        ])
                        ->default('last_7_days')
                        ->native(false)
                        ->live(onBlur: true)
                        ->afterStateUpdated(function ($state, callable $set) {
                            if ($state === 'custom') {
                                return;
                            }

                            // Set date range based on selected period using the Action
                            [$start, $end] = app(CalculateDateRange::class)->handle($state);

                            // Set dates without triggering Livewire updates
                            $set('startDate', $start->format('Y-m-d'));
                            $set('endDate', $end->format('Y-m-d'));
                        }),

                    DatePicker::make('startDate')
                        ->label('From Date')
                        ->hint('Select start date')
                        ->native(false)
                        ->minDate(fn () => Carbon::parse('2025-01-01')), // Minimum date

                    DatePicker::make('endDate')
                        ->label('To Date')
                        ->hint('Select end date')
                        ->native(false),
                ]),
        ]);
    }
}
