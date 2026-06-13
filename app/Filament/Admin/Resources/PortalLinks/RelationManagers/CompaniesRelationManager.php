<?php

namespace App\Filament\Admin\Resources\PortalLinks\RelationManagers;

use App\Filament\Admin\Resources\Companies\CompanyResource;
use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\DetachAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class CompaniesRelationManager extends RelationManager
{
    protected static string $relationship = 'companies';

    protected static ?string $relatedResource = CompanyResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                AttachAction::make()->preloadRecordSelect(),
            ])
            ->recordActions([
                DetachAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // ...
                    DetachBulkAction::make(),
                ]),
            ]);
    }
}
