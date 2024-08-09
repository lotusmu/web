<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected ?string $plainPassword;

    protected $fillable = [
        'username',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function setPasswordAttribute($value): void
    {
        $this->plainPassword = $value;
        $this->attributes['password'] = bcrypt($value);
    }

    protected static function booted(): void
    {
        static::created(function ($user) {
            $user->createGameUser();
        });
    }

    protected function createGameUser(): void
    {
        $this->gameUser()->create([
            'memb___id' => $this->username,
            'memb__pwd' => $this->plainPassword,
            'memb_name' => $this->username,
            'mail_addr' => $this->email,
            'sno__numb' => 1111111111111,
            'appl_days' => 0,
            'mail_chek' => 0,
            'bloc_code' => 0,
            'ctl1_code' => 0,
            'AccountLevel' => 0,
            'AccountExpireDate' => now(),
        ]);

        $this->plainPassword = null;
    }

    public function gameUser(): HasOne
    {
        return $this->hasOne(Game\User::class, 'memb___id', 'username');
    }
}
