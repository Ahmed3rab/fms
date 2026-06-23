<?php

namespace App\Services\WebSocket;

use React\Socket\ConnectionInterface;

class TrackingWebSocketServer
{
    public function __construct(protected SubscriptionManager $subscriptions) {}

    public function connected(ConnectionInterface $connection): void
    {
        $client = WebSocketClient::connect($connection);

        $this->subscriptions->add($client);

        $this->listen($client);
    }

    private function listen(WebSocketClient $client): void
    {
        $client->connection->on('data', fn(string $data) => $this->handleMessage($client, $data));

        $client->connection->on('close', fn() => $this->subscriptions->remove($client));

        $client->connection->on('error', fn() => $this->subscriptions->remove($client));
    }

    private function handleMessage(WebSocketClient $client, string $message): void {}
}
