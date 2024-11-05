<?php

namespace App\Models\Game;

use App\Enums\Game\CharacterClass;
use App\Enums\Game\Map;
use App\Enums\Game\PkLevel;
use App\Models\Concerns\GameConnection;
use App\Models\User\Member;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Character extends Model
{
    use GameConnection;
    use HasFactory;

    protected $table = 'Character';

    protected $primaryKey = 'Name';

    protected $keyType = 'string';

    public $timestamps = false;

    public $incrementing = false;

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

    public static function getFillableFields(): array
    {
        return (new static)->getFillable();
    }

    public static function findUserByCharacterName(string $characterName): ?User
    {
        $character = self::where('Name', $characterName)->first();

        if (! $character) {
            return null;
        }

        $member = Member::where('memb___id', $character->AccountID)->first();

        if (! $member) {
            return null;
        }

        return User::where('name', $member->memb___id)->first();
    }

    //    public function shouldHideInformation(): bool
    //    {
    //        return $this->member->user->hasActiveStealthMode();
    //    }
    //
    //    public function getAttributeValue($key)
    //    {
    //        if ($this->shouldHideInformation() && in_array($key, [
    //            'cLevel',
    //            'Strength',
    //            'Dexterity',
    //            'Vitality',
    //            'Energy',
    //            'Leadership',
    //            'MapNumber',
    //            'MapPosX',
    //            'MapPosY',
    //            'PkCount',
    //            'PkLevel',
    //            'ResetCount',
    //            'MasterResetCount',
    //            'Kills',
    //            'Deads',
    //        ])) {
    //            return __('Hidden');
    //        }
    //
    //        return parent::getAttributeValue($key);
    //    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'AccountID', 'memb___id');
    }

    public function entries(): HasMany
    {
        return $this->hasMany(Entry::class, 'Name', 'Name');
    }
}
