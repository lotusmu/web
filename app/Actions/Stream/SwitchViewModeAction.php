<?php

namespace App\Actions\Stream;

use InvalidArgumentException;

class SwitchViewModeAction
{
    public function handle(string $viewMode): string
    {
        // Validate view mode
        if (! in_array($viewMode, ['grid', 'featured'])) {
            throw new InvalidArgumentException('Invalid view mode. Must be "grid" or "featured".');
        }

        // Save preference to cookie (30 days)
        cookie()->queue('streams_view_mode', $viewMode, 60 * 24 * 30);

        return $viewMode;
    }
}
