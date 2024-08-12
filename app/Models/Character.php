<?php

namespace App\Models;

use App\Enums\CharacterClass;
use App\Enums\Map;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Character extends Model
{
    protected $connection = 'game_server_1';

    protected $table = 'Character';

    public $timestamps = false;

    protected $primaryKey = 'Name';

    public $incrementing = false;

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
        'PkLevel' => 'integer',
        'PkTime' => 'integer',
        'CtlCode' => 'integer',
        'ResetCount' => 'integer',
        'MasterResetCount' => 'integer',
        'Kills' => 'integer',
        'Deads' => 'integer',
    ];

    public function getClassName(): string
    {
        return $this->Class->getName();
    }

    public static function getForm()
    {
        return [
            TextInput::make('AccountID')
                ->required()
                ->maxLength(10),
            TextInput::make('cLevel')
                ->numeric()
                ->default(1),
            TextInput::make('LevelUpPoint')
                ->numeric()
                ->default(0),
            Select::make('Class')
                ->label('Class')
                ->options(CharacterClass::class)
                ->enum(CharacterClass::class)
                ->required(),
            TextInput::make('Experience')
                ->numeric()
                ->default(0),
            TextInput::make('Strength')
                ->numeric(),
            TextInput::make('Dexterity')
                ->numeric(),
            TextInput::make('Vitality')
                ->numeric(),
            TextInput::make('Energy')
                ->numeric(),
            TextInput::make('Leadership')
                ->numeric()
                ->default(0),
            TextInput::make('Inventory'),
            TextInput::make('MagicList'),
            TextInput::make('Money')
                ->numeric()
                ->default(0),
            TextInput::make('Life')
                ->numeric(),
            TextInput::make('MaxLife')
                ->numeric(),
            TextInput::make('Mana')
                ->numeric(),
            TextInput::make('MaxMana')
                ->numeric(),
            TextInput::make('BP')
                ->numeric(),
            TextInput::make('MaxBP')
                ->numeric(),
            TextInput::make('Shield')
                ->numeric(),
            TextInput::make('MaxShield')
                ->numeric(),
            TextInput::make('MapNumber')
                ->numeric(),
            TextInput::make('MapPosX')
                ->numeric(),
            TextInput::make('MapPosY')
                ->numeric(),
            TextInput::make('MapDir')
                ->numeric()
                ->default(0),
            TextInput::make('PkCount')
                ->numeric()
                ->default(0),
            TextInput::make('PkLevel')
                ->numeric()
                ->default(3),
            TextInput::make('PkTime')
                ->numeric()
                ->default(0),
            DateTimePicker::make('MDate'),
            DateTimePicker::make('LDate'),
            TextInput::make('CtlCode')
                ->numeric()
                ->default(0),
            TextInput::make('DbVersion')
                ->numeric()
                ->default(0),
            TextInput::make('Quest'),
            TextInput::make('ChatLimitTime')
                ->numeric()
                ->default(0),
            TextInput::make('FruitPoint')
                ->numeric()
                ->default(0),
            TextInput::make('EffectList'),
            TextInput::make('FruitAddPoint')
                ->required()
                ->numeric()
                ->default(0),
            TextInput::make('FruitSubPoint')
                ->required()
                ->numeric()
                ->default(0),
            TextInput::make('ResetCount')
                ->required()
                ->numeric()
                ->default(0),
            TextInput::make('MasterResetCount')
                ->required()
                ->numeric()
                ->default(0),
            TextInput::make('ExtInventory')
                ->required()
                ->numeric()
                ->default(0),
            TextInput::make('Kills')
                ->required()
                ->numeric()
                ->default(0),
            TextInput::make('Deads')
                ->required()
                ->numeric()
                ->default(0),
            DateTimePicker::make('bloc_expire'),
            TextInput::make('ItemStart')
                ->required()
                ->numeric()
                ->default(0),
            TextInput::make('ResetDay')
                ->required()
                ->numeric()
                ->default(0),
            TextInput::make('ResetWek')
                ->required()
                ->numeric()
                ->default(0),
            TextInput::make('ResetMon')
                ->required()
                ->numeric()
                ->default(0),
            TextInput::make('CustomFlag')
                ->required()
                ->numeric()
                ->default(0),
            TextInput::make('CustomSkin')
                ->required()
                ->numeric()
                ->default(0),
            TextInput::make('LevelUpType')
                ->required()
                ->numeric()
                ->default(0),
            TextInput::make('last_reset_time')
                ->numeric(),
            TextInput::make('last_greset_time')
                ->numeric(),
            TextInput::make('resets')
                ->required()
                ->numeric()
                ->default(0),
            TextInput::make('grand_resets')
                ->required()
                ->numeric()
                ->default(0),
            TextInput::make('dmn_pk_count')
                ->required()
                ->numeric()
                ->default(0),
            TextInput::make('dmn_last_server_pk_count')
                ->required()
                ->numeric()
                ->default(0),
            TextInput::make('monster_kill_points')
                ->required()
                ->numeric()
                ->default(0),
        ];
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'memb___id', 'AccountID');
    }
}
