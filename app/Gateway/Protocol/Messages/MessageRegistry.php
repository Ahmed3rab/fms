<?php

namespace App\Gateway\Protocol\Messages;

use App\Gateway\Protocol\Messages\Contracts\IncomingMessage;
use InvalidArgumentException;

class MessageRegistry
{
    /**
     * @var array<string, class-string>
     */
    protected array $messages = [];

    /**
     * @param class-string<IncomingMessage> $message
     */
    public function register(string $type, string $message): void
    {
        if (! is_subclass_of($message, IncomingMessage::class)) {
            throw new InvalidArgumentException(
                "{$message} must implement IncomingMessage."
            );
        }

        $this->messages[$message::type()] = $message;
    }

    /**
     * @return class-string<IncomingMessage>
     */
    public function resolve(string $type): string
    {
        return $this->messages[$type]
            ?? throw new InvalidArgumentException(
                "Unknown websocket message type [{$type}]."
            );
    }
}
