<?php

namespace App\Models\User;

use App\Enums\Game\AccountLevel;
use App\Models\Concerns\MemberAccessors;
use App\Models\Game\Character;
use App\Models\Game\Status;
use App\Models\Game\Wallet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Member extends Model
{
    use MemberAccessors;

    protected $connection = 'gamedb_main';

    protected $table = 'MEMB_INFO';

    protected $primaryKey = 'memb___id';

    protected $keyType = 'string';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'memb___id',
        'memb__pwd',
        'memb_name',
        'sno__numb',
        'mail_addr',
        'appl_days',
        'mail_chek',
        'bloc_code',
        'ctl1_code',
        'AccountLevel',
        'AccountExpireDate',
        'tokens',
    ];

    protected $casts = [
        'AccountLevel' => AccountLevel::class,
        'AccountExpireDate' => 'datetime',
        'tokens' => 'integer',
    ];

    public function getHasStealthAttribute(): bool
    {
        $user = User::where('name', $this->memb___id)->first();

        return $user?->hasActiveStealth() ?? false;
    }

    public function hasValidVipSubscription(): bool
    {
        $user = User::where('name', $this->memb___id)->first();

        return $user?->hasValidVipSubscription() ?? false;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'name', 'memb___id');
    }

    public function status(): HasOne
    {
        return $this->hasOne(Status::class, 'memb___id', 'memb___id');
    }

    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class, 'AccountID', 'memb___id');
    }

    public function characters(): HasMany
    {
        return $this->hasMany(Character::class, 'AccountID', 'memb___id');
    }
}
