<?php

namespace App\Livewire\Forms;

use App\Enums\Utility\FilterCharacterClass;
use Illuminate\Support\Collection;
use Livewire\Attributes\Url;
use Livewire\Form;

class Filters extends Form
{
    #[Url]
    public FilterCharacterClass $class = FilterCharacterClass::All;

    public function init() {}

    public function classes(): Collection
    {
        return collect(FilterCharacterClass::cases())->map(function ($class) {
            return [
                'value' => $class->value,
                'label' => $class->getLabel(),
                'classes' => $class->getClasses(),
                'image' => $class->getImagePath(),
            ];
        });
    }

    public function applyClass($query, $class = null)
    {
        $class = $class ?? $this->class;

        if ($class === FilterCharacterClass::All) {
            return $query;
        }

        return $query->whereIn('Class', $class->getClasses());
    }

    public function apply($query)
    {
        $this->applyClass($query);

        return $query;
    }
}
