<?php

namespace App\Filament\Resources\Admin\PortalLinks\Pages;

use App\Filament\Resources\Admin\PortalLinks\PortalLinkResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends ViewRecord<Model>
 */
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
