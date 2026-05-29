<?php

namespace App\Filament\Resources\FlightPricingConfigResource\Pages;

use App\Filament\Resources\FlightPricingConfigResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFlightPricingConfig extends EditRecord
{
    protected static string $resource = FlightPricingConfigResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
