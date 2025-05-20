<?php

namespace App\Models\Partner;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartnerReward extends Model
{
    protected $fillable = [
        'partner_id',
        'type',
        'amount',
        'week_number',
        'year',
        'description',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'week_number' => 'integer',
        'year' => 'integer',
    ];

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }
}
