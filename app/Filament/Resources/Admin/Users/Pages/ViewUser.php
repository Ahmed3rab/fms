<?php

namespace App\Filament\Resources\Admin\Users\Pages;

use App\Filament\Resources\Admin\Users\UserResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends ViewRecord<Model>
 */
class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
