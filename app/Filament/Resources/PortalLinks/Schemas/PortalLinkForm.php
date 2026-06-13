<?php

namespace App\Filament\Resources\PortalLinks\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PortalLinkForm
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
