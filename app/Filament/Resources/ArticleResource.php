<?php

namespace App\Filament\Resources;

use App\Actions\User\SendArticleNotificationAction;
use App\Enums\Content\ArticleType;
use App\Filament\Resources\ArticleResource\Pages;
use App\Models\Content\Article;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ArticleResource extends Resource
{
    use Translatable;

    protected static ?string $model = Article::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $modelLabel = 'Article';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->columnSpanFull()
                    ->columns(3)
                    ->schema([
                        Section::make()
                            ->columnSpan(2)
                            ->schema([
                                TextInput::make('title')
                                    ->required(),
                                Textarea::make('excerpt')
                                    ->maxLength(124)
                                    ->required(fn ($get) => $get('type') !== ArticleType::PATCH_NOTE->value)
                                    ->helperText('A short summary of the article for preview'),
                                RichEditor::make('content')
                                    ->required(),
                            ]),
                        Group::make()
                            ->columnSpan(1)
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Select::make('type')
                                            ->options(ArticleType::class)
                                            ->enum(ArticleType::class)
                                            ->live()
                                            ->required(),
                                        Toggle::make('is_published')
                                            ->label('Publish')
                                            ->helperText('Publish article status')
                                            ->helperText('Toggle to publish or archive the article.')
                                            ->columnSpanFull()
                                            ->default(true)
                                            ->inline(false)
                                            ->required(),
                                    ]),
                                Section::make()
                                    ->schema([
                                        FileUpload::make('image')
                                            ->image()
                                            ->imageEditor()
                                            ->directory('article-images')
                                            ->visibility('public')
                                            ->required(fn ($get) => $get('type') !== ArticleType::PATCH_NOTE->value),
                                    ]),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->limit(20),
                Tables\Columns\TextColumn::make('excerpt')
                    ->searchable()
                    ->limit(30),
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\IconColumn::make('is_published')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->label('Published'),
                Tables\Columns\TextColumn::make('created_at')
                    ->sortable()
                    ->dateTime(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('publish')
                    ->visible(function ($record) {
                        return $record->is_published === false;
                    })
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function ($record, SendArticleNotificationAction $notificationAction) {
                        $record->publish();
                        $notificationAction->handle($record);
                    })
                    ->after(function () {
                        Notification::make()->success()->title('Success!')
                            ->body('The article was published and notifications are being sent.')
                            ->duration(3000)
                            ->send();
                    }),
                Tables\Actions\Action::make('archive')
                    ->visible(function ($record) {
                        return $record->is_published === true;
                    })
                    ->icon('heroicon-o-archive-box')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->archive();
                    })
                    ->after(function () {
                        Notification::make()->success()->title('Success!')
                            ->body('The article was archived successfully.')
                            ->duration(2000)
                            ->send();
                    }),
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

    public static function getRecordRouteKeyName(): string
    {
        return 'slug';
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListArticles::route('/'),
            'create' => Pages\CreateArticle::route('/create'),
            'edit' => Pages\EditArticle::route('/{record:slug}/edit'),
        ];
    }
}
