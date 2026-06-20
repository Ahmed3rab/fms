<?php

namespace App\Filament\Admin\Resources\Companies\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CompanyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug')
                    ->unique(ignoreRecord: true)
                    ->required(),
                Select::make('visible_companies')
                    ->relationship('visibleCompanies', 'name')
                    ->preload()
                    ->multiple(),
            ]);
    }
}
