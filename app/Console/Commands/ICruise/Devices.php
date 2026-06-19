<?php

namespace App\Console\Commands\ICruise;

use App\Models\Company;
use App\Models\Device;
use App\Services\ICruise\ICruiseClient;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('icruise:sync-devices')]
#[Description('Command description')]
class Devices extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(ICruiseClient $client): int
    {
        $devices = $client->trackers()['Tracker'];

        foreach ($devices as $device) {
            Device::updateOrCreate(
                [
                    'icruise_product_id' => $device['ProductID'],
                ],
                [
                    'company_id' => Company::where('icruise_company_id', $device['CompanyID'])->first()?->id,
                    'system_no' => $device['SystemNo'],
                    'imei' => $device['IMEI'],
                    'name' => $device['Name'],
                    'model' => $device['Model'],
                    'brand' => $device['Brand'],
                    'icruise_vehicle_id'    => $device['VehID'],
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
        return self::SUCCESS;
    }
}
