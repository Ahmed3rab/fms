<?php

namespace App\Console\Commands;

use App\Data\Coordinates;
use App\Data\RealtimeDeviceState;
use App\Data\Speed;
use App\Data\TrackingTimestamps;
use App\Models\Vehicle;
use App\Services\Tracking\TrackingManager;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('gateway:test-publish {vehicle}')]
#[Description('Command description')]
class TestPublish extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(TrackingManager $tracking): int
    {
        $vehicle = Vehicle::query()
            ->with('device')
            ->whereUuid($this->argument('vehicle'))
            ->firstOrFail();

        if (! $vehicle->device) {
            $this->error('Vehicle has no device attached.');

            return self::FAILURE;
        }

        $state = new RealtimeDeviceState(
            deviceUuid: $vehicle->device->uuid,
            coordinates: new Coordinates(
                latitude: 32.8872,
                longitude: 13.1913,
            ),
            acc: 34,
            geoAddress: null,
            altitude: null,
            speed: new Speed(65),
            gpsStatus: null,
            angle: null,
            oil: null,
            voltage: null,
            mileage: null,
            temperature: null,
            timestamps: new TrackingTimestamps(gps: now(), received: now(), lastSynced: now()),
            payload: []
        );

        $tracking->ingestRealTimeState($state);

        $this->info('Realtime state dispatched.');

        return self::SUCCESS;
    }
}
