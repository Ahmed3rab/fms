<?php

namespace App\Filament\Admin\Resources\Portals\Pages;

use App\Filament\Admin\Resources\Portals\PortalResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends ViewRecord<Model>
 */
class ViewPortal extends ViewRecord
{
    protected static string $resource = PortalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
