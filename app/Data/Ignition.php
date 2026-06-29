<?php

namespace App\Data;

use App\Enums\IgnitionStatus;
use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

/**
 * @implements Arrayable<array-key,mixed>
 */
final readonly class Ignition implements Arrayable, JsonSerializable
{
    public function __construct(public IgnitionStatus $status) {}

    public static function fromProvider(string|int|bool|null $value): ?self
    {
        if ($value === null) {
            return null;
        }

        return new self(
            match (strtoupper((string) $value)) {
                '1', 'ON', 'TRUE' => IgnitionStatus::On,
                default => IgnitionStatus::Off,
            },
        );
    }
    /**
     * @param array<int,mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(IgnitionStatus::from($data['status']));
    }

    public function toArray(): array
    {
        return [
            'status' => $this->status->value,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
