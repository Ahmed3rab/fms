<?php

namespace App\Filament\Resources\Admin\Roles\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RolesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('users_count')
                    ->counts('users'),
                TextColumn::make('permissions_count')
                    ->counts('permissions'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([
            ]);
    }
}
