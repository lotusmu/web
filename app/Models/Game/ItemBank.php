<?php

namespace App\Models\Game;

use App\Models\Concerns\GameConnection;
use App\Models\User\Member;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemBank extends Model
{
    use GameConnection;

    protected $table = 'CustomItemBank';

    public $incrementing = false;

    protected $primaryKey = null;

    public $timestamps = false;

    protected $fillable = [
        'AccountID',
        'ItemIndex',
        'ItemLevel',
        'ItemCount',
    ];

    protected function casts(): array
    {
        return [
            'AccountID' => 'string',
            'ItemIndex' => 'integer',
            'ItemLevel' => 'integer',
            'ItemCount' => 'integer',
        ];
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'AccountID', 'memb___id');
    }
}
