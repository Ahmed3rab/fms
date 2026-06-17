<?php

namespace App\Filament\Admin\Resources\Portals\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PortalForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),

                TextInput::make('url')
                    ->url()
                    ->required(),

                TextInput::make('icon'),

                TextInput::make('sort_order')
                    ->numeric(),
                //
            ]);
    }
}
