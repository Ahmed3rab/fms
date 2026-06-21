<?php

namespace App\Providers;

use App\Services\ICruise\ICruiseTrackingBackend;
use App\Services\Tracking\Contracts\TrackingBackend;
use Illuminate\Support\ServiceProvider;

class TrackingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            TrackingBackend::class,
            ICruiseTrackingBackend::class,
        );
    }
    public function boot(): void {}
}
