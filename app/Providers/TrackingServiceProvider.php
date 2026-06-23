<?php

namespace App\Providers;

use App\Services\ICruise\ICruiseTrackingProvider;
use App\Services\Tracking\Contracts\TrackingProvider;
use Illuminate\Support\ServiceProvider;

class TrackingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            TrackingProvider::class,
            ICruiseTrackingProvider::class,
        );
    }
    public function boot(): void {}
}
