<?php

namespace App\Filament\Widgets;

use App\Models\User\User;
use Filament\Support\Enums\IconPosition;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\DB;

class TopCustomersTable extends BaseWidget
{
    protected static ?int $sort = 7;

    protected static ?string $heading = 'Top Customers';

    protected int|string|array $columnSpan = [
        'default' => 'full',
        'lg' => 6,
    ];

    public function table(Table $table): Table
    {
        return $table
            ->query(
                User::query()
                    ->select('users.*', DB::raw('SUM(orders.amount) as total_spent'))
                    ->join('orders', 'users.id', '=', 'orders.user_id')
                    ->where('orders.status', 'completed')
                    ->groupBy('users.id')
                    ->orderByDesc('total_spent')
                    ->limit(5)
            )
            ->columns([
                TextColumn::make('name')
                    ->label('Username')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->iconPosition(IconPosition::After)
                    ->url(fn ($record) => route('filament.admin.resources.members.edit', ['record' => $record->name])),
                TextColumn::make('total_spent')
                    ->money('eur')
                    ->label('Total Spent'),
                TextColumn::make('created_at')
                    ->dateTime('M j, Y')
                    ->label('Member Since'),
            ])
            ->paginated([5]);
    }
}
