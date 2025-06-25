<?php

namespace App\Actions\Partner;

use App\Models\Partner\PartnerBrandAsset;
use App\Models\User\User;
use Exception;
use Flux;
use Illuminate\Cache\RateLimiter;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadBrandAssetsAction
{
    private const MAX_ATTEMPTS = 5;

    private const DECAY_MINUTES = 60;

    public function __construct(
        private RateLimiter $rateLimiter
    ) {}

    public function handle(int $assetId, User $user): StreamedResponse
    {
        if (! $this->ensureIsNotRateLimited($user->id)) {
            throw new Exception('Rate limit exceeded.');
        }

        // Find and validate asset
        $asset = PartnerBrandAsset::findOrFail($assetId);

        if (! $asset->is_active) {
            throw new Exception('This asset is no longer available.');
        }

        if (! $asset->exists()) {
            throw new Exception('Asset file not found.');
        }

        // Hit the rate limiter
        $this->rateLimiter->hit($this->throttleKey($user->id), self::DECAY_MINUTES * 60);

        // Use stored filename or fallback to asset name
        $filename = $asset->filename ?: $asset->name.'.zip';

        return Storage::disk('public')->download(
            $asset->path,
            $filename,
            [
                'Content-Type' => 'application/octet-stream',
                'Content-Disposition' => 'attachment; filename="'.$filename.'"',
            ]
        );
    }

    private function ensureIsNotRateLimited(int $userId): bool
    {
        if (! $this->rateLimiter->tooManyAttempts($this->throttleKey($userId), self::MAX_ATTEMPTS)) {
            return true;
        }

        $seconds = $this->rateLimiter->availableIn($this->throttleKey($userId));

        Flux::toast(
            text: __('Download limit: :max files per hour. Please wait :seconds seconds.', [
                'max' => self::MAX_ATTEMPTS,
                'seconds' => $seconds,
            ]),
            heading: __('Too Many Downloads'),
            variant: 'danger'
        );

        return false;
    }

    private function throttleKey(int $userId): string
    {
        return "brand_asset_downloads:{$userId}";
    }
}
