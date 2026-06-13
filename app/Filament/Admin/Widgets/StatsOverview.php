<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Company;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Companies', Company::count()),
            Stat::make('Active Companies', Company::whereNull('deleted_at')->count()),
            Stat::make('Users', User::count()),
            Stat::make('Inactive Users', User::onlyTrashed()->count()),
        ];
    }
}
