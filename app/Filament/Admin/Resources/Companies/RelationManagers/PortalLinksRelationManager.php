<?php

namespace App\Filament\Admin\Resources\Companies\RelationManagers;

use App\Filament\Admin\Resources\PortalLinks\PortalLinkResource;
use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\DetachAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class PortalLinksRelationManager extends RelationManager
{
    protected static string $relationship = 'portalLinks';

    protected static ?string $relatedResource = PortalLinkResource::class;

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
