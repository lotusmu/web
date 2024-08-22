<?php

namespace App\Models\Game;

use App\Models\Concerns\CreditAccessors;
use App\Models\User\Member;
use App\Models\Utility\GameModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Credit extends GameModel
{
    use CreditAccessors, HasFactory;

    protected $table = 'CashShopData';

    protected $primaryKey = 'AccountID';

    protected $keyType = 'string';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'AccountID',
        'WCoinC',
    ];

    protected $casts = [
        'WCoinC' => 'integer',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'memb___id', 'AccountID');
    }
}
