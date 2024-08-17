<?php

namespace App\Livewire;

use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Livewire\Component;

class DatabaseSelector extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public $selectedDatabase;

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('selectedDatabase')
                    ->label('Choose Database')
                    ->options([
                        'database1' => 'Database 1',
                        'database2' => 'Database 2',
                        'database3' => 'Database 3',
                    ])
                    ->required(),
            ]);
    }

    public function save(): void
    {

        Notification::make()
            ->title('Database Changed')
            ->body('Switched successfully!')
            ->success()
            ->send();
    }

    public function render()
    {
        return view('livewire.database-selector');
    }
}
