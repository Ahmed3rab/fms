<?php

namespace App\Filament\Resources\Admin\Roles\Pages;

use App\Filament\Resources\Admin\Roles\RoleResource;
use Filament\Resources\Pages\ListRecords;

class ListRoles extends ListRecords
{
    protected static string $resource = RoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
