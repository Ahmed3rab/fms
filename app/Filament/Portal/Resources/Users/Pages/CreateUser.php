<?php

namespace App\Filament\Portal\Resources\Users\Pages;

use App\Filament\Portal\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
