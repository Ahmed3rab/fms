<?php

namespace App\Gateway\Events;

use App\Data\RealtimeDeviceState;
use App\Gateway\Events\Contracts\GatewayEvent;

final readonly class TelemetryEvent extends GatewayEvent
{
    public function __construct(public RealtimeDeviceState $state) {}

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
            RealtimeDeviceState::fromArray(
                $payload['state'],
            ),
        );
    }
}
