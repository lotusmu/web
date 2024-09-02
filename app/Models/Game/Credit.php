<?php

namespace App\Models\Game;

use App\Models\Concerns\CreditAccessors;
use App\Models\Concerns\GameConnection;
use App\Models\User\Member;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Credit extends Model
{
    use CreditAccessors;
    use GameConnection;
    use HasFactory;

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
