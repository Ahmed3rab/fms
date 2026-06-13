<?php

namespace App\Filament\Resources\Admin\Roles\Pages;

use App\Filament\Resources\Admin\Roles\RoleResource;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends ViewRecord<Model>
 */
class ViewRole extends ViewRecord
{
    protected static string $resource = RoleResource::class;

    public function getHeading(): string
    {
        return $this->record->name;
    }
    public function getSubheading(): ?string
    {
        return sprintf(
            '%d users • %d permissions',
            $this->record->users()->count(),
            $this->record->permissions()->count(),
        );
    }
    public function getBreadcrumb(): string
    {
        return '';
    }
    protected function getHeaderActions(): array
    {
        return [
        ];
    }

}
