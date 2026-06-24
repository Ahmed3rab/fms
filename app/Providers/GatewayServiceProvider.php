<?php

namespace App\Providers;

use App\Gateway\Connections\ClientRepository;
use App\Gateway\Handlers\PingHandler;
use App\Gateway\Messages\Incoming\PingMessage;
use App\Gateway\Handlers\UnsubscribeHandler;
use App\Gateway\Handlers\SubscribeHandler;
use App\Gateway\Messages\Incoming\SubscribeMessage;
use App\Gateway\Handlers\AuthenticateHandler;
use App\Gateway\Messages\Incoming\UnsubscribeMessage;
use App\Gateway\Messages\Incoming\AuthenticateMessage;
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
