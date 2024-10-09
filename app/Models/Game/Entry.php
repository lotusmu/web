<?php

namespace App\Models\Game;

use App\Models\Concerns\GameConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Entry extends Model
{
    use GameConnection;

    protected $table = 'EventEntryCount';

    public $incrementing = false;

    public $timestamps = false;

    protected $casts = [
        'Name' => 'string',
        'Type' => 'integer',
        'EntryCount' => 'integer',
    ];

    public function character(): BelongsTo
    {
        return $this->belongsTo(Character::class, 'Name', 'Name');
    }
}
