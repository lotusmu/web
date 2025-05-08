<?php

namespace App\Filament\Resources\MemberResource\RelationManagers;

use App\Models\Game\ItemBank;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ItemBankRelationManager extends RelationManager
{
    protected static string $relationship = 'itemBank';

    protected static ?string $title = 'Item Bank';

    protected static ?string $recordTitleAttribute = 'ItemIndex';

    public function getTableRecordKey($record): string
    {
        return $record->ItemIndex.'_'.$record->ItemLevel;
    }

    protected function getRecordUrl($record): ?string
    {
        return null;
    }

    // Define item groups with their categories
    protected static array $itemGroups = [
        'Basic Jewels' => [
            6159 => [0 => 'Jewel of Chaos'],
            7181 => [0 => 'Jewel of Bless'],
            7182 => [0 => 'Jewel of Soul'],
            7184 => [0 => 'Jewel of Life'],
            7190 => [0 => 'Jewel of Creation'],
            7199 => [0 => 'Jewel of Guardian'],
        ],
        'Harmony Materials' => [
            7210 => [0 => 'Jewel of Harmony'],
            7211 => [0 => 'Lower refining stone'],
            7212 => [0 => 'Higher refining stone'],
        ],
        'Other Items' => [
            7200 => [0 => 'Gemstone'],
            6670 => [
                0 => 'Loch\'s Feather',
                1 => 'Monarch\'s Crest',
            ],
        ],
        'Superb Jewels' => [
            7412 => [0 => 'Jewel of Level'],
            7414 => [0 => 'Jewel Of Luck'],
            7415 => [0 => 'Jewel Of Recovery'],
        ],
    ];

    // Define a method to get flattened item definitions
    protected static function getItemDefinitions(): array
    {
        static $itemDefinitions = [];

        if (empty($itemDefinitions)) {
            foreach (self::$itemGroups as $groupItems) {
                foreach ($groupItems as $itemIndex => $levels) {
                    $itemDefinitions[$itemIndex] = $levels;
                }
            }
        }

        return $itemDefinitions;
    }

    public function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $accountID = $this->getOwnerRecord()->memb___id;

                return $query->where('AccountID', $accountID)
                    ->select(['AccountID', 'ItemIndex', 'ItemLevel', 'ItemCount'])
                    ->orderBy('ItemIndex');
            })
            ->paginated(false)
            ->columns([
                Tables\Columns\TextColumn::make('ItemIndex')
                    ->formatStateUsing(function ($state, $record) {
                        $level = $record->ItemLevel ?? 0;
                        $definitions = self::getItemDefinitions();

                        return $definitions[$state][$level] ?? "Item {$state} (Level {$level})";
                    })
                    ->label('Item'),
                Tables\Columns\TextColumn::make('ItemLevel')
                    ->label('Level')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('ItemCount')
                    ->label('Quantity'),
            ])
            ->headerActions([
                Action::make('manageItems')
                    ->label('Edit Items')
                    ->modalHeading('Manage Item Bank')
                    ->slideOver()
                    ->modalDescription('Update item quantities for this account')
                    ->modalSubmitActionLabel('Save Changes')
                    ->form(function () {
                        $sections = [];

                        // Create sections based on our item groups
                        foreach (self::$itemGroups as $groupName => $groupItems) {
                            $groupFields = [];

                            foreach ($groupItems as $itemIndex => $levels) {
                                foreach ($levels as $level => $name) {
                                    $key = "{$itemIndex}_{$level}";

                                    $groupFields[] = TextInput::make("items.{$key}")
                                        ->label('')
                                        ->prefix($name)
                                        ->numeric()
                                        ->default(function () use ($itemIndex, $level) {
                                            // Get current value for this item
                                            $record = $this->getItemRecord($itemIndex, $level);

                                            return $record ? $record->ItemCount : 0;
                                        })
                                        ->minValue(0);
                                }
                            }

                            $sections[] = Fieldset::make($groupName)
                                ->schema($groupFields)
                                ->columns(3);
                        }

                        return $sections;
                    })
                    ->action(function (array $data) {
                        $this->updateItemBank($data['items'] ?? []);
                    }),
            ]);
    }

    protected function getItemRecord(int $itemIndex, int $level = 0): ?object
    {
        $accountID = $this->getOwnerRecord()->memb___id;

        return $this->getRelationship()
            ->where('AccountID', $accountID)
            ->where('ItemIndex', $itemIndex)
            ->where('ItemLevel', $level)
            ->first();
    }

    protected function updateItemBank(array $items): void
    {
        $accountID = $this->getOwnerRecord()->memb___id;

        foreach ($items as $key => $quantity) {
            [$itemIndex, $level] = explode('_', $key);
            $itemIndex = (int) $itemIndex;
            $level = (int) $level;
            $quantity = (int) $quantity;

            // Check if record exists
            $exists = ItemBank::where('AccountID', $accountID)
                ->where('ItemIndex', $itemIndex)
                ->where('ItemLevel', $level)
                ->exists();

            if ($exists) {
                // Update existing record
                ItemBank::where('AccountID', $accountID)
                    ->where('ItemIndex', $itemIndex)
                    ->where('ItemLevel', $level)
                    ->update(['ItemCount' => $quantity]);
            } elseif ($quantity > 0) {
                // Insert new record
                ItemBank::insert([
                    'AccountID' => $accountID,
                    'ItemIndex' => $itemIndex,
                    'ItemLevel' => $level,
                    'ItemCount' => $quantity,
                ]);
            }
        }

        Notification::make()
            ->title('Items Updated')
            ->body('Item bank has been successfully updated')
            ->success()
            ->send();
    }
}
