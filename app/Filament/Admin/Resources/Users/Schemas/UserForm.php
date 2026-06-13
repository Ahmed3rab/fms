<?php

namespace App\Filament\Admin\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Spatie\Permission\Models\Role;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                Select::make('roles')
                    ->relationship(
                        'roles',
                        'name',
                        modifyQueryUsing: function ($query) {

                            $user = auth()->user();

                            if ($user->hasRole('super_admin')) {
                                return;
                            }

                            if ($user->hasRole('system_admin')) {
                                $query->whereIn('name', [
                                    'system_admin',
                                    'company_admin',
                                    'api_consumer',
                                ]);
                            }

                            if ($user->hasRole('company_admin')) {
                                $query->whereIn('name', [
                                    'company_admin',
                                    'api_consumer',
                                ]);
                            }
                        }
                    )
                    ->preload()
                    ->searchable()
                    ->live()
                    ->required()
                    ->multiple(false),
                Select::make('company_id')
                    ->relationship(
                        'company',
                        'name',
                        modifyQueryUsing: fn($query) => $query->whereNull('deleted_at')
                    )
                    ->searchable()
                    ->preload()
                    ->reactive()
                    ->visible(function (Get $get) {
                        if (! auth()->user()->hasAnyRole(['super_admin', 'system_admin'])) {
                            return false;
                        }
                        $role = Role::find($get('roles'));
                        return in_array($role?->name, ['company_admin', 'api_consumer']);
                    })
                    ->required(function (Get $get) {
                        return auth()->user()->hasAnyRole(['super_admin', 'system_admin'])
                        && in_array(Role::find($get('roles'))?->name, ['company_admin', 'api_consumer']);
                    }),

                TextInput::make('password')
                    ->password()
                    ->dehydrated(fn($state) => filled($state))
                    ->dehydrateStateUsing(fn($state) => bcrypt($state))
                    ->confirmed()
                    ->required(fn(string $operation) => $operation === 'create'),
                TextInput::make('password_confirmation')
                    ->requiredWith('password')
                    ->password(),

            ]);
    }

}
