<?php

namespace App\Enums\Utility;

enum ActivityType: string
{
    case INCREMENT = 'increment';
    case DECREMENT = 'decrement';
    case INTERNAL = 'internal';
    case DEFAULT = 'default';

    public function getLabel(): string
    {
        return match ($this) {
            self::INCREMENT => 'Increment',
            self::DECREMENT => 'Decrement',
            self::INTERNAL => 'Internal',
            self::DEFAULT => 'Default',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::INCREMENT => 'green',
            self::DECREMENT => 'red',
            self::INTERNAL => 'yellow',
            self::DEFAULT => 'zinc',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::INCREMENT => 'arrow-up',
            self::DECREMENT => 'arrow-down',
            self::INTERNAL => 'arrows-right-left',
            self::DEFAULT => 'document-text',
        };
    }
}
