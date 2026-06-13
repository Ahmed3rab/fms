<?php

namespace App\Filament\Admin\Resources\Companies\Pages;

use App\Filament\Admin\Resources\Companies\CompanyResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends CreateRecord<Model>
 */
class CreateCompany extends CreateRecord
{
    protected static string $resource = CompanyResource::class;
}
