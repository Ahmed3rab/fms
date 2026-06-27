<?php

namespace App\Gateway;

use App\Gateway\Connections\Connection;
use App\Gateway\Connections\ConnectionRepository;
use App\Gateway\Protocol\Messages\Contracts\OutgoingMessage;
use App\Gateway\Protocol\Messages\MessageFactory;
use App\Gateway\Routing\MessageRouter;
use App\Gateway\Subscriptions\SubscriptionManager;
use App\Gateway\Transport\Contracts\GatewayTransport;
use OpenSwoole\Http\Request;

class Gateway
{
    public function __construct(
        protected GatewayTransport $transport,
        protected ConnectionRepository $connections,
        protected SubscriptionManager $subscriptions,
        protected MessageFactory $messages,
        protected MessageRouter $router,
    ) {}

    public function start(): void
    {
        $this->transport->start($this);
    }

    public function connect(Request $request): void
    {
        $connection = Connection::fromRequest($request);
        $this->connections->put($connection);

        logger()->info('Gateway connected', [
            'connection' => $connection->id(),
            'ip' => $connection->ip(),
        ]);
    }

    public function receive(int $connectionId, string $payload): void
    {
        $connection = $this->connections->get($connectionId);

        if ($connection === null) {
            logger()->warning('Message received for unknown connection.', [
                'connection' => $connectionId,
            ]);

            return;
        }

        $message = $this->messages->make($payload);

        $this->router->dispatch(
            $this,
            $connection,
            $message,
        );
    }

    public function disconnect(int $connectionId): void
    {
        $connection = $this->connections->get($connectionId);

        if ($connection === null) {
            return;
        }

        $this->connections->forget($connectionId);

        logger()->info('Gateway disconnected', [
            'connection' => $connectionId,
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

    public function disconnectConnection(Connection $connection): void
    {
        $this->transport->disconnect($connection);

        $this->connections->forget($connection->id());
    }
}
