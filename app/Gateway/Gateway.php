<?php

namespace App\Gateway;

use App\Gateway\Connections\Connection;
use App\Gateway\Connections\ConnectionRepository;
use App\Gateway\Protocol\Messages\Contracts\OutgoingMessage;
use App\Gateway\Protocol\Messages\MessageFactory;
use App\Gateway\Routing\MessageRouter;
use App\Gateway\Transport\Contracts\GatewayTransport;

class Gateway
{
    public function __construct(
        protected GatewayTransport $transport,
        protected ConnectionRepository $connections,
        protected MessageFactory $messages,
        protected MessageRouter $router,
    ) {}

    public function start(): void
    {
        $this->transport->start($this);
    }

    public function connect(Connection $connection): void
    {
        $this->connections->put($connection);

        logger()->info('Gateway connected', [
            'connection' => $connection->id(),
            'ip' => $connection->ip(),
        ]);
    }

    public function receive(Connection $connection, string $message): void
    {
        $message = $this->messages->make($message);

        $this->router->dispatch(
            $connection,
            $message,
        );
    }

    public function disconnect(Connection $connection): void
    {
        $this->connections->forget($connection->id());

        logger()->info('Gateway disconnected', [
            'connection' => $connection->id(),
        ]);
    }

    public function connection(int $id): ?Connection
    {
        return $this->connections->get($id);
    }

    public function send(Connection $connection, OutgoingMessage $message): void
    {
        $this->transport->send(
            $connection,
            json_encode(
                $message->toJson(),
                JSON_THROW_ON_ERROR,
            ),
        );
    }
}
