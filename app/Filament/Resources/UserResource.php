<?php

namespace App\Filament\Resources;

use App\Actions\User\BanUser;
use App\Actions\User\UnbanUser;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers\MemberRelationManager;
use App\Filament\Resources\UserResource\RelationManagers\TicketsRelationManager;
use App\Models\User\User;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationGroup = 'Account & Characters';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'User Logins';

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email'];
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return "{$record->name} ({$record->email})";
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('User Login Details')
                    ->description('View and update user account information, including email and password.')
                    ->aside()
                    ->columns(2)
                    ->schema([
                        Placeholder::make('name')
                            ->label('Username')
                            ->content(fn ($record) => $record->name),
                        Placeholder::make('email_verified_at')
                            ->label('Email Verified At')
                            ->content(function ($record) {
                                if ($record->email_verified_at) {
                                    return Carbon::parse($record->email_verified_at)->format('M d, Y H:i:s');
                                }

                                return 'Not verified';
                            }),
                        TextInput::make('email')
                            ->columnSpanFull()
                            ->email()
                            ->required()
                            ->maxLength(255),
                        Checkbox::make('change_password')
                            ->label('Change password')
                            ->columnSpanFull()
                            ->live()
                            ->afterStateUpdated(function (Set $set, $state) {
                                if (! $state) {
                                    $set('password', null);
                                    $set('password_confirmation', null);
                                }
                            }),
                        TextInput::make('password')
                            ->password()
                            ->required(fn (Get $get): bool => (bool) $get('change_password'))
                            ->maxLength(255)
                            ->visible(fn (Get $get): bool => (bool) $get('change_password'))
                            ->confirmed(),

                        TextInput::make('password_confirmation')
                            ->password()
                            ->required(fn (Get $get): bool => (bool) $get('change_password'))
                            ->maxLength(255)
                            ->visible(fn (Get $get): bool => (bool) $get('change_password'))
                            ->dehydrated(false),
                    ]),

                Section::make('Banned User')
                    ->aside()
                    ->description('This user has been banned from the platform.')
                    ->icon('heroicon-o-lock-closed')
                    ->visible(fn ($record) => $record && $record->is_banned)
                    ->schema([
                        Placeholder::make('banned_at')
                            ->label('Banned At')
                            ->content(fn ($record) => $record->banned_at ?
                                Carbon::parse($record->banned_at)->format('M d, Y H:i:s') :
                                'Not recorded'),

                        Placeholder::make('ban_duration')
                            ->label('Ban Duration')
                            ->content(fn ($record) => $record->banned_at ?
                                Carbon::parse($record->banned_at)->diffForHumans(null, true) :
                                'Unknown'),

                        Placeholder::make('ban_reason')
                            ->label('Ban Reason')
                            ->content(fn ($record) => $record->ban_reason ?: 'No reason provided'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Username')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\IconColumn::make('email_verified_at')
                    ->label('Verified')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->getStateUsing(function ($record) {
                        return $record->email_verified_at !== null;
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')
                            ->native(false),
                        DatePicker::make('created_until')
                            ->native(false),
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
            ->persistFiltersInSession()
            ->filtersTriggerAction(function ($action) {
                return $action->button()->label('Filters');
            })
            ->actions([
                Tables\Actions\Action::make('Verify')
                    ->visible(function ($record) {
                        return $record->email_verified_at === null;
                    })
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->verify();
                    })
                    ->after(function () {
                        Notification::make()->success()->title('The email was verified successfully!')
                            ->send();
                    }),

                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('ban')
                    ->visible(fn ($record) => ! $record->is_banned)
                    ->icon('heroicon-o-lock-closed')
                    ->color('danger')
                    ->form([
                        TextInput::make('ban_reason')
                            ->label('Reason for ban')
                            ->placeholder('Optional reason'),
                    ])
                    ->requiresConfirmation()
                    ->modalHeading('Ban User')
                    ->modalDescription('Are you sure you want to ban this user? They will be unable to log in.')
                    ->action(function (User $record, array $data) {
                        app(BanUser::class)->handle($record, $data['ban_reason'] ?? null);

                        Notification::make()
                            ->title('User banned successfully')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('unban')
                    ->visible(fn ($record) => $record->is_banned)
                    ->icon('heroicon-o-lock-open')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Unban User')
                    ->modalDescription('Are you sure you want to unban this user?')
                    ->action(function (User $record) {
                        app(UnbanUser::class)->handle($record);

                        Notification::make()
                            ->title('User unbanned successfully')
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('Verify selected')
                        ->requiresConfirmation()
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function (Collection $records) {
                            $records->each->verify();
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            MemberRelationManager::class,
            TicketsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
