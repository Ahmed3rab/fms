<?php

namespace App\Filament\Admin\Resources\PortalLinks\Pages;

use App\Filament\Admin\Resources\PortalLinks\PortalLinkResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPortalLinks extends ListRecords
{
    protected static string $resource = PortalLinkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
