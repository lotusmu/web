<?php

namespace App\Actions\Member;

use App\Enums\Game\BankItem;
use App\Models\Game\ItemBank;

class UpdateItemBank
{
    protected string $accountID;

    protected array $itemUpdates = [];

    protected bool $incrementMode = false;

    /**
     * Updates a player's item bank.
     *
     * This class provides a fluent API for adding, updating, or removing items from a player's item bank.
     * It handles both increment mode (adding/subtracting quantities) and replace mode (setting exact quantities).
     *
     * Basic usage examples:
     *
     * 1. Add items to a player's inventory (increment mode):
     *    ```
     *    (new UpdateItemBank)
     *        ->forAccount($accountID)
     *        ->addItem(BankItem::JEWEL_OF_BLESS, 5)
     *        ->addItem(BankItem::MONARCHS_CREST, 2)
     *        ->asIncrement()  // This is actually the default
     *        ->execute();
     *    ```
     *
     * 2. Set exact quantities for items (replace mode):
     *    ```
     *    (new UpdateItemBank)
     *        ->forAccount($accountID)
     *        ->addItem(BankItem::JEWEL_OF_BLESS, 10)
     *        ->asReplace()
     *        ->execute();
     *    ```
     *
     * 3. Remove items by using negative values in increment mode:
     *    ```
     *    (new UpdateItemBank)
     *        ->forAccount($accountID)
     *        ->addItem(BankItem::JEWEL_OF_BLESS, -3)  // Remove 3 jewels
     *        ->asIncrement()
     *        ->execute();
     *    ```
     *
     * 4. Set multiple item quantities in one operation:
     *    ```
     *    (new UpdateItemBank)
     *        ->forAccount($accountID)
     *        ->addItem(BankItem::JEWEL_OF_BLESS, 10)
     *        ->addItem(BankItem::JEWEL_OF_SOUL, 5)
     *        ->addItem(BankItem::JEWEL_OF_CHAOS, 20)
     *        ->asReplace()
     *        ->execute();
     *    ```
     *
     * 5. Using with integer values instead of enum:
     *    ```
     *    (new UpdateItemBank)
     *        ->forAccount($accountID)
     *        ->addItem(7181, 5)  // 7181 is JEWEL_OF_BLESS
     *        ->execute();
     *    ```
     */

    /**
     * Set the account ID for this operation
     *
     * @param  string  $accountID  The account ID to update items for
     */
    public function forAccount(string $accountID): self
    {
        $this->accountID = $accountID;

        return $this;
    }

    /**
     * Add an item update using either enum, integer ID, or array representation for special cases
     *
     * @param  BankItem|int|array  $item  The item to update (enum, ID, or special array format)
     * @param  int  $count  The quantity to add/set (can be negative for removal in increment mode)
     * @param  int|null  $level  Optional item level (default is 0 or from enum)
     */
    public function addItem(BankItem|int|array $item, int $count, ?int $level = null): self
    {
        // Handle special cases like BankItem::MONARCHS_CREST()
        if (is_array($item) && isset($item['item']) && isset($item['level'])) {
            $itemIndex = $item['item'] instanceof BankItem ? $item['item']->value : (int) $item['item'];
            $itemLevel = (int) $item['level'];
        } else {
            $itemIndex = $item instanceof BankItem ? $item->value : (int) $item;
            $itemLevel = $level ?? ($item instanceof BankItem ? $item->getLevel() : 0);
        }

        $this->itemUpdates[] = [
            'ItemIndex' => $itemIndex,
            'ItemLevel' => $itemLevel,
            'ItemCount' => $count,
        ];

        return $this;
    }

    /**
     * Set to increment mode (add to existing quantity)
     * This is the default mode, so only needed if previously set to replace mode.
     *
     * @param  bool  $isIncrement  Whether to use increment mode
     */
    public function asIncrement(bool $isIncrement = true): self
    {
        $this->incrementMode = $isIncrement;

        return $this;
    }

    /**
     * Set to replace mode (overwrite existing quantity)
     *
     * @param  bool  $isReplace  Whether to use replace mode
     */
    public function asReplace(bool $isReplace = true): self
    {
        return $this->asIncrement(! $isReplace);
    }

    /**
     * Execute the update operation
     *
     * @return array List of modified items with their new quantities
     */
    public function execute(): array
    {
        if (empty($this->accountID) || empty($this->itemUpdates)) {
            return [];
        }

        $modifiedItems = [];

        foreach ($this->itemUpdates as $item) {
            $itemIndex = $item['ItemIndex'];
            $itemLevel = $item['ItemLevel'];
            $quantity = $item['ItemCount'];

            // Check if record exists directly
            $exists = ItemBank::where('AccountID', $this->accountID)
                ->where('ItemIndex', $itemIndex)
                ->where('ItemLevel', $itemLevel)
                ->exists();

            $newQuantity = $quantity;

            if ($exists) {
                // Get current quantity if needed for increment mode
                if ($this->incrementMode) {
                    $currentQuantity = ItemBank::where('AccountID', $this->accountID)
                        ->where('ItemIndex', $itemIndex)
                        ->where('ItemLevel', $itemLevel)
                        ->value('ItemCount') ?? 0;

                    $newQuantity = max(0, $currentQuantity + $quantity);
                }

                // Update existing record
                ItemBank::where('AccountID', $this->accountID)
                    ->where('ItemIndex', $itemIndex)
                    ->where('ItemLevel', $itemLevel)
                    ->update(['ItemCount' => $newQuantity]);
            } elseif ($newQuantity > 0) {
                // Insert new record
                ItemBank::insert([
                    'AccountID' => $this->accountID,
                    'ItemIndex' => $itemIndex,
                    'ItemLevel' => $itemLevel,
                    'ItemCount' => $newQuantity,
                ]);
            }

            $modifiedItems[] = [
                'ItemIndex' => $itemIndex,
                'ItemLevel' => $itemLevel,
                'ItemCount' => $newQuantity,
            ];
        }

        return $modifiedItems;
    }
}
