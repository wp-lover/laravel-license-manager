<?php

namespace App\Filament\Admin\Resources\Licenses\Pages;

use App\Filament\Admin\Resources\Licenses\LicenseResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewLicense extends ViewRecord
{
    protected static string $resource = LicenseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
