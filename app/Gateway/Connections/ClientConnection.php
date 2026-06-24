<?php

namespace App\Gateway\Connections;

use App\Gateway\Messages\Contracts\OutgoingMessage;

class ClientConnection
{
    public function __construct(
        public readonly string $id,
        public readonly mixed $socket,
        public Client $client,
    ) {}

    public function send(OutgoingMessage $message): void
    {
        $this->socket->send(
            $message->toJson()
        );
    }
}
