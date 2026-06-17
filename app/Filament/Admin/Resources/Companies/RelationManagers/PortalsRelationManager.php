<?php

namespace App\Filament\Admin\Resources\Companies\RelationManagers;

use App\Filament\Admin\Resources\Portals\PortalResource;
use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\DetachAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class PortalsRelationManager extends RelationManager
{
    protected static string $relationship = 'portals';

    protected static ?string $relatedResource = PortalResource::class;

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
