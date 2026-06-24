<?php

namespace App\Gateway\Connections;

use OpenSwoole\Http\Request;

class ConnectionFactory
{
    public function create(Request $request): Connection
    {
        return new Connection(
            id: $request->fd,
            ip: $request->server['remote_addr'] ?? 'unknown',
            headers: $request->header ?? [],
        );
    }
}
