<?php

namespace App\Filament\Resources\PartnerApplicationResource\Pages;

use App\Actions\Partner\CreatePartnerFromApplication;
use App\Actions\User\SendNotification;
use App\Enums\Partner\ApplicationStatus;
use App\Enums\Partner\Platform;
use App\Filament\Resources\PartnerApplicationResource;
use App\Models\Partner\Partner;
use Filament\Actions;
use Filament\Forms;
use Filament\Infolists\Components;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewPartnerApplication extends ViewRecord
{
    protected static string $resource = PartnerApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('approve')
                ->label('Approve')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn () => $this->record->status === ApplicationStatus::PENDING)
                ->form([
                    Forms\Components\TextInput::make('promo_code')
                        ->label('Promo Code')
                        ->required()
                        ->maxLength(50)
                        ->unique('partners', 'promo_code')
                        ->helperText('Create a unique promo code for this partner')
                        ->default(fn () => $this->generateSuggestedPromoCode())
                        ->suffixAction(
                            Forms\Components\Actions\Action::make('regenerate')
                                ->icon('heroicon-m-arrow-path')
                                ->action(function (Forms\Set $set) {
                                    $set('promo_code', $this->generateSuggestedPromoCode());
                                })
                        ),
                ])
                ->modalHeading('Approve Partner Application')
                ->modalDescription('Please set a promo code for the new partner.')
                ->action(function (array $data) {
                    $this->record->update([
                        'status' => ApplicationStatus::APPROVED->value,
                        'reviewed_at' => now(),
                    ]);

                    $partner = (new CreatePartnerFromApplication)->handle($this->record, $data['promo_code']);

                    Notification::make()
                        ->success()
                        ->title('Application Approved!')
                        ->body("Partner account created with promo code: {$partner->promo_code}")
                        ->send();

                    $this->sendStatusNotification(ApplicationStatus::PENDING, ApplicationStatus::APPROVED);
                }),

            Actions\Action::make('reject')
                ->label('Reject')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->visible(fn () => $this->record->status === ApplicationStatus::PENDING)
                ->form([
                    Forms\Components\RichEditor::make('rejection_notes')
                        ->label('Rejection Reason')
                        ->placeholder('Please provide a reason for rejecting this application')
                        ->required()
                        ->helperText('This message will be visible to the applicant.'),
                ])
                ->action(function (array $data) {
                    $this->record->update([
                        'status' => ApplicationStatus::REJECTED->value,
                        'reviewed_at' => now(),
                        'notes' => $data['rejection_notes'],
                    ]);

                    Notification::make()
                        ->success()
                        ->title('Application Rejected')
                        ->body('Rejection reason has been saved.')
                        ->send();

                    $this->sendStatusNotification(ApplicationStatus::PENDING, ApplicationStatus::REJECTED);
                }),

            Actions\Action::make('addNotes')
                ->label('Add Notes')
                ->icon('heroicon-o-chat-bubble-left-ellipsis')
                ->color('gray')
                ->form([
                    Forms\Components\RichEditor::make('notes')
                        ->label('Admin Notes')
                        ->default(fn () => $this->record->notes)
                        ->placeholder('Add notes or feedback about this application')
                        ->helperText('These notes will be visible to the applicant.'),
                ])
                ->action(function (array $data) {
                    $this->record->update([
                        'notes' => $data['notes'],
                        'reviewed_at' => now(),
                    ]);

                    Notification::make()
                        ->success()
                        ->title('Notes Updated')
                        ->body('Notes have been saved successfully.')
                        ->send();

                    SendNotification::make('Application Notes Added')
                        ->body("We've updated the notes on your partner application.")
                        ->action('View Application', route('partners.status'))
                        ->send($this->record->user);
                }),
        ];
    }

    private function generateSuggestedPromoCode(): string
    {
        $userName = $this->record->user->name;
        $baseCode = strtoupper(str()->slug($userName, ''));
        $baseCode = substr($baseCode, 0, 8); // Limit to 8 characters

        $promoCode = $baseCode;
        $counter = 1;

        // Ensure uniqueness
        while (Partner::where('promo_code', $promoCode)->exists()) {
            $promoCode = $baseCode.$counter;
            $counter++;
        }

        return $promoCode;
    }

    private function sendStatusNotification(ApplicationStatus $oldStatus, ApplicationStatus $newStatus): void
    {
        if ($oldStatus === $newStatus) {
            return;
        }

        $title = match ($newStatus) {
            ApplicationStatus::APPROVED => 'Partner Application Approved',
            ApplicationStatus::REJECTED, ApplicationStatus::PENDING => 'Partner Application Update',
        };

        $body = match ($newStatus) {
            ApplicationStatus::APPROVED => 'Congratulations! Your partner application has been approved. You can now start earning through our partner program.',
            ApplicationStatus::REJECTED => "We've reviewed your partner application. Unfortunately, it wasn't approved at this time.",
            ApplicationStatus::PENDING => 'Your partner application status has been updated to pending review.',
        };

        SendNotification::make($title)
            ->body($body)
            ->action('View Application', route('partners.status'))
            ->send($this->record->user);
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Components\Section::make('Application Status')
                    ->schema([
                        Components\TextEntry::make('status')
                            ->badge(),
                        Components\TextEntry::make('created_at')
                            ->label('Submitted')
                            ->dateTime('M j, Y \a\t g:i A'),
                        Components\TextEntry::make('reviewed_at')
                            ->label('Reviewed')
                            ->dateTime('M j, Y \a\t g:i A')
                            ->placeholder('Not yet reviewed'),
                    ])
                    ->columns(3),

                Components\Section::make('Applicant Information')
                    ->schema([
                        Components\TextEntry::make('user.name')
                            ->label('Username'),
                        Components\TextEntry::make('user.email')
                            ->label('Email'),
                    ])
                    ->columns(2),

                Components\Section::make('Content Details')
                    ->schema([
                        Components\TextEntry::make('content_type')
                            ->label('Content Type')
                            ->formatStateUsing(function ($state) {
                                return match ($state) {
                                    'streaming' => 'Live Streaming',
                                    'content' => 'Video Content',
                                    'both' => 'Both',
                                    default => ucfirst($state)
                                };
                            }),
                        Components\TextEntry::make('platforms')
                            ->label('Platforms')
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
                            }),
                    ])
                    ->columns(2),

                Components\Section::make('Content Schedule')
                    ->schema([
                        Components\TextEntry::make('streaming_hours_per_day')
                            ->label('Streaming Hours/Day')
                            ->suffix(' hours')
                            ->placeholder('Not specified')
                            ->visible(fn ($record) => in_array($record->content_type, ['streaming', 'both'])),

                        Components\TextEntry::make('streaming_days_per_week')
                            ->label('Streaming Days/Week')
                            ->suffix(' days')
                            ->placeholder('Not specified')
                            ->visible(fn ($record) => in_array($record->content_type, ['streaming', 'both'])),

                        Components\TextEntry::make('videos_per_week')
                            ->label('Videos/Week')
                            ->suffix(' videos')
                            ->placeholder('Not specified')
                            ->visible(fn ($record) => in_array($record->content_type, ['content', 'both'])),
                    ])
                    ->columns(3)
                    ->visible(fn ($record) => $record->streaming_hours_per_day ||
                        $record->streaming_days_per_week ||
                        $record->videos_per_week
                    ),

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
                                    ->label('Channel Name'),
                            ])
                            ->columns(2),
                    ]),

                Components\Section::make('About Content Creator')
                    ->schema([
                        Components\TextEntry::make('about_you')
                            ->label('')
                            ->html()
                            ->prose(),
                    ]),

                Components\Section::make('Admin Notes')
                    ->schema([
                        Components\TextEntry::make('notes')
                            ->label('')
                            ->placeholder('No notes added yet')
                            ->html()
                            ->prose(),
                    ])
                    ->visible(fn () => $this->record->notes),
            ]);
    }
}
