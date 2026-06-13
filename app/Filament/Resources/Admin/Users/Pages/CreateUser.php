<?php

namespace App\Filament\Resources\Admin\Users\Pages;

use App\Filament\Resources\Admin\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends CreateRecord<Model>
 */
class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (auth()->user()->hasRole('company_admin')) {
            $data['company_id'] = auth()->user()->company_id;
        }

        return $data;
    }
}
