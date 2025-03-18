<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Crypt;

class TemporaryPassword extends Model
{
    protected $fillable = ['user_id', 'password'];

    protected function setPasswordAttribute($value): void
    {
        $this->attributes['password'] = Crypt::encryptString($value);
    }

    protected function getPasswordAttribute($value): string
    {
        return Crypt::decryptString($value);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
