<?php

namespace App\Filament\Portal\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Filament\Pages\PageConfiguration;

/**
 * @extends Page<PageConfiguration>
 */
class Home extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-home';

    protected string $view = 'filament.portal.pages.home';

    protected static ?string $title = 'Portal';

    protected static ?string $navigationLabel = 'Home';

    protected static ?string $slug = '';

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    public function getPortals()
    {
        return auth()
            ->user()
            ->company
            ->portals()
            ->orderBy('sort_order')
            ->get();
    }
}
