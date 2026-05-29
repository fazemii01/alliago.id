<?php

namespace App\Filament\Resources\FlightPricingConfigResource\Pages;

use App\Filament\Resources\FlightPricingConfigResource;
use Filament\Resources\Pages\ListRecords;

class ListFlightPricingConfigs extends ListRecords
{
    protected static string $resource = FlightPricingConfigResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
