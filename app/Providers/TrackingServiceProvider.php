<?php

namespace App\Providers;

use App\Services\Tracking\Contracts\TrackingProvider;
use App\Services\WebSocket\Messages\AuthenticateMessage;
use App\Services\WebSocket\Messages\MessageDispatcher;
use App\Services\WebSocket\Messages\PingMessage;
use App\Services\WebSocket\Messages\SubscribeVehicleMessage;
use App\Services\WebSocket\Messages\UnsubscribeVehicleMessage;
use Illuminate\Support\ServiceProvider;

class TrackingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            TrackingProvider::class,
            config('tracking.provider'),
        );
        $this->app->singleton(MessageDispatcher::class, function ($app) {
            return new MessageDispatcher([
                $app->make(AuthenticateMessage::class),
                $app->make(SubscribeVehicleMessage::class),
                $app->make(UnsubscribeVehicleMessage::class),
                $app->make(PingMessage::class),
            ]);
        });
    }
    public function boot(): void {}
}
