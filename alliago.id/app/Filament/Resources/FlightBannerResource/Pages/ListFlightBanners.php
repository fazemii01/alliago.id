<?php

namespace App\Filament\Resources\FlightBannerResource\Pages;

use App\Filament\Resources\FlightBannerResource;
use Filament\Resources\Pages\ListRecords;

class ListFlightBanners extends ListRecords
{
    protected static string $resource = FlightBannerResource::class;

    public function mount(): void
    {
        // Ensure at least one default record exists before rendering
        \App\Models\FlightPricingConfig::current();

        parent::mount();
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make(),
        ];
    }
}
