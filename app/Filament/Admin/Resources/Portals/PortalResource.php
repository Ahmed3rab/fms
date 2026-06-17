<?php

namespace App\Filament\Admin\Resources\Portals;

use App\Filament\Admin\Resources\Portals\Pages\EditPortal;
use App\Filament\Admin\Resources\Portals\Pages\CreatePortal;
use App\Filament\Admin\Resources\Portals\Pages\ViewPortal;
use App\Filament\Admin\Resources\Portals\Pages\ListPortals;
use App\Filament\Admin\Resources\Portals\RelationManagers\CompaniesRelationManager;
use App\Filament\Admin\Resources\Portals\Schemas\PortalForm;
use App\Filament\Admin\Resources\Portals\Schemas\PortalInfolist;
use App\Filament\Admin\Resources\Portals\Tables\PortalsTable;
use App\Models\Portal;
use BackedEnum;
use Filament\Resources\RelationManagers\RelationGroup;
use Filament\Resources\Resource;
use Filament\Resources\ResourceConfiguration;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

/**
 * @extends Resource<Model,ResourceConfiguration>
 */
class PortalResource extends Resource
{
    protected static ?string $model = Portal::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return PortalForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PortalInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PortalsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            RelationGroup::make('user', [
                CompaniesRelationManager::class,
            ]),
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPortals::route('/'),
            'create' => CreatePortal::route('/create'),
            'view' => ViewPortal::route('/{record}'),
            'edit' => EditPortal::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
