<?php

namespace App\Filament\Resources\VisaProductResource\Pages;

use App\Filament\Resources\VisaProductResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVisaProduct extends EditRecord
{
    protected static string $resource = VisaProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
