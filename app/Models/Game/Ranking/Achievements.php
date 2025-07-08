<?php

namespace App\Models\Game\Ranking;

use App\Models\Concerns\GameConnection;
use App\Models\Game\Character;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Achievements extends Model
{
    use GameConnection;

    protected $table = 'CustomAchievementsUser';

    protected $primaryKey = 'Name';

    protected $keyType = 'string';

    public $timestamps = false;

    public $incrementing = false;

    protected $fillable = [
        'Name',
        'AchievementsPoints',
        'IncHP',
        'IncExperienceRate',
        'IncDefenseBase',
        'IncFullReflectRate',
        'IncCriticalDamageRate',
        'IncExcellentDamageRate',
        'IncDoubleDamageRate',
        'IncTripleDamageRate',
        'IncIgnoreDefenseRate',
        'ResistCriticalDamageRate',
        'ResistExcellentDamageRate',
        'ResistDoubleDamageRate',
        'ResistTripleDamageRate',
        'ResistIgnoreDefenseRate',
        'IncWeaponDurabilityRate',
        'IncArmorDurabilityRate',
        'IncGuardianDurabilityRate',
        'IncOffensiveFullHpRestoreRate',
        'IncDefensiveFullHpRestoreRate1',
        'ResistStunRate',
    ];

    protected $casts = [
        'AchievementsPoints' => 'integer',
        'IncHP' => 'integer',
        'IncExperienceRate' => 'integer',
        'IncDefenseBase' => 'integer',
        'IncFullReflectRate' => 'integer',
        'IncCriticalDamageRate' => 'integer',
        'IncExcellentDamageRate' => 'integer',
        'IncDoubleDamageRate' => 'integer',
        'IncTripleDamageRate' => 'integer',
        'IncIgnoreDefenseRate' => 'integer',
        'ResistCriticalDamageRate' => 'integer',
        'ResistExcellentDamageRate' => 'integer',
        'ResistDoubleDamageRate' => 'integer',
        'ResistTripleDamageRate' => 'integer',
        'ResistIgnoreDefenseRate' => 'integer',
        'IncWeaponDurabilityRate' => 'integer',
        'IncArmorDurabilityRate' => 'integer',
        'IncGuardianDurabilityRate' => 'integer',
        'IncOffensiveFullHpRestoreRate' => 'integer',
        'IncDefensiveFullHpRestoreRate1' => 'integer',
        'ResistStunRate' => 'integer',
    ];

    public function getTotalAchievementPointsAttribute(): int
    {
        return collect($this->attributes)
            ->except(['Name'])
            ->sum();
    }

    public function character(): BelongsTo
    {
        return $this->belongsTo(Character::class, 'Name', 'Name');
    }
}
