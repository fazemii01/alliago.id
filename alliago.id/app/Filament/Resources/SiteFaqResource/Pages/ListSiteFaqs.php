<?php

namespace App\Filament\Resources\SiteFaqResource\Pages;

use App\Filament\Resources\SiteFaqResource;
use Filament\Resources\Pages\ListRecords;

class ListSiteFaqs extends ListRecords
{
    protected static string $resource = SiteFaqResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make(),
        ];
    }
}
