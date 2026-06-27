<?php

namespace App\Providers;

use App\Services\Tracking\Contracts\TrackingProvider;
use App\Services\Tracking\Identifiers\Contract\TrackingDeviceRegistry;
use App\Services\Tracking\Identifiers\Contract\TrackingVehicleRegistry;
use App\Services\Tracking\Identifiers\ICruiseTrackingDeviceRegistry;
use App\Services\Tracking\Identifiers\ICruiseTrackingVehicleRegistry;
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
            TrackingDeviceRegistry::class,
            ICruiseTrackingDeviceRegistry::class,
        );
        $this->app->bind(
            TrackingVehicleRegistry::class,
            ICruiseTrackingVehicleRegistry::class,
        );
    }

    public function boot(): void {}
}
