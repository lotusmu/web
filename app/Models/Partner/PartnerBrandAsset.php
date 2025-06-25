<?php

namespace App\Models\Partner;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PartnerBrandAsset extends Model
{
    protected $fillable = [
        'name',
        'path',
        'filename',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getDownloadUrl(): string
    {
        return route('partners.brand-assets.download', $this);
    }

    public function exists(): bool
    {
        return Storage::disk('public')->exists($this->path);
    }
}
