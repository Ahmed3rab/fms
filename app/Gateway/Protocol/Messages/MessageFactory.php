<?php

namespace App\Gateway\Protocol\Messages;

use App\Enums\WebSocketMessageType;
use App\Gateway\Protocol\Messages\Contracts\IncomingMessage;
use App\Gateway\Protocol\Messages\Incoming\PingMessage;
use App\Gateway\Protocol\Messages\Incoming\UnsubscribeMessage;
use App\Gateway\Protocol\Messages\Incoming\SubscribeMessage;
use App\Gateway\Protocol\Messages\Incoming\AuthenticateMessage;
use InvalidArgumentException;

class MessageFactory
{
    public function make(string $json): IncomingMessage
    {
        $payload = json_decode($json, true);

        if (! is_array($payload)) {
            throw new InvalidArgumentException('Invalid websocket payload.');
        }

        return match (WebSocketMessageType::from($payload['type'])) {

            WebSocketMessageType::Authenticate => AuthenticateMessage::fromArray($payload),

            WebSocketMessageType::Subscribe => SubscribeMessage::fromArray($payload),

            WebSocketMessageType::Unsubscribe => UnsubscribeMessage::fromArray($payload),

            WebSocketMessageType::Ping => PingMessage::fromArray($payload),
        };
    }
}
