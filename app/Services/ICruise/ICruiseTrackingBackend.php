<?php

namespace App\Services\ICruise;

use App\Models\Device;
use App\Services\Tracking\Contracts\TrackingBackend;
use App\Services\Tracking\DeviceStateStore;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class ICruiseTrackingBackend implements TrackingBackend
{
    public function __construct(protected DeviceStateStore $store, protected ICruiseClient $client) {}

    public function attachCurrentState(Device $device): Device
    {
        $device->loadMissing('state');

        $state = $this->store->get($device->system_no);

        $this->attachCurrentStateToDevice($device, $state);

        return $device;
    }

    /**
     * @param Collection<array-key,Model> $devices
     * @return Collection<array-key,Model>
     */
    public function attachCurrentStateForMany(Collection $devices): Collection
    {
        $states = $this->store->many($devices->pluck('system_no')->all());

        $devices->each(function (Device $device) use ($states) {

            $state = $states[$device->system_no] ?? null;

            $this->attachCurrentStateToDevice($device, $state);
        });

        return $devices;
    }

    public function history(Device $device, Carbon $from, Carbon $to): Collection
    {
        $history = $this->client->history(
            $device->icruise_product_id,
            $from,
            $to,
        );

        return collect($history['Data'] ?? []);
    }
    /**
     * @return void
     */
    private function attachCurrentStateToDevice(Device $device, ?array $realtimeState): void
    {
        if ($realtimeState) {
            $realtimeState['source'] = 'realtime';
            $device->setResolvedState($realtimeState);
            return;
        }

        if ($device->state) {
            $device->state->source = 'database';
            $device->setResolvedState($device->state);
        }
    }
}
