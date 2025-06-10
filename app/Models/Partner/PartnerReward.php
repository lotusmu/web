<?php

namespace App\Models\Partner;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartnerReward extends Model
{
    protected $fillable = [
        'partner_id',
        'type',
        'tokens_amount',
        'week_number',
        'year',
        'description',
        'status',
    ];

    protected $casts = [
        'tokens_amount' => 'integer',
        'week_number' => 'integer',
        'year' => 'integer',
    ];

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }
}
