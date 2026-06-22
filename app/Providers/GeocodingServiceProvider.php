<?php

namespace App\Providers;

use App\Services\Geocoding\CachedGeocoder;
use App\Services\Geocoding\Contracts\Geocoder;
use App\Services\Geocoding\NominatimGeocoder;
use Illuminate\Support\ServiceProvider;

class GeocodingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            NominatimGeocoder::class,
        );

        $this->app->bind(
            Geocoder::class,
            function ($app) {
                return new CachedGeocoder(
                    $app->make(NominatimGeocoder::class),
                );
            }
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
