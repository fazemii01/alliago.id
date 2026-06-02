<?php

namespace App\Filament\Resources\PopupBannerResource\Pages;

use App\Filament\Resources\PopupBannerResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManagePopupBanners extends ManageRecords
{
    protected static string $resource = PopupBannerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
