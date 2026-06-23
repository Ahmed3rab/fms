<?php

namespace App\Services\WebSocket\Server;

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
}
