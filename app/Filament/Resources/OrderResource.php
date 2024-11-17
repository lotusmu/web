<?php

namespace App\Filament\Resources;

use App\Enums\OrderStatus;
use App\Enums\PaymentProvider;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\Widgets\OrderStatsWidget;
use App\Filament\Resources\OrderResource\Widgets\PaymentProviderDistributionChart;
use App\Filament\Resources\OrderResource\Widgets\RevenueByCountryChart;
use App\Filament\Resources\OrderResource\Widgets\RevenueByProviderChart;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\IconPosition;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationGroup = 'Payments';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('amount')
                            ->required()
                            ->disabled()
                            ->numeric(),
                        Forms\Components\TextInput::make('currency')
                            ->required()
                            ->disabled(),
                        Forms\Components\Select::make('status')
                            ->options(OrderStatus::class)
                            ->required(),
                        Forms\Components\TextInput::make('payment_id')
                            ->label('Payment ID')
                            ->required()
                            ->disabled(),
                        Forms\Components\KeyValue::make('payment_data')
                            ->disabled(),
                    ]),
            ]);
    }

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
                    ->options(PaymentProvider::class),
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from'),
                        DatePicker::make('created_until'),
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
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getWidgets(): array
    {
        return [
            OrderStatsWidget::class,
            RevenueByProviderChart::class,
            RevenueByCountryChart::class,
            PaymentProviderDistributionChart::class,
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
