<?php

namespace App\Models\User;

use App\Actions\Member\SyncMember;
use App\Enums\Game\AccountLevel;
use App\Enums\Game\GuildMemberStatus;
use App\Interfaces\HasMember;
use App\Models\Concerns\ManagesResources;
use App\Models\Game\CastleData;
use App\Models\Game\Status;
use App\Models\Ticket\Ticket;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Flux\Flux;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use InvalidArgumentException;
use Laravel\Cashier\Billable;
use Solutionforest\FilamentEmail2fa\Interfaces\RequireTwoFALogin;
use Solutionforest\FilamentEmail2fa\Trait\HasTwoFALogin;

class User extends Authenticatable implements FilamentUser, HasMember, MustVerifyEmail, RequireTwoFALogin
{
    use Billable;
    use HasFactory;
    use HasTwoFALogin;
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
        return $this->is_admin;
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
        $isOnline = $this->status?->ConnectStat ?? false;

        if ($isOnline) {
            Flux::toast(
                text: __('Please logout from the game to proceed.'),
                heading: __('Action Required'),
                variant: 'warning'
            );
        }

        return $isOnline;
    }

    public function isCastleLord(?CastleData $castle): bool
    {
        if (! $castle) {
            return false;
        }

        return $this->member->characters()
            ->whereHas('guildMember', function ($query) use ($castle) {
                $query->where('G_Name', $castle->OWNER_GUILD)
                    ->where('G_Status', GuildMemberStatus::GuildMaster);
            })
            ->exists();
    }

    public function hasActiveStealth(): bool
    {
        return $this->stealth?->isActive() ?? false;
    }

    public function hasValidVipSubscription(): bool
    {
        return $this->member->AccountLevel !== AccountLevel::Regular
            && now()->lessThan($this->member->AccountExpireDate);
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

    public function stealth(): HasOne
    {
        return $this->hasOne(Stealth::class);
    }
}
