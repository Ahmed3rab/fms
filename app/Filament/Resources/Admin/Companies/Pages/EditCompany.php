<?php

namespace App\Filament\Resources\Admin\Companies\Pages;

use App\Filament\Resources\Admin\Companies\CompanyResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends EditRecord<Model>
 */
class EditCompany extends EditRecord
{
    protected static string $resource = CompanyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make()->label('deactivate'),
            RestoreAction::make()->label('activate'),
        ];
    }
}
