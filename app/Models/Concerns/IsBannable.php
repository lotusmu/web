<?php

namespace App\Models\Concerns;

use App\Enums\Game\BanStatus;
use App\Support\ActivityLog\IdentityProperties;
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

    public function getModelTypeForLogging(): string
    {
        return match ($this->getTable()) {
            'Character' => 'character',
            'MEMB_INFO' => 'account',
            default => 'entity'
        };
    }

    public function getSubjectNameForLogging(): string
    {
        // For Character model, use Name field
        if ($this->getTable() === 'Character') {
            return $this->Name;
        }

        // For Member model, use memb___id field
        if ($this->getTable() === 'MEMB_INFO') {
            return $this->memb___id;
        }

        // Default fallback
        return (string) $this->getKey();
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

        $modelType = $this->getModelTypeForLogging();
        $subjectName = $this->getSubjectNameForLogging();

        // Log using a simple description to avoid model binding issues
        activity('ban')
            ->withProperties([
                'action' => 'permanent_ban',
                'ban_type' => 'permanent',
                'model_type' => $modelType,
                'subject_name' => $subjectName,
                ...IdentityProperties::capture(),
            ])
            ->log(":causer.name permanently banned {$modelType} {$subjectName}");
    }

    public function banUntil(DateTime $expireDate): void
    {
        $statusField = $this->getStatusField();
        $expiryField = $this->getExpiryField();

        $this->update([
            $statusField => BanStatus::Banned,
            $expiryField => $expireDate,
        ]);

        $modelType = $this->getModelTypeForLogging();
        $subjectName = $this->getSubjectNameForLogging();
        $formattedExpiry = $expireDate->format('M d Y, H:i');

        // Log the temporary ban action
        activity('ban')
            ->withProperties([
                'action' => 'temporary_ban',
                'ban_type' => 'temporary',
                'expires_at' => $formattedExpiry,
                'model_type' => $modelType,
                'subject_name' => $subjectName,
                ...IdentityProperties::capture(),
            ])
            ->log(":causer.name banned {$modelType} {$subjectName} until {$formattedExpiry}");
    }

    public function unban(): void
    {
        $statusField = $this->getStatusField();
        $expiryField = $this->getExpiryField();

        $this->update([
            $statusField => BanStatus::Active,
            $expiryField => null,
        ]);

        $modelType = $this->getModelTypeForLogging();
        $subjectName = $this->getSubjectNameForLogging();

        // Log the unban action
        activity('ban')
            ->withProperties([
                'action' => 'unban',
                'model_type' => $modelType,
                'subject_name' => $subjectName,
                ...IdentityProperties::capture(),
            ])
            ->log(":causer.name unbanned {$modelType} {$subjectName}");
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
