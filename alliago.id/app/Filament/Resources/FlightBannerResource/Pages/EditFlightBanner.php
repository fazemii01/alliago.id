<?php

namespace App\Filament\Resources\FlightBannerResource\Pages;

use App\Filament\Resources\FlightBannerResource;
use Filament\Resources\Pages\EditRecord;

class EditFlightBanner extends EditRecord
{
    protected static string $resource = FlightBannerResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
