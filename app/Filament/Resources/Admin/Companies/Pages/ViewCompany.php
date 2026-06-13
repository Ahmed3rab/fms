<?php

namespace App\Filament\Resources\Admin\Companies\Pages;

use App\Filament\Resources\Admin\Companies\CompanyResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends ViewRecord<Model>
 */
class ViewCompany extends ViewRecord
{
    protected static string $resource = CompanyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
