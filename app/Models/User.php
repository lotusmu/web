<?php

namespace App\Models;

use App\Interfaces\HasMember;
use App\Services\MemberService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use InvalidArgumentException;

class User extends Authenticatable implements HasMember
{
    use HasFactory, Notifiable;

    protected ?string $rawPassword = null;

    protected $fillable = [
        'username',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function setPasswordAttribute(string $value): void
    {
        $this->rawPassword = $value;
        $this->attributes['password'] = Hash::make($value);
    }

    public function getRawPassword(): ?string
    {
        return $this->rawPassword;
    }

    public static function boot(): void
    {
        parent::boot();

        static::creating(function (User $user) {
            if (! $user->username) {
                throw new InvalidArgumentException('Username is required when creating a new user.');
            }
        });

        static::created(function (User $user) {
            app(MemberService::class)->createMember($user);
        });

        static::updated(function (User $user) {
            app(MemberService::class)->updateMember($user);
        });

        static::deleted(function (User $user) {
            $user->member()->delete();
        });
    }

    public function save(array $options = []): bool
    {
        if ($this->exists && $this->isDirty('username')) {
            throw new InvalidArgumentException('Username cannot be updated after creation.');
        }

        $result = parent::save($options);

        $this->rawPassword = null;

        return $result;
    }

    public function member(): HasOne
    {
        return $this->hasOne(Member::class, 'memb___id', 'username');
    }

    public function characters(): HasMany
    {
        return $this->hasMany(Character::class, 'AccountID', 'username');
    }
}
