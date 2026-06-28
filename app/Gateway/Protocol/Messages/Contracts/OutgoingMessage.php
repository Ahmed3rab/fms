<?php

namespace App\Gateway\Protocol\Messages\Contracts;

use JsonSerializable;

abstract readonly class OutgoingMessage implements JsonSerializable
{
    abstract public static function type(): string;

    /**
     * @return array<string,mixed>
     */
    abstract protected function data(): array;

    /**
     * @return array<string,mixed>
     */
    final public function toArray(): array
    {
        return [
            'type' => static::type(),
            'data' => $this->data(),
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
            JSON_THROW_ON_ERROR
            | JSON_UNESCAPED_SLASHES
            | JSON_UNESCAPED_UNICODE,
        );
    }
}
