<?php

namespace App\Providers;

use App\Services\ICruise\ICruiseTrackingGateway;
use App\Services\Tracking\Contracts\TrackingGateway;
use Illuminate\Support\ServiceProvider;

class TrackingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            TrackingGateway::class,
            ICruiseTrackingGateway::class,
        );
    }
    public function boot(): void {}
}
