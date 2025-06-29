<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GameServerResource\Pages;
use App\Models\Utility\GameServer;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Config;

class GameServerResource extends Resource
{
    protected static ?string $model = GameServer::class;

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $modelLabel = 'Server';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Game Server Configuration')
                    ->description('Configure basic server information and activation status.')
                    ->aside()
                    ->schema([
                        TextInput::make('name')
                            ->label('Server Name')
                            ->required()
                            ->maxLength(255)
                            ->helperText('Enter a unique name for this game server.'),

                        Select::make('connection_name')
                            ->label('Database Connection')
                            ->options(self::getDbConnectionOptions())
                            ->required()
                            ->helperText('Select the database connection for this server.'),

                        TextInput::make('port')
                            ->label('Game Server Port')
                            ->required()
                            ->numeric()
                            ->default(55901)
                            ->maxLength(5)
                            ->helperText('Enter the port for the main game server.'),

                        DateTimePicker::make('launch_date')
                            ->label('Launch Date')
                            ->native(false)
                            ->helperText('Set the server launch date for countdown')
                            ->nullable(),

                        Toggle::make('is_active')
                            ->label('Server Status')
                            ->onColor('success')
                            ->offColor('danger')
                            ->onIcon('heroicon-s-check')
                            ->offIcon('heroicon-s-x-mark')
                            ->default(true)
                            ->required()
                            ->inline(false)
                            ->helperText('Toggle to activate or deactivate the server.'),

                        Toggle::make('is_default')
                            ->label('Default Server')
                            ->onColor('success')
                            ->offColor('gray')
                            ->onIcon('heroicon-s-star')
                            ->offIcon('heroicon-o-star')
                            ->default(false)
                            ->inline(false)
                            ->helperText('Mark as the default server for new users.')
                            ->afterStateUpdated(function ($state, $record) {
                                if ($state && $record) {
                                    // Ensure only one default server
                                    GameServer::where('id', '!=', $record->id)
                                        ->update(['is_default' => false]);
                                }
                            }),
                    ]),

                Section::make('Server Rate Configuration')
                    ->description('Set up the server rates and multipliers that affect gameplay mechanics.')
                    ->aside()
                    ->columns(2)
                    ->schema([
                        TextInput::make('server_version')
                            ->label('Server Version')
                            ->default('Season 3')
                            ->required()
                            ->helperText('Specify the MU Online server version.'),

                        TextInput::make('online_multiplier')
                            ->label('Online Multiplier')
                            ->default(1)
                            ->required()
                            ->minValue(0)
                            ->numeric()
                            ->helperText('Multiplier applied when players are online.'),

                        TextInput::make('experience_rate')
                            ->label('Experience Rate')
                            ->required()
                            ->numeric()
                            ->helperText('Rate at which players gain experience.'),

                        TextInput::make('drop_rate')
                            ->label('Drop Rate')
                            ->required()
                            ->numeric()
                            ->helperText('Rate at which items drop from monsters.'),
                    ]),

                Section::make('Character Reset Settings')
                    ->description('Configure character reset mechanics and associated costs.')
                    ->aside()
                    ->columns(2)
                    ->schema([
                        TextInput::make('max_resets')
                            ->label('Maximum Resets')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->helperText('Maximum number of resets allowed per character.'),

                        TextInput::make('starting_resets')
                            ->label('Starting Resets')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->helperText('Number of maximum resets the server starts with.'),

                        TextInput::make('reset_zen')
                            ->label('Reset Cost')
                            ->required()
                            ->numeric()
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters(',')
                            ->default(0)
                            ->minValue(0)
                            ->helperText('Amount of Zen required for character reset.')
                            ->suffix('Zen'),

                        TextInput::make('clear_pk_zen')
                            ->label('PK Clear Cost')
                            ->required()
                            ->numeric()
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters(',')
                            ->default(0)
                            ->minValue(0)
                            ->helperText('Cost to clear PK status from a character.')
                            ->suffix('Zen'),
                    ]),
            ]);
    }

    protected static function getDbConnectionOptions(): array
    {
        $connections = Config::get('database.connections');
        $options = [];

        foreach ($connections as $name => $config) {
            if (str_starts_with($name, 'gamedb_')) {
                $options[$name] = $name;
            }
        }

        return $options;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('experience_rate')
                    ->numeric(),
                TextColumn::make('online_multiplier')
                    ->numeric(),
                TextColumn::make('max_resets')
                    ->numeric(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),

                Tables\Columns\IconColumn::make('is_default')
                    ->label('Default')
                    ->boolean()
                    ->trueIcon('heroicon-s-star')
                    ->falseIcon('heroicon-o-star'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGameServers::route('/'),
            'create' => Pages\CreateGameServer::route('/create'),
            'edit' => Pages\EditGameServer::route('/{record}/edit'),
        ];
    }
}
