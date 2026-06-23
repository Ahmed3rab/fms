<?php

namespace App\Providers;

use App\Services\WebSocket\Connections\ClientRepository;
use App\Services\WebSocket\Handlers\PingHandler;
use App\Services\WebSocket\Messages\Incoming\PingMessage;
use App\Services\WebSocket\Handlers\UnsubscribeHandler;
use App\Services\WebSocket\Handlers\SubscribeHandler;
use App\Services\WebSocket\Messages\Incoming\SubscribeMessage;
use App\Services\WebSocket\Handlers\AuthenticateHandler;
use App\Services\WebSocket\Messages\Incoming\UnsubscribeMessage;
use App\Services\WebSocket\Messages\Incoming\AuthenticateMessage;
use App\Services\WebSocket\Routing\MessageRouter;
use App\Services\WebSocket\Subscriptions\SubscriptionManager;
use Illuminate\Support\ServiceProvider;

class WebSocketServiceProvider extends ServiceProvider
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
