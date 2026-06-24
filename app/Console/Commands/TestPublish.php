<?php

namespace App\Console\Commands;

use App\Data\Coordinates;
use App\Data\RealtimeDeviceState;
use App\Data\Speed;
use App\Data\TrackingTimestamps;
use App\Data\VehicleStatus;
use App\Enums\ConnectivityStatus;
use App\Enums\MovementStatus;
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
        $vehicle = Vehicle::whereUuid($this->argument('vehicle'))->firstOrFail();

        $state = new RealtimeDeviceState(
            deviceUuid: "019eeeb4-e021-7348-a26b-d899578a2860",
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

        $tracking->publish($state);

        $this->info('Published.');

        return self::SUCCESS;
    }
}
