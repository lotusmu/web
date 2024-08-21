<?php

namespace App\Models;

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'ticket_category_id',
        'user_id',
    ];

    protected $casts = [
        'status' => TicketStatus::class,
        'priority' => TicketPriority::class,
    ];

    public static function getForm(): array
    {
        return [
            Section::make('Content')
                ->columnSpan(2)
                ->schema([
                    TextInput::make('title')
                        ->required()
                        ->maxLength(255),
                    RichEditor::make('description')
                        ->required(),
                ]),
            Section::make('Status')
                ->columnSpan(1)
                ->schema([
                    Select::make('ticket_category_id')
                        ->label('Category')
                        ->relationship('category', 'name')
                        ->required(),
                    Select::make('status')
                        ->options(TicketStatus::class)
                        ->enum(TicketStatus::class)
                        ->required(),
                    Select::make('priority')
                        ->options(TicketPriority::class)
                        ->enum(TicketPriority::class)
                        ->required(),
                    TextInput::make('user_id')
                        ->required(),
                ]),
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(TicketCategory::class, 'ticket_category_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function replies(): HasMany
    {
        return $this->hasMany(TicketReply::class);
    }
}
