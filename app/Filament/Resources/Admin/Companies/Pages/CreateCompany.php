<?php

namespace App\Filament\Resources\Admin\Companies\Pages;

use App\Filament\Resources\Admin\Companies\CompanyResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends CreateRecord<Model>
 */
class CreateCompany extends CreateRecord
{
    protected static string $resource = CompanyResource::class;
}
