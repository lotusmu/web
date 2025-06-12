<?php

namespace App\Filament\Resources\PartnerReviewHistoryResource\Pages;

use App\Filament\Resources\PartnerReviewHistoryResource;
use Filament\Infolists\Components;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewPartnerReviewHistory extends ViewRecord
{
    protected static string $resource = PartnerReviewHistoryResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Components\Section::make('Review Information')
                    ->schema([
                        Components\TextEntry::make('partner.user.name')
                            ->label('Partner Name'),

                        Components\TextEntry::make('partner.promo_code')
                            ->label('Promo Code')
                            ->copyable(),

                        Components\TextEntry::make('partner.level')
                            ->label('Partner Level')
                            ->formatStateUsing(fn ($state) => $state->getLabel())
                            ->badge(),

                        Components\TextEntry::make('week_number')
                            ->label('Week Number'),

                        Components\TextEntry::make('year')
                            ->label('Year'),

                        Components\TextEntry::make('decision')
                            ->label('Decision')
                            ->formatStateUsing(fn ($state) => ucwords($state))
                            ->badge()
                            ->color(fn ($state) => match ($state) {
                                'approved' => 'success',
                                'rejected' => 'danger',
                                default => 'gray',
                            }),

                        Components\TextEntry::make('created_at')
                            ->label('Reviewed At')
                            ->dateTime('M j, Y \a\t H:i'),
                    ])
                    ->columns(3),

                Components\Section::make('Review Notes')
                    ->schema([
                        Components\TextEntry::make('notes')
                            ->label('')
                            ->placeholder('No notes provided')
                            ->prose(),
                    ])
                    ->visible(fn ($record) => ! empty($record->notes)),

                Components\Section::make('Partner Statistics (At Time of Review)')
                    ->schema([
                        Components\TextEntry::make('total_referrals_at_review')
                            ->label('Total Referrals')
                            ->getStateUsing(function ($record) {
                                // Get referrals up to the review date
                                return $record->partner->promoCodeUsages()
                                    ->where('created_at', '<=', $record->created_at)
                                    ->count();
                            }),

                        Components\TextEntry::make('week_referrals_at_review')
                            ->label('Week Referrals')
                            ->getStateUsing(function ($record) {
                                // Get referrals for that specific week
                                $weekStart = now()->setISODate($record->year, $record->week_number)->startOfWeek();
                                $weekEnd = $weekStart->copy()->endOfWeek();

                                return $record->partner->promoCodeUsages()
                                    ->whereBetween('created_at', [$weekStart, $weekEnd])
                                    ->count();
                            }),

                        Components\TextEntry::make('tokens_earned_at_review')
                            ->label('Total Tokens Earned')
                            ->getStateUsing(function ($record) {
                                return number_format(
                                    $record->partner->promoCodeUsages()
                                        ->where('created_at', '<=', $record->created_at)
                                        ->sum('partner_tokens')
                                );
                            }),
                    ])
                    ->columns(3),
            ]);
    }
}
