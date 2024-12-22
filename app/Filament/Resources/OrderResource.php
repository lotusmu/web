<?php

namespace App\Filament\Resources;

use App\Enums\PaymentProvider;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers\StatusHistoryRelationManager;
use App\Filament\Resources\OrderResource\Widgets\OrderStatsWidget;
use App\Filament\Resources\OrderResource\Widgets\OrderStatusDistributionChart;
use App\Filament\Resources\OrderResource\Widgets\RevenueByCountryChart;
use App\Filament\Resources\OrderResource\Widgets\RevenueByProviderChart;
use App\Models\Payment\Order;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Support\Enums\IconPosition;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\Intl\Countries;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationGroup = 'Payments';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Username')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->iconPosition(IconPosition::After)
                    ->url(fn ($record) => route('filament.admin.resources.members.edit', ['record' => $record->user->name]))
                    ->searchable(),
                TextColumn::make('amount')
                    ->money('eur')
                    ->sortable(),
                TextColumn::make('payment_provider')
                    ->badge(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->persistFiltersInSession()
            ->filtersTriggerAction(function ($action) {
                return $action->button()->label('Filters');
            })
            ->filters([
                Tables\Filters\SelectFilter::make('payment_provider')
                    ->multiple()
                    ->options(PaymentProvider::class),
                Tables\Filters\Filter::make('date_preset')
                    ->form([
                        Select::make('preset')
                            ->label('Period')
                            ->selectablePlaceholder(false)
                            ->options([
                                'all' => 'All time',
                                '24h' => 'Last 24 hours',
                                '72h' => 'Last 3 days',
                                'week' => 'Last 7 days',
                                '2weeks' => 'Last 2 weeks',
                                'month' => 'Last 30 days',
                                'quarter' => 'Last 3 months',
                                'halfyear' => 'Last 6 months',
                                'year' => 'Last year',
                            ]),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when($data['preset'], function ($query, $preset) {
                            if ($preset === 'all') {
                                return $query;
                            }

                            $query->where('created_at', '>=', match ($preset) {
                                '24h' => now()->subDay(),
                                '72h' => now()->subDays(3),
                                'week' => now()->subWeek(),
                                '2weeks' => now()->subWeeks(2),
                                'month' => now()->subMonth(),
                                'quarter' => now()->subMonths(3),
                                'halfyear' => now()->subMonths(6),
                                'year' => now()->subYear()
                            });
                        });
                    }),
                Tables\Filters\Filter::make('customer_country')
                    ->form([
                        Select::make('country')
                            ->label('Customer country')
                            ->options(Countries::getNames('en'))
                            ->searchable(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when($data['country'], function ($query, $country) {
                            $query->where(function ($q) use ($country) {
                                $q->whereJsonContains('payment_data->customer_details->address->country', $country)
                                    ->orWhereJsonContains('payment_data->payer->address->country_code', $country);
                            });
                        });
                    }),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')
                            ->native(false)
                            ->label('From date'),
                        DatePicker::make('created_until')
                            ->native(false)
                            ->label('To date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['created_from']) {
                            $indicators[] = Tables\Filters\Indicator::make('From '.$data['created_from']);
                        }

                        if ($data['created_until']) {
                            $indicators[] = Tables\Filters\Indicator::make('Until '.$data['created_until']);
                        }

                        return $indicators;
                    }),

            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            StatusHistoryRelationManager::class,
        ];
    }

    public static function getWidgets(): array
    {
        return [
            OrderStatsWidget::class,
            RevenueByProviderChart::class,
            RevenueByCountryChart::class,
            OrderStatusDistributionChart::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'view' => Pages\ViewOrder::route('/{record}'),
        ];
    }
}
