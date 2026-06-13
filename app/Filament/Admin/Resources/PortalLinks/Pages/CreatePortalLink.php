<?php

namespace App\Filament\Admin\Resources\PortalLinks\Pages;

use App\Filament\Admin\Resources\PortalLinks\PortalLinkResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends CreateRecord<Model>
 */
class CreatePortalLink extends CreateRecord
{
    protected static string $resource = PortalLinkResource::class;
}
