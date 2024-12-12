<?php

namespace App\Models\Game;

use App\Enums\Game\GuildMemberStatus;
use App\Models\Concerns\GameConnection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GuildMember extends Model
{
    use GameConnection;
    use HasFactory;

    protected $table = 'GuildMember';

    protected $primaryKey = 'Name';

    protected $keyType = 'string';

    public $timestamps = false;

    public $incrementing = false;

    protected $fillable = [
        'Name',
        'G_Name',
        'G_Level',
        'G_Status',
    ];

    protected $casts = [
        'G_Level' => 'integer',
        'G_Status' => GuildMemberStatus::class,
    ];

    public function guild(): BelongsTo
    {
        return $this->belongsTo(Guild::class, 'G_Name', 'G_Name');
    }

    public function character(): BelongsTo
    {
        return $this->belongsTo(Character::class, 'Name', 'Name');
    }
}
