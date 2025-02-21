<?php

namespace App\Models\Concerns;

use App\Enums\Game\BanStatus;
use DateTime;

trait IsBannable
{
    public function getStatusField(): string
    {
        return match ($this->getTable()) {
            'Character' => 'CtlCode',
            default => 'bloc_code'
        };
    }

    public function getExpiryField(): string
    {
        return 'bloc_expire';
    }

    public function isBanned(): bool
    {
        $field = $this->getStatusField();

        return $this->{$field} === BanStatus::Banned;
    }

    public function banPermanently(): void
    {
        $statusField = $this->getStatusField();
        $expiryField = $this->getExpiryField();

        $this->update([
            $statusField => BanStatus::Banned,
            $expiryField => null,
        ]);
    }

    public function banUntil(DateTime $expireDate): void
    {
        $statusField = $this->getStatusField();
        $expiryField = $this->getExpiryField();

        $this->update([
            $statusField => BanStatus::Banned,
            $expiryField => $expireDate,
        ]);
    }

    public function unban(): void
    {
        $statusField = $this->getStatusField();
        $expiryField = $this->getExpiryField();

        $this->update([
            $statusField => BanStatus::Active,
            $expiryField => null,
        ]);
    }

    public function getBanExpirationText(): string
    {
        if (! $this->isBanned()) {
            return 'Not banned';
        }

        $expiryField = $this->getExpiryField();

        return $this->{$expiryField} === null ? 'Permanent' : $this->{$expiryField}->format('Y-m-d H:i');
    }
}
