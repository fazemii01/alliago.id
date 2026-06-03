<?php

namespace App\Filament\Resources\FerryApplicationResource\Pages;

use App\Filament\Resources\FerryApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFerryApplication extends EditRecord
{
    protected static string $resource = FerryApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
