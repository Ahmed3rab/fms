<?php

namespace App\Data;

use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

final readonly class Distance implements Arrayable, JsonSerializable
{
    public function __construct(public float $kilometers) {}

    /**
     * @param float $value
     */
    public static function fromProvider(float $value): self
    {
        return self::fromKilometers($value);
    }

    public static function fromKilometers(float $km): self
    {
        return new self($km);
    }

    public static function fromMeters(float $meters): self
    {
        return new self($meters / 1000);
    }

    public function meters(): float
    {
        return $this->kilometers * 1000;
    }

    /**
     * @param array{km:float|int,meters:float|int} $data
     */
    public static function fromArray(array $data): self
    {
        return self::fromKilometers(
            (float) $data['km']
        );
    }

    public function toArray(): array
    {
        return [
            'km' => round($this->kilometers, 3),
            'meters' => round($this->meters(), 0),
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
