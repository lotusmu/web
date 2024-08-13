<?php

namespace App\Models;

use App\Enums\CharacterClass;
use App\Enums\Map;
use App\Enums\PkLevel;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Character extends Model
{
    public $timestamps = false;

    public $incrementing = false;

    protected $connection = 'game_server_1';

    protected $table = 'Character';

    protected $primaryKey = 'Name';

    protected $keyType = 'string';

    protected $fillable = [
        'AccountID',
        'Name',
        'cLevel',
        'LevelUpPoint',
        'Class',
        'Strength',
        'Dexterity',
        'Vitality',
        'Energy',
        'Leadership',
        'Money',
        'MapNumber',
        'MapPosX',
        'MapPosY',
        'PkCount',
        'PkLevel',
        'PkTime',
        'CtlCode',
        'ResetCount',
        'MasterResetCount',
        'ExtInventory',
        'Kills',
        'Deads',
    ];

    protected $casts = [
        'cLevel' => 'integer',
        'LevelUpPoint' => 'integer',
        'Class' => CharacterClass::class,
        'Strength' => 'integer',
        'Dexterity' => 'integer',
        'Vitality' => 'integer',
        'Energy' => 'integer',
        'Leadership' => 'integer',
        'Money' => 'integer',
        'MapNumber' => Map::class,
        'MapPosX' => 'integer',
        'MapPosY' => 'integer',
        'PkCount' => 'integer',
        'PkLevel' => PkLevel::class,
        'PkTime' => 'integer',
        'CtlCode' => 'integer',
        'ResetCount' => 'integer',
        'MasterResetCount' => 'integer',
        'Kills' => 'integer',
        'Deads' => 'integer',
    ];

    public static function getForm()
    {
        return [
            Section::make('Character Information')
                ->description('General information about the character.')
                ->aside()
                ->columns(2)
                ->schema([
                    TextInput::make('Name')
                        ->label('Character Name')
                        ->columnSpanFull()
                        ->required()
                        ->minLength(4)
                        ->maxLength(10),
                    Select::make('Class')
                        ->label('Class')
                        ->columnSpanFull()
                        ->options(CharacterClass::class)
                        ->enum(CharacterClass::class)
                        ->searchable()
                        ->preload()
                        ->required(),
                    TextInput::make('ResetCount')
                        ->label('Resets')
                        ->required()
                        ->numeric()
                        ->default(0),
                    TextInput::make('cLevel')
                        ->label('Level')
                        ->required()
                        ->numeric()
                        ->default(1),
                ]),
            Section::make('Other Information')
                ->description('Detailed information about the character.')
                ->aside()
                ->schema([
                    Tabs::make('Tabs')
                        ->tabs([
                            Tabs\Tab::make('Character Stats')
                                ->columns(2)
                                ->schema([
                                    TextInput::make('Strength')
                                        ->required()
                                        ->minValue(1)
                                        ->maxValue(32767)
                                        ->numeric(),
                                    TextInput::make('Dexterity')
                                        ->required()
                                        ->minValue(1)
                                        ->maxValue(32767)
                                        ->numeric(),
                                    TextInput::make('Vitality')
                                        ->required()
                                        ->minValue(1)
                                        ->maxValue(32767)
                                        ->numeric(),
                                    TextInput::make('Energy')
                                        ->required()
                                        ->minValue(1)
                                        ->maxValue(32767)
                                        ->numeric(),
                                    TextInput::make('Leadership')
                                        ->columnSpanFull()
                                        ->required()
                                        ->minValue(0)
                                        ->maxValue(32767)
                                        ->numeric()
                                        ->default(0),
                                ]),
                            Tabs\Tab::make('Location')
                                ->schema([
                                    Select::make('MapNumber')
                                        ->label('Map Name')
                                        ->options(Map::class)
                                        ->enum(Map::class)
                                        ->searchable()
                                        ->preload()
                                        ->required(),
                                    TextInput::make('MapPosX')
                                        ->label('X Position')
                                        ->required()
                                        ->minValue(0)
                                        ->maxValue(255)
                                        ->numeric(),
                                    TextInput::make('MapPosY')
                                        ->label('Y Position')
                                        ->required()
                                        ->minValue(0)
                                        ->maxValue(255)
                                        ->numeric(),
                                ]),
                            Tabs\Tab::make('Player Status')
                                ->schema([
                                    Select::make('PkLevel')
                                        ->label('PK Level')
                                        ->options(PkLevel::class)
                                        ->enum(PkLevel::class)
                                        ->required()
                                        ->searchable()
                                        ->preload(),
                                    TextInput::make('PkCount')
                                        ->label('Kills Count')
                                        ->numeric()
                                        ->required()
                                        ->minValue(0)
                                        ->default(0),
                                    TextInput::make('PkTime')
                                        ->label('PK Time')
                                        ->numeric()
                                        ->minValue(0)
                                        ->required()
                                        ->default(0),
                                ]),
                        ]),
                ]),
        ];
    }

    public function getClassName(): string
    {
        return $this->Class->getName();
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'memb___id', 'AccountID');
    }
}
