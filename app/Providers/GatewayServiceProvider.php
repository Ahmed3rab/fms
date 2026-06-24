<?php

namespace App\Providers;

use App\Gateway\Connections\ClientRepository;
use App\Gateway\Protocol\Handlers\AuthenticateHandler;
use App\Gateway\Protocol\Handlers\PingHandler;
use App\Gateway\Protocol\Handlers\UnsubscribeHandler;
use App\Gateway\Protocol\Messages\Incoming\PingMessage;
use App\Gateway\Protocol\Handlers\SubscribeHandler;
use App\Gateway\Protocol\Messages\Incoming\UnsubscribeMessage;
use App\Gateway\Protocol\Messages\Incoming\AuthenticateMessage;
use App\Gateway\Protocol\Messages\Incoming\SubscribeMessage;
use App\Gateway\Routing\MessageRouter;
use App\Gateway\Subscriptions\SubscriptionManager;
use Illuminate\Support\ServiceProvider;

class GatewayServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(ClientRepository::class);

        $this->app->singleton(SubscriptionManager::class);

        $this->app->singleton(MessageRouter::class, function ($app) {
            return new MessageRouter([
                AuthenticateMessage::class => $app->make(AuthenticateHandler::class),
                SubscribeMessage::class => $app->make(SubscribeHandler::class),
                UnsubscribeMessage::class => $app->make(UnsubscribeHandler::class),
                PingMessage::class => $app->make(PingHandler::class),
            ]);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
