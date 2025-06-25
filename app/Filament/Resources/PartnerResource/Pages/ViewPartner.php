<?php

namespace App\Filament\Resources\PartnerResource\Pages;

use App\Actions\Partner\PromotePartnerAction;
use App\Actions\User\SendNotification;
use App\Enums\Partner\Platform;
use App\Filament\Resources\PartnerResource;
use Exception;
use Filament\Actions;
use Filament\Infolists\Components;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewPartner extends ViewRecord
{
    protected static string $resource = PartnerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('promote')
                ->label('Promote Partner')
                ->icon('heroicon-o-arrow-up')
                ->color('success')
                ->visible(fn (): bool => $this->record->level->getNextLevel() !== null)
                ->requiresConfirmation()
                ->modalHeading(fn (): string => "Promote {$this->record->user->name}?")
                ->modalDescription(fn (): string => "This will promote {$this->record->user->name} from {$this->record->level->getLabel()} to {$this->record->level->getNextLevel()->getLabel()} and update their commission to {$this->record->level->getNextLevel()->getTokenPercentage()}%."
                )
                ->action(function (): void {
                    try {
                        $oldLevel = $this->record->level;
                        app(PromotePartnerAction::class)->handle($this->record);

                        // Refresh the record to show updated data
                        $this->record = $this->record->fresh();

                        // Send admin notification
                        Notification::make()
                            ->title('Partner promoted successfully')
                            ->body("{$this->record->user->name} has been promoted to {$this->record->level->getLabel()}")
                            ->success()
                            ->send();

                        // Send user notification
                        SendNotification::make('Congratulations! You\'ve been promoted!')
                            ->body('You have been promoted from :oldLevel to :newLevel. Your new commission rate is :percentage%.', [
                                'oldLevel' => $oldLevel->getLabel(),
                                'newLevel' => $this->record->level->getLabel(),
                                'percentage' => $this->record->token_percentage,
                            ])
                            ->action('View Dashboard', route('partners.dashboard'))
                            ->send($this->record->user);
                    } catch (Exception $e) {
                        Notification::make()
                            ->title('Promotion failed')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),

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
