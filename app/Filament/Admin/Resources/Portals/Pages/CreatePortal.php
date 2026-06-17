<?php

namespace App\Filament\Admin\Resources\Portals\Pages;

use App\Filament\Admin\Resources\Portals\PortalResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends CreateRecord<Model>
 */
class CreatePortal extends CreateRecord
{
    protected static string $resource = PortalResource::class;
}
