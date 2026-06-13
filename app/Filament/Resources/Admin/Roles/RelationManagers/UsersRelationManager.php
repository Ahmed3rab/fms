<?php

namespace App\Filament\Resources\Admin\Roles\RelationManagers;

use App\Filament\Resources\Admin\Users\UserResource;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UsersRelationManager extends RelationManager
{
    protected static string $relationship = 'users';

    protected static ?string $relatedResource = UserResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->heading('')
            ->recordTitleAttribute('name')
            ->recordUrl(fn($record) => UserResource::getUrl('view', [ 'record' => $record]))
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime(),
            ])
            ->headerActions([])
            ->recordActions([
            ])
            ->toolbarActions([]);
    }
}
