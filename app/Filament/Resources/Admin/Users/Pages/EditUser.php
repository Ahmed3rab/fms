<?php

namespace App\Filament\Resources\Admin\Users\Pages;

use App\Filament\Resources\Admin\Users\UserResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends EditRecord<Model>
 */
class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make()->label('deactivate'),
            RestoreAction::make()->label('activate'),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (auth()->user()->hasRole('company_admin')) {
            $data['company_id'] = auth()->user()->company_id;
        }

        return $data;
    }
}
