<?php

namespace App\Services\WebSocket\Routing;

use App\Services\WebSocket\Connections\Client;
use App\Services\WebSocket\Handlers\AuthenticateHandler;
use App\Services\WebSocket\Handlers\PingHandler;
use App\Services\WebSocket\Handlers\SubscribeHandler;
use App\Services\WebSocket\Handlers\UnsubscribeHandler;
use App\Services\WebSocket\Messages\Contracts\IncomingMessage;
use App\Services\WebSocket\Messages\Incoming\AuthenticateMessage;
use App\Services\WebSocket\Messages\Incoming\PingMessage;
use App\Services\WebSocket\Messages\Incoming\SubscribeMessage;
use App\Services\WebSocket\Messages\Incoming\UnsubscribeMessage;

class MessageRouter
{
    public function __construct(
        protected AuthenticateHandler $authenticate,
        protected SubscribeHandler $subscribe,
        protected UnsubscribeHandler $unsubscribe,
        protected PingHandler $ping,
    ) {}

    public function route(Client $client, IncomingMessage $message): void
    {
        match (true) {
            $message instanceof AuthenticateMessage => ($this->authenticate)($client, $message),

            $message instanceof SubscribeMessage => ($this->subscribe)($client, $message),

            $message instanceof UnsubscribeMessage => ($this->unsubscribe)($client, $message),

            $message instanceof PingMessage => ($this->ping)($client, $message),

            default => null,
        };
    }
}
