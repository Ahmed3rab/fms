<?php

namespace App\Gateway\Routing;

use App\Gateway\Connections\Connection;
use App\Gateway\Gateway;
use App\Gateway\Protocol\Exceptions\ProtocolException;
use App\Gateway\Protocol\Handlers\Contracts\MessageHandler;
use App\Gateway\Protocol\Messages\Contracts\IncomingMessage;
use App\Gateway\Protocol\ProtocolErrorResponder;
use RuntimeException;

class MessageRouter
{
    /**
     * @param array<class-string<IncomingMessage>, MessageHandler> $handlers
     */
    public function __construct(protected array $handlers, protected ProtocolErrorResponder $errors) {}

    public function dispatch(Gateway $gateway, Connection $connection, IncomingMessage $message): void
    {
        $handler = $this->handlers[$message::class] ?? null;
        if ($handler === null) {
            throw new RuntimeException(
                "No handler registered for " . $message::class
            );
        }
        try {
            $handler->handle(
                $gateway,
                $connection,
                $message,
            );
        } catch (ProtocolException $e) {
            $this->errors->respond(
                $gateway,
                $connection,
                $e,
            );
        }
    }
}
