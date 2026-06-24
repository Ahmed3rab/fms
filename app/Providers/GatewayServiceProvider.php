<?php

namespace App\Providers;

use App\Gateway\Connections\ConnectionRepository;
use App\Gateway\Events\GatewayEventRegistry;
use App\Gateway\Events\TelemetryEvent;
use App\Gateway\Protocol\Handlers\AuthenticateHandler;
use App\Gateway\Protocol\Handlers\PingHandler;
use App\Gateway\Protocol\Handlers\UnsubscribeHandler;
use App\Gateway\Protocol\Messages\Incoming\PingMessage;
use App\Gateway\Protocol\Handlers\SubscribeHandler;
use App\Gateway\Protocol\Messages\Incoming\UnsubscribeMessage;
use App\Gateway\Protocol\Messages\Incoming\AuthenticateMessage;
use App\Gateway\Protocol\Messages\Incoming\SubscribeMessage;
use App\Gateway\Protocol\Messages\MessageRegistry;
use App\Gateway\Routing\MessageRouter;
use App\Gateway\Subscriptions\SubscriptionManager;
use App\Gateway\Transport\Contracts\GatewayTransport;
use App\Gateway\Transport\OpenSwooleTransport;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class GatewayServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(GatewayTransport::class, OpenSwooleTransport::class);
        $this->app->singleton(ConnectionRepository::class);
        $this->app->singleton(SubscriptionManager::class);
        $this->app->singleton(MessageRouter::class, fn(Application $app) => $this->registerHandlers($app));
        $this->app->singleton(MessageRegistry::class, fn() => $this->registerProtocols());
        $this->app->singleton(
            GatewayEventRegistry::class,
            function () {
                $registry = new GatewayEventRegistry();
                $registry->register(TelemetryEvent::class);
                return $registry;
            },
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }

    private function registerProtocols(): MessageRegistry
    {
        $registry = new MessageRegistry();
        $registry->register(AuthenticateMessage::class);
        $registry->register(SubscribeMessage::class);
        $registry->register(UnsubscribeMessage::class);
        $registry->register(PingMessage::class);
        return $registry;
    }

    /**
     * @param Application $app
     */
    private function registerHandlers(Application $app): MessageRouter
    {
        return new MessageRouter([
            AuthenticateMessage::class => $app->make(AuthenticateHandler::class),
            SubscribeMessage::class => $app->make(SubscribeHandler::class),
            UnsubscribeMessage::class => $app->make(UnsubscribeHandler::class),
            PingMessage::class => $app->make(PingHandler::class),
        ]);
    }
}
