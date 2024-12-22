<?php

namespace App\Models\Utility;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CastlePrizeDistribution extends Model
{
    protected $fillable = [
        'castle_prize_id',
        'guild_name',
        'total_members',
        'distributed_amount',
        'amount_per_member',
    ];

    protected $casts = [
        'total_members' => 'integer',
        'distributed_amount' => 'integer',
        'amount_per_member' => 'integer',
    ];

    public function prize(): BelongsTo
    {
        return $this->belongsTo(CastlePrize::class);
    }
}
