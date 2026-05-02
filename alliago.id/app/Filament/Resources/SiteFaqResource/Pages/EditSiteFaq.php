<?php

namespace App\Filament\Resources\SiteFaqResource\Pages;

use App\Filament\Resources\SiteFaqResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSiteFaq extends EditRecord
{
    protected static string $resource = SiteFaqResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
