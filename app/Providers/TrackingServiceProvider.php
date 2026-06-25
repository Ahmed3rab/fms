<?php

namespace App\Providers;

use App\Services\Tracking\Contracts\TrackingProvider;
use App\Services\Tracking\Resolvers\Contract\TrackingDeviceResolver;
use App\Services\Tracking\Resolvers\ICruiseTrackingDeviceResolver;
use Illuminate\Support\ServiceProvider;

class TrackingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            TrackingProvider::class,
            config('tracking.provider'),
        );
        $this->app->bind(
            TrackingDeviceResolver::class,
            ICruiseTrackingDeviceResolver::class,
        );
    }

    public function boot(): void {}
}
