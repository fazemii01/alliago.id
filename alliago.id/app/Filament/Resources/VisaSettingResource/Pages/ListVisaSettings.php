<?php

namespace App\Filament\Resources\VisaSettingResource\Pages;

use App\Filament\Resources\VisaSettingResource;
use Filament\Resources\Pages\ListRecords;

class ListVisaSettings extends ListRecords
{
    protected static string $resource = VisaSettingResource::class;

    public function mount(): void
    {
        // Ensure the default configuration record exists
        \App\Models\VisaSetting::current();

        parent::mount();
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
