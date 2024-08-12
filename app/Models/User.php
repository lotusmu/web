<?php

namespace App\Models;

use App\Interfaces\HasMember;
use App\Services\MemberService;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
        'name',
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
            if (! $user->name) {
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
        if ($this->exists && $this->isDirty('name')) {
            throw new InvalidArgumentException('Username cannot be updated after creation.');
        }

        $result = parent::save($options);

        $this->rawPassword = null;

        return $result;
    }

    public static function getForm(): array
    {
        return [
            Section::make('User Login Details')
                ->schema([
                    TextInput::make('name')
                        ->label('Username')
                        ->disabled(),
                    TextInput::make('email')
                        ->email()
                        ->required()
                        ->maxLength(255),
                    DateTimePicker::make('email_verified_at')
                        ->label('Email Verified At')
                        ->disabled()
                        ->dehydrated(false),
                    Checkbox::make('change_password')
                        ->label('Change password')
                        ->live()
                        ->afterStateUpdated(function (Set $set, $state) {
                            if (! $state) {
                                $set('password', null);
                                $set('password_confirmation', null);
                            }
                        }),

                    TextInput::make('password')
                        ->password()
                        ->required(fn (Get $get): bool => (bool) $get('change_password'))
                        ->maxLength(255)
                        ->visible(fn (Get $get): bool => (bool) $get('change_password'))
                        ->confirmed(),

                    TextInput::make('password_confirmation')
                        ->password()
                        ->required(fn (Get $get): bool => (bool) $get('change_password'))
                        ->maxLength(255)
                        ->visible(fn (Get $get): bool => (bool) $get('change_password'))
                        ->dehydrated(false),
                ]),
        ];
    }

    public function member(): HasOne
    {
        return $this->hasOne(Member::class, 'memb___id', 'name');
    }
}
