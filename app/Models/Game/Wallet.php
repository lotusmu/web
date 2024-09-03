<?php

namespace App\Models\Game;

use App\Models\Concerns\GameConnection;
use App\Models\User\Member;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wallet extends Model
{
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
        'zen',
    ];

    protected $casts = [
        'WCoinC' => 'integer',
        'zen' => 'integer',
    ];

    protected function credits(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->WCoinC,
            set: fn ($value) => ['WCoinC' => $value]
        );
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'memb___id', 'AccountID');
    }
}
