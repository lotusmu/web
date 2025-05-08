<?php

namespace App\Enums\Game;

enum BankItem: int
{
    // Basic Jewels
    case JEWEL_OF_CHAOS = 6159;
    case JEWEL_OF_BLESS = 7181;
    case JEWEL_OF_SOUL = 7182;
    case JEWEL_OF_LIFE = 7184;
    case JEWEL_OF_CREATION = 7190;
    case JEWEL_OF_GUARDIAN = 7199;

    // Harmony Materials
    case JEWEL_OF_HARMONY = 7210;
    case LOWER_REFINING_STONE = 7211;
    case HIGHER_REFINING_STONE = 7212;

    // Other Items
    case GEMSTONE = 7200;
    case LOCHS_FEATHER = 6670;
    // We cannot have MONARCHS_CREST with the same value 6670

    // Superb Jewels
    case JEWEL_OF_LEVEL = 7412;
    case JEWEL_OF_LUCK = 7414;
    case JEWEL_OF_RECOVERY = 7415;

    /**
     * Get the level for this item
     */
    public function getLevel(): int
    {
        return 0; // Default level for most items
    }

    /**
     * Get the display name for the item
     */
    public function getName(int $level = 0): string
    {
        // Special case for LOCHS_FEATHER which has different names based on level
        if ($this === self::LOCHS_FEATHER && $level === 1) {
            return 'Monarch\'s Crest';
        }

        return match ($this) {
            self::JEWEL_OF_CHAOS => 'Jewel of Chaos',
            self::JEWEL_OF_BLESS => 'Jewel of Bless',
            self::JEWEL_OF_SOUL => 'Jewel of Soul',
            self::JEWEL_OF_LIFE => 'Jewel of Life',
            self::JEWEL_OF_CREATION => 'Jewel of Creation',
            self::JEWEL_OF_GUARDIAN => 'Jewel of Guardian',
            self::JEWEL_OF_HARMONY => 'Jewel of Harmony',
            self::LOWER_REFINING_STONE => 'Lower refining stone',
            self::HIGHER_REFINING_STONE => 'Higher refining stone',
            self::GEMSTONE => 'Gemstone',
            self::LOCHS_FEATHER => 'Loch\'s Feather',
            self::JEWEL_OF_LEVEL => 'Jewel of Level',
            self::JEWEL_OF_LUCK => 'Jewel Of Luck',
            self::JEWEL_OF_RECOVERY => 'Jewel Of Recovery',
        };
    }

    /**
     * Get the group this item belongs to
     */
    public function getGroup(): string
    {
        return match ($this) {
            self::JEWEL_OF_CHAOS,
            self::JEWEL_OF_BLESS,
            self::JEWEL_OF_SOUL,
            self::JEWEL_OF_LIFE,
            self::JEWEL_OF_CREATION,
            self::JEWEL_OF_GUARDIAN => 'Basic Jewels',

            self::JEWEL_OF_HARMONY,
            self::LOWER_REFINING_STONE,
            self::HIGHER_REFINING_STONE => 'Harmony Materials',

            self::GEMSTONE,
            self::LOCHS_FEATHER => 'Other Items',

            self::JEWEL_OF_LEVEL,
            self::JEWEL_OF_LUCK,
            self::JEWEL_OF_RECOVERY => 'Superb Jewels',
        };
    }

    /**
     * Get item by index and level
     */
    public static function fromIndexAndLevel(int $index, int $level): ?self
    {
        foreach (self::cases() as $case) {
            if ($case->value === $index) {
                return $case;
            }
        }

        return null;
    }

    /**
     * Create a Monarch's Crest item (which is Loch's Feather with level 1)
     */
    public static function MONARCHS_CREST(): array
    {
        return [
            'item' => self::LOCHS_FEATHER,
            'level' => 1,
        ];
    }

    /**
     * Get all items organized by group
     */
    public static function getItemGroups(): array
    {
        $groups = [];

        foreach (self::cases() as $item) {
            $group = $item->getGroup();

            if (! isset($groups[$group])) {
                $groups[$group] = [];
            }

            // Special case for items with multiple levels
            if ($item === self::LOCHS_FEATHER) {
                $groups[$group][$item->value] = [
                    0 => 'Loch\'s Feather',
                    1 => 'Monarch\'s Crest',
                ];
            } else {
                $groups[$group][$item->value] = [
                    0 => $item->getName(),
                ];
            }
        }

        return $groups;
    }
}
