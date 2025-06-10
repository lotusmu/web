<?php

namespace App\Filament\Resources\PartnerResource\Pages;

use App\Enums\Partner\Platform;
use App\Filament\Resources\PartnerResource;
use Filament\Actions;
use Filament\Infolists\Components;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewPartner extends ViewRecord
{
    protected static string $resource = PartnerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Components\Section::make('Partner Details')
                    ->schema([
                        Components\TextEntry::make('user.name')
                            ->label('Partner'),
                        Components\TextEntry::make('user.email')
                            ->label('Email'),
                        Components\TextEntry::make('promo_code')
                            ->label('Promo Code')
                            ->copyable(),
                        Components\TextEntry::make('level')
                            ->label('Level')
                            ->badge(),
                        Components\TextEntry::make('token_percentage')
                            ->label('Token Percentage')
                            ->suffix('%'),
                        Components\TextEntry::make('status')
                            ->label('Status')
                            ->badge(),
                        Components\TextEntry::make('approved_at')
                            ->label('Approved')
                            ->dateTime('M j, Y \a\t g:i A'),
                    ])
                    ->columns(3),

                Components\Section::make('Performance Stats')
                    ->schema([
                        Components\TextEntry::make('total_referrals')
                            ->label('Total Referrals')
                            ->getStateUsing(fn ($record) => number_format($record->promoCodeUsages()->count())),

                        Components\TextEntry::make('this_month_referrals')
                            ->label('This Month Referrals')
                            ->getStateUsing(fn ($record) => number_format(
                                $record->promoCodeUsages()
                                    ->whereMonth('created_at', now()->month)
                                    ->whereYear('created_at', now()->year)
                                    ->count()
                            )),

                        Components\TextEntry::make('total_tokens')
                            ->label('Total Tokens Earned')
                            ->getStateUsing(fn ($record) => number_format($record->getTotalTokensEarned())),

                        Components\TextEntry::make('tokens_this_month')
                            ->label('Tokens This Month')
                            ->getStateUsing(fn ($record) => number_format($record->getTokensEarnedThisMonth())),
                    ])
                    ->columns(4),

                Components\Section::make('Platforms')
                    ->schema([
                        Components\TextEntry::make('platforms')
                            ->label('Active Platforms')
                            ->formatStateUsing(function ($state) {
                                if (! is_array($state)) {
                                    return '';
                                }

                                $formatted = [];
                                foreach ($state as $platformValue) {
                                    $platform = Platform::tryFrom($platformValue);
                                    if ($platform) {
                                        $formatted[] = $platform->getLabel();
                                    }
                                }

                                return implode(', ', $formatted);
                            })
                            ->badge(),
                    ]),

                Components\Section::make('Channels')
                    ->schema([
                        Components\RepeatableEntry::make('channels')
                            ->label('')
                            ->schema([
                                Components\TextEntry::make('platform')
                                    ->formatStateUsing(function ($state) {
                                        $platform = Platform::tryFrom($state);

                                        return $platform ? $platform->getLabel() : ucfirst($state);
                                    }),
                                Components\TextEntry::make('name')
                                    ->label('Channel Name/URL'),
                            ])
                            ->columns(2),
                    ]),
            ]);
    }
}
