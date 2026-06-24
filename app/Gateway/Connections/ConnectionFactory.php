<?php

namespace App\Gateway\Connections;

use OpenSwoole\Http\Request;
use OpenSwoole\WebSocket\Server;

class ConnectionFactory
{
    public function create(Server $server, Request $request): Connection {}
}
