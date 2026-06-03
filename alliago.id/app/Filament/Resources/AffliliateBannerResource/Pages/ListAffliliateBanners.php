<?php

namespace App\Filament\Resources\AffliliateBannerResource\Pages;

use App\Filament\Resources\AffliliateBannerResource;
use Filament\Resources\Pages\ListRecords;

class ListAffliliateBanners extends ListRecords
{
    protected static string $resource = AffliliateBannerResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
