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
        $size = (int) (round($size / 8) * 8);

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

        $pixelSize = (int) floor($size / 8);  // Force integer pixel size

        $image = imagecreatetruecolor($size, $size);

        $colors = [
            '0' => '#ffffff', // Transparent (skipped in rendering)
            '1' => '#000000', // Black
            '2' => '#8c8a8d', // Gray
            '3' => '#ffffff', // White
            '4' => '#fe0000', // Pure Red
            '5' => '#ff8a00', // Orange
            '6' => '#ffff00', // Yellow
            '7' => '#8cff01', // Lime Green
            '8' => '#00ff00', // Pure Green
            '9' => '#01ff8d', // Spring Green
            'A' => '#00ffff', // Cyan
            'B' => '#008aff', // Azure Blue
            'C' => '#0000fe', // Pure Blue
            'D' => '#8c00ff', // Purple
            'E' => '#ff00ff', // Magenta
            'F' => '#ff008c', // Pink
        ];

        // Set transparent background first
        $transparent = imagecolorallocatealpha($image, 0, 0, 0, 127);
        imagefill($image, 0, 0, $transparent);

        imagealphablending($image, true);
        imagesavealpha($image, true);

        for ($y = 0; $y < 8; $y++) {
            for ($x = 0; $x < 8; $x++) {
                $colorKey = strtoupper($grid[$y][$x]); // Ensure uppercase for hex values

                if ($colorKey === '0') {
                    continue;
                }

                $hexColor = $colors[$colorKey] ?? '#ffffff';
                [$r, $g, $b] = sscanf($hexColor, '#%02x%02x%02x');
                $color = imagecolorallocate($image, $r, $g, $b);

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
