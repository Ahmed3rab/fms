<?php

namespace App\Services\WebSocket\Messages;

use App\Enums\WebSocketMessageType;
use App\Services\WebSocket\Messages\Contracts\IncomingMessage;
use App\Services\WebSocket\Messages\Incoming\AuthenticateMessage;
use App\Services\WebSocket\Messages\Incoming\PingMessage;
use App\Services\WebSocket\Messages\Incoming\SubscribeMessage;
use App\Services\WebSocket\Messages\Incoming\UnsubscribeMessage;
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
