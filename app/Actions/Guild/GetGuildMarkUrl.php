<?php

namespace App\Actions\Guild;

use App\Models\Game\Guild;
use Illuminate\Support\Facades\Storage;

class GetGuildMarkUrl
{
    public function handle(Guild $guild, int $size = 24): string
    {
        // Normalize size to be a multiple of 8
        $size = (int) (round($size / 8) * 8);

        // Cap size at 256
        if ($size > 256) {
            $size = 24;
        }

        $path = "guild_marks/{$guild->G_Name}_{$size}.png";
        $cacheKey = "guild_mark_{$guild->G_Name}_{$size}";
        $fileExistsKey = "guild_mark_exists_{$guild->G_Name}_{$size}";

        // First check if we have a cached URL
        $cachedUrl = cache()->get($cacheKey);

        // If we have a cached URL and either we know the file exists or we verify it exists
        if ($cachedUrl && (cache()->get($fileExistsKey) || Storage::disk('public')->exists($path))) {
            // If we checked the file system and it exists, ensure our exists flag is cached
            if (! cache()->has($fileExistsKey)) {
                cache()->put($fileExistsKey, true, now()->addDay());
            }

            return $cachedUrl;
        }

        // At this point either:
        // 1. We have no cached URL
        // 2. Or we have a cached URL but the file doesn't exist

        // Generate/regenerate the image
        Storage::disk('public')->put($path, $this->generateMarkImage($guild, $size));

        // Get the URL
        $url = Storage::disk('public')->url($path);

        // Cache both the URL and the fact that the file exists
        cache()->put($cacheKey, $url, now()->addWeek());
        cache()->put($fileExistsKey, true, now()->addDay());

        return $url;
    }

    /**
     * Generate a mark image for the guild
     */
    private function generateMarkImage(Guild $guild, int $size): string
    {
        $grid = $this->getMarkArray($guild);

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

    /**
     * Convert binary mark data to a 2D array
     */
    private function getMarkArray(Guild $guild): array
    {
        $mark = bin2hex($guild->G_Mark);
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
}
