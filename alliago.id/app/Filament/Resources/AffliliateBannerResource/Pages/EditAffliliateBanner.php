<?php

namespace App\Filament\Resources\AffliliateBannerResource\Pages;

use App\Filament\Resources\AffliliateBannerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAffliliateBanner extends EditRecord
{
    protected static string $resource = AffliliateBannerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
