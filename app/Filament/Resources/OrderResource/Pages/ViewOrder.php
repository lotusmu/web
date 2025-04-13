<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\IconPosition;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Order Details')
                    ->schema([
                        TextEntry::make('user.name')
                            ->label('Username')
                            ->icon('heroicon-o-arrow-top-right-on-square')
                            ->iconPosition(IconPosition::After)
                            ->url(fn ($record) => route('filament.admin.resources.members.edit', ['record' => $record->user->name])),
                        TextEntry::make('package.name')
                            ->label('Package'),
                        TextEntry::make('amount')
                            ->money('eur'),
                        TextEntry::make('status')
                            ->badge(),
                        TextEntry::make('created_at')
                            ->dateTime(),
                    ])->columns(5),

                Section::make('Payment Information')
                    ->schema([
                        TextEntry::make('payment_provider')
                            ->badge(),
                        TextEntry::make('payment_id')
                            ->label('Payment ID'),
                        TextEntry::make('provider_transaction_id')
                            ->label('Provider Transaction ID')
                            ->getStateUsing(fn ($record) => $record->getProviderTransactionId()),
                    ])->columns(3),

                Section::make('Package Details')
                    ->schema([
                        TextEntry::make('package.name')
                            ->label('Package'),
                        TextEntry::make('package.tokens_amount')
                            ->numeric()
                            ->label('Tokens Amount'),
                        TextEntry::make('package.price')
                            ->label('Price')
                            ->money('eur'),
                    ])->columns(3),
            ]);
    }
}
