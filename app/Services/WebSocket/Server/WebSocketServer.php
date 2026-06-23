<?php

namespace App\Services\WebSocket\Server;

use App\Services\WebSocket\Connections\Client;
use App\Services\WebSocket\Connections\ClientConnection;
use App\Services\WebSocket\Connections\ClientRepository;
use App\Services\WebSocket\Messages\MessageFactory;
use App\Services\WebSocket\Routing\MessageRouter;

class WebSocketServer
{
    public function __construct(
        protected ClientRepository $clients,
        protected MessageFactory $factory,
        protected MessageRouter $router,
    ) {}

    public function onOpen(string $connectionId, mixed $socket): void
    {
        $this->clients->put(
            $connectionId,
            new ClientConnection(
                id: $connectionId,
                socket: $socket,
                client: new Client(),
            ),
        );
    }

    public function onMessage(string $connectionId, string $message): void
    {
        $connection = $this->clients->get($connectionId);

        if (! $connection) {
            return;
        }

        $this->router->route(
            $connection,
            $this->factory->make($message),
        );
    }

    public function onClose(string $connectionId): void
    {
        $this->clients->forget($connectionId);
    }
}
