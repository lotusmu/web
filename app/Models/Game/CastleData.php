<?php

namespace App\Models\Game;

use App\Models\Concerns\GameConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CastleData extends Model
{
    use GameConnection;

    protected $table = 'MuCastle_DATA';

    protected $primaryKey = 'MAP_SVR_GROUP';

    public $timestamps = false;

    protected $fillable = [
        'MAP_SVR_GROUP',
        'SIEGE_START_DATE',
        'SIEGE_END_DATE',
        'SIEGE_GUILDLIST_SETTED',
        'SIEGE_ENDED',
        'CASTLE_OCCUPY',
        'OWNER_GUILD',
        'MONEY',
        'TAX_RATE_CHAOS',
        'TAX_RATE_STORE',
        'TAX_HUNT_ZONE',
    ];

    protected $casts = [
        'SIEGE_START_DATE' => 'datetime',
        'SIEGE_END_DATE' => 'datetime',
        'SIEGE_GUILDLIST_SETTED' => 'boolean',
        'SIEGE_ENDED' => 'boolean',
        'CASTLE_OCCUPY' => 'boolean',
        'MONEY' => 'integer',
        'TAX_RATE_CHAOS' => 'integer',
        'TAX_RATE_STORE' => 'integer',
        'TAX_HUNT_ZONE' => 'integer',
    ];

    public function getServerGroupAttribute()
    {
        return $this->MAP_SVR_GROUP;
    }

    public function getSiegeStartAttribute()
    {
        return $this->SIEGE_START_DATE;
    }

    public function getSiegeEndAttribute()
    {
        return $this->SIEGE_END_DATE;
    }

    public function getIsGuildListSettedAttribute()
    {
        return $this->SIEGE_GUILDLIST_SETTED;
    }

    public function getIsSiegeEndedAttribute()
    {
        return $this->SIEGE_ENDED;
    }

    public function getIsOccupiedAttribute()
    {
        return $this->CASTLE_OCCUPY;
    }

    public function getOwnerGuildNameAttribute()
    {
        return $this->OWNER_GUILD;
    }

    public function getTreasuryAttribute()
    {
        return $this->MONEY;
    }

    public function getGoblinTaxAttribute()
    {
        return $this->TAX_RATE_CHAOS;
    }

    public function getStoreTaxAttribute()
    {
        return $this->TAX_RATE_STORE;
    }

    public function getHuntZoneTaxAttribute()
    {
        return $this->TAX_HUNT_ZONE;
    }

    public function getRemainingTimeAttribute(): string
    {
        $diff = $this->siege_end->diff(now());

        return "{$diff->d}d {$diff->h}h {$diff->i}m";
    }

    public function guild(): BelongsTo
    {
        return $this->belongsTo(Guild::class, 'OWNER_GUILD', 'G_Name');
    }
}
