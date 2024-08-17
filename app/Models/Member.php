<?php

namespace App\Models;

use App\Enums\AccountLevel;
use App\Models\Traits\MemberAccessors;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Member extends Model
{
    use MemberAccessors;

    public $incrementing = false;

    public $timestamps = false;

    protected $connection = 'gamedb_main';

    protected $table = 'MEMB_INFO';

    protected $primaryKey = 'memb___id';

    protected $keyType = 'string';

    protected $fillable = [
        'memb___id',
        'memb__pwd',
        'memb_name',
        'sno__numb',
        'mail_addr',
        'appl_days',
        'mail_chek',
        'bloc_code',
        'ctl1_code',
        'AccountLevel',
        'AccountExpireDate',
    ];

    protected $casts = [
        'AccountLevel' => AccountLevel::class,
    ];

    public static function getForm(): array
    {
        return [
            Section::make('User Details')
                ->description('User Details can be changed from User Logins.')
                ->aside()
                ->columns(2)
                ->schema([
                    Placeholder::make('memb___id')
                        ->label('Username')
                        ->content(fn ($record) => $record->memb___id),

                    Placeholder::make('mail_addr')
                        ->label('Email')
                        ->content(fn ($record) => $record->mail_addr),

                    Placeholder::make('memb__pwd')
                        ->label('Password')
                        ->content(fn ($record) => $record->memb__pwd),
                ]),
            Section::make('Account Level')
                ->description('Change the account level and its expiration date.')
                ->aside()
                ->columns(2)
                ->schema([
                    Select::make('AccountLevel')
                        ->label('VIP Package')
                        ->options(AccountLevel::class)
                        ->enum(AccountLevel::class)
                        ->required(),
                    DateTimePicker::make('AccountExpireDate')
                        ->label('Expiration Date')
                        ->required(),
                ]),
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'memb___id', 'name');
    }

    public function characters(): HasMany
    {
        return $this->hasMany(Character::class, 'AccountID', 'memb___id');
    }
}
