<?php

namespace App\Filament\Resources\PortalLinks\Pages;

use App\Filament\Resources\PortalLinks\PortalLinkResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPortalLink extends ViewRecord
{
    protected static string $resource = PortalLinkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
