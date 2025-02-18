<?php

namespace App\Models\Game;

use App\Models\Concerns\GameConnection;
use App\Models\User\Member;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyReward extends Model
{
    use GameConnection;

    protected $table = 'CustomDailyReward';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'AccountID',
        'Day',
        'Month',
    ];

    protected $casts = [
        'AccountID' => 'string',
        'Day' => 'integer',
        'Month' => 'integer',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'AccountID', 'memb___id');
    }
}
