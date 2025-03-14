<?php

namespace App\Models\Game;

use App\Enums\Game\CharacterClass;
use App\Enums\Game\CharacterStatus;
use App\Enums\Game\Map;
use App\Enums\Game\PkLevel;
use App\Models\Concerns\GameConnection;
use App\Models\Concerns\HandlesStealthVisibility;
use App\Models\Concerns\IsBannable;
use App\Models\Game\Ranking\Event;
use App\Models\Game\Ranking\EventWeekly;
use App\Models\Game\Ranking\Hunter;
use App\Models\Game\Ranking\HunterWeekly;
use App\Models\Game\Ranking\Quest;
use App\Models\User\Member;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Character extends Model
{
    use GameConnection;
    use HandlesStealthVisibility;
    use HasFactory;
    use IsBannable;

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
        'ban_reason',
        'ResetCount',
        'MasterResetCount',
        'ExtInventory',
        'Kills',
        'Deads',
        'bloc_expire',
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
        'CtlCode' => CharacterStatus::class,
        'ResetCount' => 'integer',
        'MasterResetCount' => 'integer',
        'Kills' => 'integer',
        'Deads' => 'integer',
        'HunterScore' => 'integer',
        'HunterScoreWeekly' => 'integer',
        'EventScore' => 'integer',
        'EventScoreWeekly' => 'integer',
        'HofWins' => 'integer',
        'bloc_expire' => 'datetime',
    ];

    protected static function getFillableFields(): array
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

    public function getQuestCountAttribute(): int
    {
        return $this->quest?->Quest ?? 0;
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'AccountID', 'memb___id');
    }

    public function entries(): HasMany
    {
        return $this->hasMany(Entry::class, 'Name', 'Name');
    }

    public function guildMember(): HasOne
    {
        return $this->hasOne(GuildMember::class, 'Name', 'Name');
    }

    public function quest(): HasOne
    {
        return $this->hasOne(Quest::class, 'Name', 'Name');
    }

    public function hunterScores(): HasMany
    {
        return $this->hasMany(Hunter::class, 'Name', 'Name');
    }

    public function weeklyHunterScores(): HasMany
    {
        return $this->hasMany(HunterWeekly::class, 'Name', 'Name');
    }

    public function eventScores(): HasMany
    {
        return $this->hasMany(Event::class, 'Name', 'Name');
    }

    public function weeklyEventScores(): HasMany
    {
        return $this->hasMany(EventWeekly::class, 'Name', 'Name');
    }
}
