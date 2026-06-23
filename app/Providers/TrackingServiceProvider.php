<?php

namespace App\Providers;

use App\Services\Tracking\Contracts\TrackingProvider;
use Illuminate\Support\ServiceProvider;

class TrackingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            TrackingProvider::class,
            config('tracking.provider'),
        );
    }
    public function boot(): void {}
}
