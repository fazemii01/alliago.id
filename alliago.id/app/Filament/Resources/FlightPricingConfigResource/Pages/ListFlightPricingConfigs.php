<?php

namespace App\Filament\Resources\FlightPricingConfigResource\Pages;

use App\Filament\Resources\FlightPricingConfigResource;
use Filament\Resources\Pages\ListRecords;

class ListFlightPricingConfigs extends ListRecords
{
    protected static string $resource = FlightPricingConfigResource::class;

    public function mount(): void
    {
        // Ensure at least one default record exists before rendering
        \App\Models\FlightPricingConfig::current();

        parent::mount();
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
