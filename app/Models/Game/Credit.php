<?php

namespace App\Models\Game;

use App\Models\Concerns\CreditAccessors;
use App\Models\User\Member;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Credit extends Model
{
    use CreditAccessors, HasFactory;

    public $incrementing = false;

    public $timestamps = false;

    protected $connection = 'gamedb_main';

    protected $table = 'CashShopData';

    protected $primaryKey = 'AccountID';

    protected $keyType = 'string';

    protected $fillable = [
        'AccountID',
        'WCoinC',
        //        'WCoinP',
        //        'GoblinPoint',
    ];

    protected $casts = [
        'WCoinC' => 'integer',
        //        'WCoinP' => 'integer',
        //        'GoblinPoint' => 'integer',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'memb___id', 'AccountID');
    }
}
