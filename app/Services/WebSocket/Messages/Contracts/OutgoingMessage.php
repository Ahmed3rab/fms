<?php

namespace App\Services\WebSocket\Messages\Contracts;

use App\Enums\WebSocketMessageType;
use JsonSerializable;

abstract readonly class OutgoingMessage implements JsonSerializable
{
    public function __construct(public WebSocketMessageType $type) {}

    /**
     * @return array<string,mixed>
     */
    abstract protected function payload(): array;

    /**
     * @return array<string,mixed>
     */
    final public function toArray(): array
    {
        return [
            'type' => $this->type->value,
            ...$this->payload(),
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function toJson(): string
    {
        return json_encode(
            $this,
            JSON_THROW_ON_ERROR,
        );
    }
}
