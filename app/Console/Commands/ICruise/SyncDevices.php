<?php

namespace App\Console\Commands\ICruise;

use App\Models\Device;
use App\Models\DeviceState;
use App\Models\Vehicle;
use App\Services\Geocoding\Contracts\Geocoder;
use App\Services\ICruise\ICruiseClient;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

#[Signature('icruise:sync-devices')]
#[Description('Command description')]
class SyncDevices extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(ICruiseClient $client, Geocoder $geocoder): int
    {
        $data = $client->trackers();
        $devices = $data['Tracker'];
        $positions = $data['Position'];
        $info = $data['Transfer'][0];

        $db_id = $devices[0]['DbID'];
        $vehicles = Vehicle::query()
            ->get()
            ->keyBy('icruise_vehicle_id');

        foreach ($devices as $device) {
            $vehicle = $vehicles->get($device['VehID']);
            Device::updateOrCreate(
                [
                    'icruise_product_id' => $device['ProductID'],
                ],
                [
                    'system_no' => $device['SystemNo'],
                    'imei' => $device['IMEI'],
                    'name' => $device['Name'],
                    'model' => $device['Model'],
                    'brand' => $device['Brand'],
                    'vehicle_id'    => $vehicle?->id,
                    'icruise_tot_id'    => $device['TotID'],
                    'icruise_object_id' => $device['ObjectID'],
                    'phone_number' => $device['PhoneNumber1'],
                    'tracker_status' => $device['TrackerStatus'],
                    'timezone' => $device['TimeZone'],
                    'payload' => $device,
                    'last_synced_at' => now(),
                ]
            );
        }

        $devicesBySystemNo = Device::pluck('id', 'system_no');
        foreach ($positions as $position) {
            $geoAddress = $geocoder->reverse(
                $position['Latitude'],
                $position['Longitude'],
            );
            $state = DeviceState::updateOrCreate([
                'device_id' => $devicesBySystemNo[$position['SystemNo']] ?? null,
            ], [
                'latitude'  => $position['Latitude'],
                'longitude' => $position['Longitude'],
                'geo_address' => $geoAddress,
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

        Cache::rememberForever('server-info', function () use ($info, $db_id) {
            return [
                'ip' => $info['ServerIP'],
                'domain' => $info['DomainName'],
                'db_id' => $db_id,
                'websocket'    => [
                    'domain'    => $info['WssDomainName'],
                    'port' => $info['WsOutputPort'],
                    'secure_port'   => $info['WssOutputPort'],
                ],
            ];

        });
        return self::SUCCESS;
    }
}
