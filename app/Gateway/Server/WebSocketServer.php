<?php

namespace App\Gateway\Server;

use App\Gateway\Connections\Client;
use App\Gateway\Connections\ClientConnection;
use App\Gateway\Routing\MessageRouter;
use App\Gateway\Messages\MessageFactory;
use App\Gateway\Connections\ClientRepository;

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
