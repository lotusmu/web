<?php

namespace App\Filament\Resources;

use App\Actions\Partner\ReviewPartner;
use App\Enums\Partner\PartnerStatus;
use App\Filament\Resources\PartnerReviewResource\Pages;
use App\Models\Partner\Partner;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Enums\IconPosition;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PartnerReviewResource extends Resource
{
    protected static ?string $model = Partner::class;

    protected static ?string $navigationGroup = 'Partners';

    protected static ?string $navigationLabel = 'Weekly Review';

    protected static ?int $navigationSort = 4;

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                // Only show active partners who haven't been reviewed this week
                return $query->where('status', PartnerStatus::ACTIVE)
                    ->whereDoesntHave('reviews', function (Builder $q) {
                        $q->where('week_number', now()->week)
                            ->where('year', now()->year);
                    });
            })
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Partner')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('promo_code')
                    ->label('Promo Code')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->iconPosition(IconPosition::After)
                    ->copyable(),

                Tables\Columns\TextColumn::make('level')
                    ->label('Level')
                    ->badge(),

                Tables\Columns\TextColumn::make('this_week_referrals')
                    ->label('This Week Referrals')
                    ->getStateUsing(function ($record) {
                        return $record->promoCodeUsages()
                            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                            ->count();
                    }),

                Tables\Columns\TextColumn::make('total_referrals')
                    ->label('Total Referrals')
                    ->getStateUsing(fn ($record) => $record->promoCodeUsages()->count()),

                Tables\Columns\TextColumn::make('approved_at')
                    ->label('Partner Since')
                    ->dateTime('M j, Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('level')
                    ->options([
                        1 => 'Level 1',
                        2 => 'Level 2',
                        3 => 'Level 3',
                        4 => 'Level 4',
                        5 => 'Level 5',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->form([
                        Forms\Components\Textarea::make('notes')
                            ->label('Review Notes (Optional)')
                            ->placeholder('Add any notes about this partner\'s performance...')
                            ->rows(3),
                    ])
                    ->action(function (Partner $record, array $data, ReviewPartner $reviewAction) {
                        $reviewAction->approve($record, $data['notes'] ?? null);

                        Notification::make()
                            ->success()
                            ->title('Partner Approved')
                            ->body("{$record->user->name} has been approved for this week.")
                            ->send();
                    }),

                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->form([
                        Forms\Components\Textarea::make('notes')
                            ->label('Rejection Reason')
                            ->placeholder('Explain why this partner is being rejected...')
                            ->required()
                            ->rows(3),
                    ])
                    ->action(function (Partner $record, array $data, ReviewPartner $reviewAction) {
                        $reviewAction->reject($record, $data['notes']);

                        Notification::make()
                            ->success()
                            ->title('Partner Rejected')
                            ->body("{$record->user->name} has been marked as inactive.")
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('bulk_approve')
                    ->label('Approve Selected')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->form([
                        Forms\Components\Textarea::make('notes')
                            ->label('Review Notes (Optional)')
                            ->placeholder('Add notes for all selected partners...')
                            ->rows(3),
                    ])
                    ->action(function ($records, array $data, ReviewPartner $reviewAction) {
                        $count = 0;
                        foreach ($records as $record) {
                            $reviewAction->approve($record, $data['notes'] ?? null);
                            $count++;
                        }

                        Notification::make()
                            ->success()
                            ->title('Partners Approved')
                            ->body("{$count} partners have been approved for this week.")
                            ->send();
                    }),
            ])
            ->emptyStateHeading('All Partners Reviewed!')
            ->emptyStateDescription('All active partners have been reviewed for this week.')
            ->emptyStateIcon('heroicon-o-check-badge')
            ->defaultSort('approved_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPartnerReviews::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }
}
