<?php

namespace App\Filament\Resources\ScheduledEventResource\Pages;

use App\Filament\Resources\ScheduledEventResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;

class CreateScheduledEvent extends CreateRecord
{
    protected static string $resource = ScheduledEventResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        Log::info('Form data before create:', $data);

        return $data;
    }

    protected function beforeValidate(): void
    {
        $data = $this->form->getState();
        Log::info('Form data before validation:', $data);
    }

    protected function afterValidate(): void
    {
        $data = $this->form->getState();
        Log::info('Form data after validation:', $data);
    }

    // You can also add this method to see if it's being called
    public function create(bool $another = false): void
    {
        Log::info('Create method called');
        parent::create($another);
    }
}
