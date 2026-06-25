<?php

namespace App\Services\Tracking;

use App\Data\History;
use App\Data\RealtimeDeviceState;
use App\Gateway\Events\TelemetryEvent;
use App\Gateway\PubSub\GatewayPublisher;
use App\Models\Device;
use App\Models\Vehicle;
use App\Services\Tracking\Contracts\TrackingProvider;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class TrackingManager
{
    public function __construct(protected TrackingProvider $provider, protected GatewayPublisher $publisher, protected DeviceStateStore $store) {}

    public function attachCurrentState(Device $device): Device
    {
        return $this->provider->attachCurrentState($device);
    }

    /**
     * @param Collection<array-key,mixed> $devices
     * @return Collection<int,Device>
     */
    public function attachCurrentStateForMany(Collection $devices): Collection
    {
        return $this->provider->attachCurrentStateForMany($devices);
    }

    public function hydrateVehicle(Vehicle $vehicle): Vehicle
    {
        return $this->provider->hydrateVehicle($vehicle);
    }

    /**
     * @param Collection<array-key,mixed> $vehicles
     * @return Collection<int,Vehicle>
     */
    public function hydrateVehicles(Collection $vehicles): Collection
    {
        return $this->provider->hydrateVehicles($vehicles);
    }

    public function history(Vehicle $vehicle, Carbon $from, Carbon $to): History
    {
        return $this->provider->history($vehicle, $from, $to);
    }

    public function ingestRealTimeState(RealtimeDeviceState $state): void
    {
        if ($state->deviceUuid === null) {
            return;
        }

        $this->store->put(
            $state,
        );

        $this->publisher->publish(
            new TelemetryEvent($state),
        );
    }
}
