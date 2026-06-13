<?php

namespace App\Filament\Resources\Admin\Users;

use App\Filament\Resources\Admin\Users\Pages\EditUser;
use App\Filament\Resources\Admin\Users\Pages\CreateUser;
use App\Filament\Resources\Admin\Users\Pages\ViewUser;
use App\Filament\Resources\Admin\Users\Pages\ListUsers;
use App\Filament\Resources\Admin\Users\Schemas\UserForm;
use App\Filament\Resources\Admin\Users\Tables\UsersTable;
use App\Filament\Resources\Admin\Users\Schemas\UserInfolist;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Resources\ResourceConfiguration;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

/**
 * @extends Resource<Model,ResourceConfiguration>
 */
class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|UnitEnum|null $navigationGroup = 'Administration';

    protected static bool $isScopedToTenant = false;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function form(Schema $schema): Schema
    {
        return UserForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return UserInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UsersTable::configure($table);
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
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'view' => ViewUser::route('/{record}'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
    public static function canAccess(): bool
    {
        return auth()->user()?->hasAnyRole([
            'super_admin',
            'system_admin',
        ]) ?? false;
    }
    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->hasAnyRole([
            'super_admin',
            'system_admin',
        ]) ?? false;
    }
}
