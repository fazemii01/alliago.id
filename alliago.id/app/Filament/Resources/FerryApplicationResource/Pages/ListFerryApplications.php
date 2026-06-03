<?php

namespace App\Filament\Resources\FerryApplicationResource\Pages;

use App\Filament\Resources\FerryApplicationResource;
use Filament\Resources\Pages\ListRecords;

class ListFerryApplications extends ListRecords
{
    protected static string $resource = FerryApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
