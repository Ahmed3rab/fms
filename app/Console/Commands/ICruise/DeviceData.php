<?php

namespace App\Console\Commands\ICruise;

use App\Models\Company;
use App\Models\Device;
use App\Models\DeviceState;
use App\Services\ICruise\ICruiseClient;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

#[Signature('icruise:fetch-data')]
#[Description('Command description')]
class DeviceData extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(ICruiseClient $client): int
    {
        $data = $client->trackers();
        $devices = $data['Tracker'];
        $positions = $data['Position'];
        $info = $data['Transfer'][0];
        $companies = Company::pluck('id', 'icruise_company_id');
        $db_id = $devices[0]['DbID'];
        foreach ($devices as $device) {
            Device::updateOrCreate(
                [
                    'icruise_product_id' => $device['ProductID'],
                ],
                [
                    'company_id' => $companies[$device['CompanyID']] ?? null,
                    'system_no' => $device['SystemNo'],
                    'imei' => $device['IMEI'],
                    'name' => $device['Name'],
                    'model' => $device['Model'],
                    'brand' => $device['Brand'],
                    // 'icruise_vehicle_id'    => $device['VehID'],
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
            DeviceState::updateOrCreate([
                'device_id' => $devicesBySystemNo[$position['SystemNo']] ?? null,
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
