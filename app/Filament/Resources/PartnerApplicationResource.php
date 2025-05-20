<?php

namespace App\Filament\Resources;

use App\Enums\Partner\ApplicationStatus;
use App\Enums\Partner\Platform;
use App\Filament\Resources\PartnerApplicationResource\Pages;
use App\Models\Partner\PartnerApplication;
use Filament\Forms;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PartnerApplicationResource extends Resource
{
    protected static ?string $model = PartnerApplication::class;

    protected static ?string $navigationGroup = 'Partners';

    protected static ?string $navigationLabel = 'Applications';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Review Decision')
                    ->description('Set the application status and provide feedback.')
                    ->aside()
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Application Status')
                            ->options([
                                ApplicationStatus::PENDING->value => 'Pending Review',
                                ApplicationStatus::APPROVED->value => 'Approved',
                                ApplicationStatus::REJECTED->value => 'Rejected',
                            ])
                            ->required(),

                        Forms\Components\Textarea::make('notes')
                            ->label('Admin Notes')
                            ->placeholder('Add notes or feedback about this application')
                            ->helperText('These notes will be visible to the applicant.'),
                    ]),

                Forms\Components\Section::make('Applicant Information')
                    ->description('Details provided by the content creator.')
                    ->aside()
                    ->schema([
                        Placeholder::make('user_name')
                            ->label('Applicant Username')
                            ->content(fn (PartnerApplication $record): string => $record->user->name ?? 'Unknown'),

                        Placeholder::make('user_email')
                            ->label('Applicant Email')
                            ->content(fn (PartnerApplication $record): string => $record->user->email ?? 'Unknown'),

                        Placeholder::make('submitted_at')
                            ->label('Submitted')
                            ->content(fn (PartnerApplication $record
                            ): string => $record->created_at->format('M j, Y \a\t g:i A')),
                    ]),

                Forms\Components\Section::make('Content Details')
                    ->description('Information about the creator\'s content and platforms.')
                    ->aside()
                    ->schema([
                        Placeholder::make('content_type')
                            ->label('Content Type')
                            ->content(function (PartnerApplication $record): string {
                                return match ($record->content_type) {
                                    'streaming' => 'Live Streaming',
                                    'content' => 'Video Content',
                                    'both' => 'Both',
                                    default => ucfirst($record->content_type)
                                };
                            }),

                        Placeholder::make('platforms')
                            ->label('Platforms')
                            ->content(function (PartnerApplication $record): string {
                                if (! is_array($record->platforms)) {
                                    return '';
                                }

                                $formatted = [];
                                foreach ($record->platforms as $platformValue) {
                                    $platform = Platform::tryFrom($platformValue);
                                    if ($platform) {
                                        $formatted[] = $platform->getLabel();
                                    }
                                }

                                return implode(', ', $formatted);
                            }),
                    ]),

                Forms\Components\Section::make('Channels Information')
                    ->description('Details about the creator\'s channels.')
                    ->aside()
                    ->schema([
                        Forms\Components\KeyValue::make('channels')
                            ->label('')
                            ->keyLabel('Platform')
                            ->valueLabel('Channel Name')
                            ->editableKeys(false)
                            ->editableValues(false)
                            ->addable(false)
                            ->deletable(false)
                            ->afterStateHydrated(function ($component, $state) {
                                // Convert the channels array to key-value format
                                if (! is_array($state)) {
                                    return;
                                }

                                $formattedState = [];
                                foreach ($state as $channel) {
                                    if (isset($channel['platform'], $channel['name'])) {
                                        $platform = Platform::tryFrom($channel['platform']);
                                        $platformLabel = $platform ? $platform->getLabel() : ucfirst($channel['platform']);
                                        $formattedState[$platformLabel] = $channel['name'];
                                    }
                                }

                                $component->state($formattedState);
                            }),
                    ]),

                Forms\Components\Section::make('About Content Creator')
                    ->description('Additional information provided by the creator.')
                    ->aside()
                    ->schema([
                        // Use a standard textarea for the about section
                        Forms\Components\RichEditor::make('about_you')
                            ->label('About Content')
                            ->disabled(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Applicant')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('content_type')
                    ->label('Content Type')
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            'streaming' => 'Live Streaming',
                            'content' => 'Video Content',
                            'both' => 'Both',
                            default => ucfirst($state)
                        };
                    }),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge(),

                TextColumn::make('created_at')
                    ->label('Submitted')
                    ->dateTime('M j, Y')
                    ->sortable(),

                TextColumn::make('reviewed_at')
                    ->label('Reviewed')
                    ->dateTime('M j, Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        ApplicationStatus::PENDING->value => 'Pending',
                        ApplicationStatus::APPROVED->value => 'Approved',
                        ApplicationStatus::REJECTED->value => 'Rejected',
                    ]),
            ])
            ->actions([
                Action::make('approve')
                    ->label('Approve')
                    ->color('success')
                    ->icon('heroicon-o-check-circle')
                    ->visible(fn (PartnerApplication $record) => $record->status === ApplicationStatus::PENDING)
                    ->action(function (PartnerApplication $record) {
                        $record->update([
                            'status' => ApplicationStatus::APPROVED->value,
                            'reviewed_at' => now(),
                        ]);

                        // In the future, we'll create a Partner record here
                    }),

                Action::make('reject')
                    ->label('Reject')
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->visible(fn (PartnerApplication $record) => $record->status === ApplicationStatus::PENDING)
                    ->action(function (PartnerApplication $record) {
                        $record->update([
                            'status' => ApplicationStatus::REJECTED->value,
                            'reviewed_at' => now(),
                        ]);
                    }),

                EditAction::make(),

            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPartnerApplications::route('/'),
            'create' => Pages\CreatePartnerApplication::route('/create'),
            'edit' => Pages\EditPartnerApplication::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with('user');

    }
}
