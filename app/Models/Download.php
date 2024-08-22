<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Download extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'storage_type',
        'local_file',
        'external_url',
    ];

    protected $casts = [
        'id' => 'string',
        'storage_type' => 'string',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if (! $model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    public function getFileUrlAttribute(): string
    {
        if ($this->storage_type === 'local') {
            return $this->local_file ? Storage::url($this->local_file) : '';
        }

        return $this->external_url ?? '';
    }
}
