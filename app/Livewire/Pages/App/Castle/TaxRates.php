<?php

namespace App\Livewire\Pages\App\Castle;

use App\Livewire\BaseComponent;
use Livewire\Attributes\Computed;

class TaxRates extends BaseComponent
{
    public int $storeTax = 0;

    public int $goblinTax = 0;

    public int $huntZoneTax = 0;

    public function mount(int $storeTax, int $goblinTax, int $huntZoneTax)
    {
        $this->storeTax = $storeTax;
        $this->goblinTax = $goblinTax;
        $this->huntZoneTax = $huntZoneTax;
    }

    #[Computed(persist: true)]
    public function taxRates(): array
    {
        return [
            'store' => $this->storeTax,
            'goblin' => $this->goblinTax,
            'huntZone' => $this->huntZoneTax,
        ];
    }

    protected function getViewName(): string
    {
        return 'pages.app.castle.tax-rates';
    }

    protected function getLayoutType(): string
    {
        return 'app';
    }
}
