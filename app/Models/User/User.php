<?php

namespace App\Models\User;

use App\Interfaces\HasMember;
use App\Services\MemberService;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use InvalidArgumentException;

class User extends Authenticatable implements FilamentUser, HasMember
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

    public static function getForm(): array
    {
        return [
            Section::make('User Login Details')
                ->description('View and update user account information, including email and password.')
                ->aside()
                ->columns(2)
                ->schema([
                    Placeholder::make('name')
                        ->label('Username')
                        ->content(fn ($record) => $record->name),
                    Placeholder::make('email_verified_at')
                        ->label('Email Verified At')
                        ->content(function ($record) {
                            if ($record->email_verified_at) {
                                return Carbon::parse($record->email_verified_at)->format('M d, Y H:i:s');
                            }

                            return 'Not verified';
                        }),
                    TextInput::make('email')
                        ->columnSpanFull()
                        ->email()
                        ->required()
                        ->maxLength(255),
                    Checkbox::make('change_password')
                        ->label('Change password')
                        ->columnSpanFull()
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

    public function setPasswordAttribute(string $value): void
    {
        $this->rawPassword = $value;
        $this->attributes['password'] = Hash::make($value);
    }

    public function getRawPassword(): ?string
    {
        return $this->rawPassword;
    }

    public function verify(): void
    {
        $this->email_verified_at = Carbon::now();

        $this->save();
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

    public function canAccessPanel(Panel $panel): bool
    {
        return true; //$this->hasRole('admin');
    }

    public function member(): HasOne
    {
        return $this->hasOne(Member::class, 'memb___id', 'name');
    }
}
