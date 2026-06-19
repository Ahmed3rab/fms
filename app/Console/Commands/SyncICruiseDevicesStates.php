<?php

namespace App\Console\Commands;

use App\Models\Device;
use App\Models\DeviceState;
use App\Services\ICruise\ICruiseClient;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('icruise:sync-states')]
#[Description('Command description')]
class SyncICruiseDevicesStates extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(ICruiseClient $client): int
    {
        $positions = $client->trackers()['Position'];

        foreach ($positions as $position) {
            DeviceState::updateOrCreate([
                'device_id' => Device::where('system_no', $position['SystemNo'])->first()?->id,
            ], [
                'latitude'  => $position['Latitude'],
                'longitude' => $position['Longitude'],
                'speed' => $position['Velocity'],
                'gps_time' => $position['DateTime'],
                'gps_status'    => $position['GpsStatus'],
                'angle' => $position['Angle'],
                'altitude'  => $position['Altitude'],
                'acc'   => $position['Acc'],
                'oil'   => $position['Oil'],
                'voltage'   => $position['Voltage'],
                'mileage'   => $position['Mileage'],
                'temperature' => $position['Temperature'],
                'payload'   => $position,
                'last_synced_at' => now(),
            ]);
        }
        return self::SUCCESS;
    }
}
