<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GameServerResource\Pages;
use App\Models\Utility\GameServer;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Config;

class GameServerResource extends Resource
{
    protected static ?string $model = GameServer::class;

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationIcon = 'heroicon-o-server-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Game Server')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Select::make('connection_name')
                            ->options(self::getDbConnectionOptions())
                            ->required(),
                        TextInput::make('experience_rate')
                            ->required()
                            ->numeric(),
                        TextInput::make('drop_rate')
                            ->required()
                            ->numeric(),
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
                Tables\Columns\TextColumn::make('connection_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('experience_rate')
                    ->numeric(),
                Tables\Columns\TextColumn::make('drop_rate')
                    ->numeric(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
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
