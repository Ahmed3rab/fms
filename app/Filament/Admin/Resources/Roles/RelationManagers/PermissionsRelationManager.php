<?php

namespace App\Filament\Admin\Resources\Roles\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PermissionsRelationManager extends RelationManager
{
    protected static string $relationship = 'permissions';

    public function table(Table $table): Table
    {
        return $table
            ->heading('')
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->badge()
                    ->searchable(),
            ])
            ->headerActions([])
            ->recordActions([])
            ->toolbarActions([]);
    }
}
