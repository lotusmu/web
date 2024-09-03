<?php

namespace App\Models\User;

use App\Enums\AccountLevel;
use App\Models\Concerns\MemberAccessors;
use App\Models\Game\Character;
use App\Models\Game\Wallet;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Support\RawJs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
        'tokens',
    ];

    protected $casts = [
        'AccountLevel' => AccountLevel::class,
        'tokens' => 'integer',
    ];

    public static function getForm(): array
    {
        return [
            Section::make('User Details')
                ->description('User Details can be changed from User Logins.')
                ->aside()
                ->columns(3)
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
            Section::make('Resources')
                ->description('Adjust member\'s balances.')
                ->aside()
                ->columns(2)
                ->schema([
                    TextInput::make('tokens')
                        ->numeric()
                        ->mask(RawJs::make('$money($input)'))
                        ->stripCharacters(',')
                        ->default(0)
                        ->minValue(0)
                        ->required(),
                    Group::make()
                        ->relationship('wallet')
                        ->schema([
                            TextInput::make('WCoinC')
                                ->label('Credits')
                                ->numeric()
                                ->mask(RawJs::make('$money($input)'))
                                ->stripCharacters(',')
                                ->default(0)
                                ->minValue(0)
                                ->required(),
                        ]),
                    Group::make()
                        ->relationship('wallet')
                        ->columnSpanFull()
                        ->schema([
                            TextInput::make('zen')
                                ->numeric()
                                ->mask(RawJs::make('$money($input)'))
                                ->stripCharacters(',')
                                ->default(0)
                                ->minValue(0)
                                ->required(),
                        ]),
                ]),
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'name', 'memb___id');
    }

    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class, 'AccountID', 'memb___id');
    }

    public function characters(): HasMany
    {
        return $this->hasMany(Character::class, 'AccountID', 'memb___id');
    }
}
