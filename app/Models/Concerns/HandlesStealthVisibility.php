<?php

namespace App\Models\Concerns;

trait HandlesStealthVisibility
{
    public function shouldHideInformation(): bool
    {
        return $this->member?->has_stealth ?? false;
    }

    public function hideIfStealth($value, $default = 'Hidden')
    {
        if ($this->shouldHideInformation()) {
            return $default;
        }

        return is_numeric($value) ? number_format($value) : $value;
    }

    // Stats accessors
    public function getDisplayStrength()
    {
        return $this->hideIfStealth($this->Strength);
    }

    public function getDisplayDexterity()
    {
        return $this->hideIfStealth($this->Dexterity);
    }

    public function getDisplayVitality()
    {
        return $this->hideIfStealth($this->Vitality);
    }

    public function getDisplayEnergy()
    {
        return $this->hideIfStealth($this->Energy);
    }

    public function getDisplayLeadership()
    {
        return $this->hideIfStealth($this->Leadership);
    }

    // Location accessor
    public function getDisplayLocation()
    {
        return $this->hideIfStealth($this->MapNumber?->getLabel());
    }

    // Connection status accessors
    public function getDisplayLastLogin()
    {
        return $this->hideIfStealth($this->member?->status?->lastLogin);
    }

    public function getDisplayLastDisconnect()
    {
        return $this->hideIfStealth($this->member?->status?->lastDisconnect);
    }
}
