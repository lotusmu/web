<?php

namespace App\Filament\Resources\PartnerApplicationResource\Pages;

use App\Actions\User\SendNotification;
use App\Enums\Partner\ApplicationStatus;
use App\Enums\Partner\Platform;
use App\Filament\Resources\PartnerApplicationResource;
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
            Actions\Action::make('changeStatus')
                ->label('Change Status')
                ->icon('heroicon-o-pencil-square')
                ->color('primary')
                ->form([
                    Forms\Components\Select::make('status')
                        ->label('Application Status')
                        ->options([
                            ApplicationStatus::PENDING->value => 'Pending Review',
                            ApplicationStatus::APPROVED->value => 'Approved',
                            ApplicationStatus::REJECTED->value => 'Rejected',
                        ])
                        ->default(fn () => $this->record->status->value)
                        ->required(),
                ])
                ->action(function (array $data) {
                    $oldStatus = $this->record->status;
                    $newStatus = ApplicationStatus::from($data['status']);

                    $this->record->update([
                        'status' => $data['status'],
                        'reviewed_at' => now(),
                    ]);

                    Notification::make()
                        ->success()
                        ->title('Success!')
                        ->body('Application status updated successfully.')
                        ->send();

                    $this->sendStatusNotification($oldStatus, $newStatus);
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
                        ->title('Success!')
                        ->body('Notes added successfully.')
                        ->send();

                    SendNotification::make('Application Notes Added')
                        ->body("We've added notes to your partner application.")
                        ->action('View Application', route('partners.status'))
                        ->send($this->record->user);
                }),
        ];
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
