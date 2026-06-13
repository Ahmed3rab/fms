<?php

namespace App\Filament\Resources\PortalLinks;

use App\Filament\Resources\PortalLinks\Pages\CreatePortalLink;
use App\Filament\Resources\PortalLinks\Pages\EditPortalLink;
use App\Filament\Resources\PortalLinks\Pages\ListPortalLinks;
use App\Filament\Resources\PortalLinks\Pages\ViewPortalLink;
use App\Filament\Resources\PortalLinks\Schemas\PortalLinkForm;
use App\Filament\Resources\PortalLinks\Schemas\PortalLinkInfolist;
use App\Filament\Resources\PortalLinks\Tables\PortalLinksTable;
use App\Models\PortalLink;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PortalLinkResource extends Resource
{
    protected static ?string $model = PortalLink::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return PortalLinkForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PortalLinkInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PortalLinksTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPortalLinks::route('/'),
            'create' => CreatePortalLink::route('/create'),
            'view' => ViewPortalLink::route('/{record}'),
            'edit' => EditPortalLink::route('/{record}/edit'),
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
