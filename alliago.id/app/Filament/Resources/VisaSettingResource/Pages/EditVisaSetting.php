<?php

namespace App\Filament\Resources\VisaSettingResource\Pages;

use App\Filament\Resources\VisaSettingResource;
use Filament\Resources\Pages\EditRecord;

class EditVisaSetting extends EditRecord
{
    protected static string $resource = VisaSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
