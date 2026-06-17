<?php

namespace App\Filament\Admin\Resources\Companies;

use App\Filament\Admin\Resources\Companies\Pages\EditCompany;
use App\Filament\Admin\Resources\Companies\Pages\ViewCompany;
use App\Filament\Admin\Resources\Companies\Pages\CreateCompany;
use App\Filament\Admin\Resources\Companies\Pages\ListCompanies;
use App\Filament\Admin\Resources\Companies\RelationManagers\PortalsRelationManager;
use App\Filament\Admin\Resources\Companies\RelationManagers\UsersRelationManager;
use App\Filament\Admin\Resources\Companies\Schemas\CompanyForm;
use App\Filament\Admin\Resources\Companies\Tables\CompaniesTable;
use App\Filament\Admin\Resources\Companies\Schemas\CompanyInfolist;
use App\Models\Company;
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
class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    protected static bool $isScopedToTenant = false;

    public static function form(Schema $schema): Schema
    {
        return CompanyForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CompanyInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CompaniesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            RelationGroup::make('user', [
                UsersRelationManager::class,
                PortalsRelationManager::class,
            ]),
            RelationGroup::make('portalLinks', [
            ]),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCompanies::route('/'),
            'create' => CreateCompany::route('/create'),
            'view' => ViewCompany::route('/{record}'),
            'edit' => EditCompany::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->hasAnyRole([
            'super_admin',
            'system_admin',
        ]) ?? false;
    }
}
