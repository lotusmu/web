<?php

namespace App\Models\Game;

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
        if ($size > 256) {
            $size = 24;
        }

        $cacheKey = "guild_mark_{$this->G_Name}_{$size}";

        return cache()->remember($cacheKey, now()->addWeek(), function () use ($size) {
            $path = "guild_marks/{$this->G_Name}_{$size}.png";

            if (! Storage::disk('public')->exists($path)) {
                Storage::disk('public')->put($path, $this->generateMarkImage($size));
            }

            return Storage::disk('public')->url($path);
        });
    }

    private function getMarkArray(): array
    {
        $mark = bin2hex($this->G_Mark);
        $grid = [];

        for ($y = 0; $y < 8; $y++) {
            $row = [];
            for ($x = 0; $x < 8; $x++) {
                $pos = $y * 8 + $x;
                $row[] = substr($mark, $pos, 1);
            }
            $grid[] = $row;
        }

        return $grid;
    }

    private function generateMarkImage(int $size): string
    {
        $grid = $this->getMarkArray();
        $pixelSize = $size / 8;

        $image = imagecreatetruecolor($size, $size);

        // Color mapping as per original code
        $colors = [
            '0' => '#ffffff',
            '1' => '#000000',
            '2' => '#8c8a8d',
            '3' => '#ffffff',
            '4' => '#fe0000',
            '5' => '#ff8a00',
            '6' => '#ffff00',
            '7' => '#8cff01',
            '8' => '#00ff00',
            '9' => '#01ff8d',
            'A' => '#00ffff',
            'B' => '#008aff',
            'C' => '#0000fe',
            'D' => '#8c00ff',
            'E' => '#8c00ff',
            'F' => '#ff008c',
        ];

        // Enable alpha blending
        imagealphablending($image, true);
        imagesavealpha($image, true);

        for ($y = 0; $y < 8; $y++) {
            for ($x = 0; $x < 8; $x++) {
                $colorKey = $grid[$y][$x];
                $hexColor = $colors[$colorKey] ?? '#ffffff';

                // Convert hex to RGB
                $rgb = sscanf($hexColor, '#%02x%02x%02x');
                $color = imagecolorallocate($image, $rgb[0], $rgb[1], $rgb[2]);

                // Fill the square
                imagefilledrectangle(
                    $image,
                    $x * $pixelSize,
                    $y * $pixelSize,
                    ($x + 1) * $pixelSize - 1,
                    ($y + 1) * $pixelSize - 1,
                    $color
                );
            }
        }

        ob_start();
        imagepng($image);
        $imageContent = ob_get_clean();

        imagedestroy($image);

        return $imageContent;
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

        cache()->tags(['guild_marks'])->flush();
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
