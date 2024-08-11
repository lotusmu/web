<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MemberResource\Pages;
use App\Filament\Resources\MemberResource\RelationManagers;
use App\Models\Member;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MemberResource extends Resource
{
    protected static ?string $model = Member::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('memb__pwd')
                    ->required()
                    ->maxLength(10),
                Forms\Components\TextInput::make('memb_name')
                    ->required()
                    ->maxLength(10),
                Forms\Components\TextInput::make('sno__numb')
                    ->required()
                    ->maxLength(18),
                Forms\Components\TextInput::make('post_code')
                    ->maxLength(6),
                Forms\Components\TextInput::make('addr_info')
                    ->maxLength(50),
                Forms\Components\TextInput::make('addr_deta')
                    ->maxLength(50),
                Forms\Components\TextInput::make('tel__numb')
                    ->tel()
                    ->maxLength(20),
                Forms\Components\TextInput::make('phon_numb')
                    ->maxLength(15),
                Forms\Components\TextInput::make('mail_addr')
                    ->maxLength(50),
                Forms\Components\TextInput::make('fpas_ques')
                    ->maxLength(50),
                Forms\Components\TextInput::make('fpas_answ')
                    ->maxLength(50),
                Forms\Components\TextInput::make('job__code')
                    ->maxLength(2),
                Forms\Components\DateTimePicker::make('appl_days'),
                Forms\Components\DateTimePicker::make('modi_days'),
                Forms\Components\DateTimePicker::make('out__days'),
                Forms\Components\DateTimePicker::make('true_days'),
                Forms\Components\TextInput::make('mail_chek')
                    ->maxLength(1)
                    ->default(0),
                Forms\Components\TextInput::make('bloc_code')
                    ->required()
                    ->maxLength(1),
                Forms\Components\TextInput::make('ctl1_code')
                    ->required()
                    ->maxLength(1),
                Forms\Components\TextInput::make('AccountLevel')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\DateTimePicker::make('AccountExpireDate')
                    ->required(),
                Forms\Components\TextInput::make('Lock')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\DateTimePicker::make('bloc_expire'),
                Forms\Components\TextInput::make('ShowBanner')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('OnlineRewardTime1')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('OnlineRewardTime2')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('OnlineRewardTime3')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('WarehouseCount')
                    ->required()
                    ->numeric()
                    ->default(10),
                Forms\Components\TextInput::make('Admin')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\DateTimePicker::make('last_login'),
                Forms\Components\TextInput::make('activated')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('activation_id')
                    ->maxLength(50),
                Forms\Components\TextInput::make('last_login_ip')
                    ->maxLength(50),
                Forms\Components\TextInput::make('country')
                    ->maxLength(50),
                Forms\Components\TextInput::make('dmn_country')
                    ->maxLength(50),
                Forms\Components\TextInput::make('dmn_partner')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('dmn_twitch_link')
                    ->maxLength(500),
                Forms\Components\TextInput::make('dmn_youtube_link')
                    ->maxLength(500),
                Forms\Components\TextInput::make('dmn_daily_coins')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('dmn_daily_coins_type')
                    ->required()
                    ->numeric()
                    ->default(1),
                Forms\Components\TextInput::make('dmn_purchases_share')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('dmn_share_url')
                    ->maxLength(500),
                Forms\Components\TextInput::make('dmn_linked_to')
                    ->maxLength(100),
                Forms\Components\DateTimePicker::make('dmn_linked_on'),
                Forms\Components\TextInput::make('dmn_current_share')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('dmn_twitch_tags')
                    ->maxLength(500),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('memb___id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('memb__pwd')
                    ->searchable(),
                Tables\Columns\TextColumn::make('memb_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sno__numb')
                    ->searchable(),
                Tables\Columns\TextColumn::make('post_code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('addr_info')
                    ->searchable(),
                Tables\Columns\TextColumn::make('addr_deta')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tel__numb')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phon_numb')
                    ->searchable(),
                Tables\Columns\TextColumn::make('mail_addr')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fpas_ques')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fpas_answ')
                    ->searchable(),
                Tables\Columns\TextColumn::make('job__code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('appl_days')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('modi_days')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('out__days')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('true_days')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('mail_chek')
                    ->searchable(),
                Tables\Columns\TextColumn::make('bloc_code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ctl1_code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('AccountLevel')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('AccountExpireDate')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('Lock')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('bloc_expire')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ShowBanner')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('OnlineRewardTime1')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('OnlineRewardTime2')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('OnlineRewardTime3')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('WarehouseCount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('Admin')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('last_login')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('activated')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('activation_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('last_login_ip')
                    ->searchable(),
                Tables\Columns\TextColumn::make('country')
                    ->searchable(),
                Tables\Columns\TextColumn::make('dmn_country')
                    ->searchable(),
                Tables\Columns\TextColumn::make('dmn_partner')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('dmn_twitch_link')
                    ->searchable(),
                Tables\Columns\TextColumn::make('dmn_youtube_link')
                    ->searchable(),
                Tables\Columns\TextColumn::make('dmn_daily_coins')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('dmn_daily_coins_type')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('dmn_purchases_share')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('dmn_share_url')
                    ->searchable(),
                Tables\Columns\TextColumn::make('dmn_linked_to')
                    ->searchable(),
                Tables\Columns\TextColumn::make('dmn_linked_on')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('dmn_current_share')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('dmn_twitch_tags')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMembers::route('/'),
            'create' => Pages\CreateMember::route('/create'),
            'edit' => Pages\EditMember::route('/{record}/edit'),
        ];
    }
}
