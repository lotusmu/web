<?php

namespace App\Filament\Resources;

use App\Enums\Partner\PartnerLevel;
use App\Enums\Partner\PartnerStatus;
use App\Filament\Resources\PartnerResource\Pages;
use App\Filament\Resources\PartnerResource\Widgets\PartnerPerformanceWidget;
use App\Models\Partner\Partner;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PartnerResource extends Resource
{
    protected static ?string $model = Partner::class;

    protected static ?string $navigationGroup = 'Partners';

    protected static ?string $navigationLabel = 'Partners';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Partner Details')
                    ->description('Basic partner information and status.')
                    ->aside()
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('User')
                            ->relationship('user', 'name')
                            ->required()
                            ->searchable()
                            ->placeholder('Select a user'),

                        Forms\Components\TextInput::make('promo_code')
                            ->label('Promo Code')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->placeholder('e.g., STREAMER123')
                            ->helperText('Unique promotional code for this partner'),
                    ]),

                Forms\Components\Section::make('Partnership Level & Status')
                    ->description('Configure partner level and current status.')
                    ->aside()
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('level')
                            ->label('Partner Level')
                            ->options(PartnerLevel::class)
                            ->required()
                            ->placeholder('Select partner level'),

                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options(PartnerStatus::class)
                            ->required()
                            ->placeholder('Select status'),

                        Forms\Components\TextInput::make('token_percentage')
                            ->label('Token Commission')
                            ->numeric()
                            ->step(0.01)
                            ->suffix('%')
                            ->required()
                            ->placeholder('0.00')
                            ->helperText('Percentage of tokens earned from referrals'),

                        Forms\Components\DateTimePicker::make('approved_at')
                            ->label('Approval Date')
                            ->native(false)
                            ->placeholder('Select approval date'),
                    ]),

                Forms\Components\Section::make('Platforms & Channels')
                    ->description('Configure streaming platforms and channel information.')
                    ->aside()
                    ->schema([
                        Forms\Components\TagsInput::make('platforms')
                            ->label('Platforms')
                            ->placeholder('Add platforms (e.g., Twitch, YouTube)')
                            ->helperText('Press Enter to add each platform'),

                        Forms\Components\Repeater::make('channels')
                            ->label('Channel Details')
                            ->schema([
                                Forms\Components\TextInput::make('platform')
                                    ->label('Platform')
                                    ->required()
                                    ->placeholder('e.g., Twitch'),

                                Forms\Components\TextInput::make('name')
                                    ->label('Channel Name')
                                    ->required()
                                    ->placeholder('Channel/Username'),
                            ])
                            ->columns(2)
                            ->addActionLabel('Add Channel')
                            ->collapsible()
                            ->defaultItems(0),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('level')
                    ->badge(),

                Tables\Columns\TextColumn::make('token_percentage')
                    ->suffix('%')
                    ->sortable(),

                Tables\Columns\IconColumn::make('has_live_streams')
                    ->label('Live')
                    ->boolean()
                    ->getStateUsing(function (Partner $record): bool {
                        return $record->streamSessions()->whereNull('ended_at')->exists();
                    })
                    ->trueColor('success')
                    ->falseColor('gray'),

                Tables\Columns\TextColumn::make('streams_this_week')
                    ->label('Streams/Week')
                    ->getStateUsing(function (Partner $record): string {
                        $count = $record->streamSessions()
                            ->whereBetween('started_at', [now()->startOfWeek(), now()->endOfWeek()])
                            ->count();

                        return (string) $count;
                    })
                    ->sortable(false),

                Tables\Columns\TextColumn::make('status')
                    ->badge(),

                Tables\Columns\TextColumn::make('approved_at')
                    ->dateTime('M j, Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(PartnerStatus::class),

                Tables\Filters\SelectFilter::make('level')
                    ->options(PartnerLevel::class),

                Tables\Filters\Filter::make('live_now')
                    ->query(fn (Builder $query): Builder => $query->whereHas('streamSessions', fn (Builder $q) => $q->whereNull('ended_at')
                    )
                    )
                    ->label('Currently Live'),

                Tables\Filters\Filter::make('streamed_this_week')
                    ->query(fn (Builder $query): Builder => $query->whereHas('streamSessions', fn (Builder $q) => $q->whereBetween('started_at', [
                        now()->startOfWeek(),
                        now()->endOfWeek(),
                    ])
                    )
                    )
                    ->label('Streamed This Week'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('approved_at', 'desc');
    }

    public static function getWidgets(): array
    {
        return [
            PartnerPerformanceWidget::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPartners::route('/'),
            'create' => Pages\CreatePartner::route('/create'),
            'view' => Pages\ViewPartner::route('/{record}'),
            'edit' => Pages\EditPartner::route('/{record}/edit'),
        ];
    }
}
