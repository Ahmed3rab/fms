<?php

namespace App\Filament\Portal\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

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
                            $query->whereIn('name', [
                                'company_admin',
                                'api_consumer',
                            ]);
                        }
                    )
                    ->preload()
                    ->searchable()
                    ->live()
                    ->required()
                    ->multiple(false),
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
