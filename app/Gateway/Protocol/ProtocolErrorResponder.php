<?php

namespace App\Gateway\Protocol;

use App\Gateway\Connections\Connection;
use App\Gateway\Gateway;
use App\Gateway\Protocol\Exceptions\ProtocolException;
use App\Gateway\Protocol\Messages\Outgoing\ErrorMessage;

class ProtocolErrorResponder
{
    public function respond(Gateway $gateway, Connection $connection, ProtocolException $exception): void
    {
        $gateway->send(
            $connection,
            new ErrorMessage(
                $exception->codeName(),
                $exception->getMessage(),
            ),
        );
    }
}
