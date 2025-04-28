<?php

namespace App\Models\Game;

use App\Actions\Guild\GetGuildMarkUrl;
use App\Models\Concerns\GameConnection;
use App\Models\Game\Ranking\Event;
use App\Models\Game\Ranking\Hunter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;

class Guild extends Model
{
    use GameConnection;
    use HasFactory;

    protected $table = 'Guild';

    protected $primaryKey = 'G_Name';

    protected $keyType = 'string';

    public $timestamps = false;

    public $incrementing = false;

    protected $fillable = [
        'G_Name',
        'G_Mark',
        'G_Score',
        'G_Master',
        'CS_Wins',
    ];

    protected $casts = [
        'CS_Wins' => 'integer',
    ];

    public function getMarkUrl(int $size = 24): string
    {
        return app(GetGuildMarkUrl::class)->handle($this, $size);
    }

    public static function cleanupOldMarkImages(int $days = 30): void
    {
        $disk = Storage::disk('public');
        $files = $disk->files('guild_marks');

        foreach ($files as $file) {
            if ($disk->lastModified($file) < now()->subDays($days)->timestamp) {
                $disk->delete($file);
            }
        }
    }

    public function members(): HasMany
    {
        return $this->hasMany(GuildMember::class, 'G_Name', 'G_Name');
    }

    public function characters(): HasManyThrough
    {
        return $this->hasManyThrough(
            Character::class,
            GuildMember::class,
            'G_Name',
            'Name',
            'G_Name',
            'Name'
        );
    }

    public function eventScores(): HasManyThrough
    {
        return $this->hasManyThrough(
            Event::class,
            GuildMember::class,
            'G_Name',
            'Name',
            'G_Name',
            'Name'
        );
    }

    public function hunterScores(): HasManyThrough
    {
        return $this->hasManyThrough(
            Hunter::class,
            GuildMember::class,
            'G_Name',
            'Name',
            'G_Name',
            'Name'
        );
    }

    public function master(): BelongsTo
    {
        return $this->belongsTo(Character::class, 'G_Master', 'Name');
    }

    public function castle(): HasOne
    {
        return $this->hasOne(CastleData::class, 'OWNER_GUILD', 'G_Name');
    }
}
