<?php

namespace App\Filament\Admin\Resources\Portals\Pages;

use App\Filament\Admin\Resources\Portals\PortalResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends EditRecord<Model>
 */
class EditPortal extends EditRecord
{
    protected static string $resource = PortalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
