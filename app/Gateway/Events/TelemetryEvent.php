<?php

namespace App\Gateway\Events;

use App\Data\ResolvedDeviceState;
use App\Gateway\Events\Contracts\GatewayEvent;

final readonly class TelemetryEvent extends GatewayEvent
{
    public function __construct(public ResolvedDeviceState $state) {}

    public static function type(): string
    {
        return 'telemetry';
    }

    protected function payload(): array
    {
        return [
            'state' => $this->state,
        ];
    }

    /**
     * @param array<string,mixed> $payload
     */
    public static function fromArray(array $payload): self
    {
        return new self(
            ResolvedDeviceState::fromArray(
                $payload['state'],
            ),
        );
    }
}
