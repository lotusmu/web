<?php

namespace App\Models\User;

use App\Actions\SyncMember;
use App\Interfaces\HasMember;
use App\Models\Concerns\ManagesResources;
use App\Models\Game\Status;
use App\Models\Ticket\Ticket;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Flux\Flux;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use InvalidArgumentException;

class User extends Authenticatable implements FilamentUser, HasMember
{
    use HasFactory;
    use ManagesResources;
    use Notifiable;

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

        static::created(static::syncMember(...));
        static::updated(static::syncMember(...));
    }

    protected static function syncMember(User $user): void
    {
        app(SyncMember::class)->handle($user);
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

    public function verify(): void
    {
        $this->email_verified_at = Carbon::now();

        $this->save();

        activity('auth')
            ->performedOn($this)
            ->log('Email address verified by system.');
    }

    public function isOnline(): bool
    {
        $isOnline = $this->status->ConnectStat === true;

        if ($isOnline) {
            Flux::toast(
                variant: 'warning',
                heading: __('Action Required'),
                text: __('Please logout from the game to proceed.')
            );
        }

        return $isOnline;
    }

    public function member(): HasOne
    {
        return $this->hasOne(Member::class, 'memb___id', 'name');
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function status(): HasOne
    {
        return $this->hasOne(Status::class, 'memb___id', 'name');
    }
}
