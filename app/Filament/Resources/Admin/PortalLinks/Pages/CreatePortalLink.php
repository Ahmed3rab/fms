<?php

namespace App\Filament\Resources\Admin\PortalLinks\Pages;

use App\Filament\Resources\Admin\PortalLinks\PortalLinkResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends CreateRecord<Model>
 */
class CreatePortalLink extends CreateRecord
{
    protected static string $resource = PortalLinkResource::class;
}
