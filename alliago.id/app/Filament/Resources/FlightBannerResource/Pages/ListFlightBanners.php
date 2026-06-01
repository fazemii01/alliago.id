<?php

namespace App\Filament\Resources\FlightBannerResource\Pages;

use App\Filament\Resources\FlightBannerResource;
use Filament\Resources\Pages\ListRecords;

class ListFlightBanners extends ListRecords
{
    protected static string $resource = FlightBannerResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
