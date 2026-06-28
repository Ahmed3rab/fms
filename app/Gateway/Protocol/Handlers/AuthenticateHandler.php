<?php

namespace App\Gateway\Protocol\Handlers;

use App\Gateway\Authentication\GatewayAuthenticator;
use App\Gateway\Connections\Connection;
use App\Gateway\Gateway;
use App\Gateway\Protocol\Handlers\Contracts\MessageHandler;
use App\Gateway\Protocol\Messages\Contracts\IncomingMessage;
use App\Gateway\Protocol\Messages\Outgoing\AuthenticatedMessage;

class AuthenticateHandler implements MessageHandler
{
    public function __construct(protected GatewayAuthenticator $authenticator) {}

    public function handle(Gateway $gateway, Connection $connection, IncomingMessage $message): void
    {
        /** @var AuthenticateMessage $message */
        $this->authenticator->authenticate(
            $connection->client(),
            $message->accessToken,
        );

        $gateway->send(
            $connection,
            new AuthenticatedMessage(),
        );
    }
}
