<?php

namespace App\Console\Commands\ICruise;

use App\Models\Company;
use App\Models\Vehicle;
use App\Services\ICruise\ICruiseClient;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

#[Signature('icruise:sync-vehicles')]
#[Description('Command description')]
class SyncVehicles extends Command
{
    /**
     * Execute the console command.
     * @return int
     */
    public function handle(ICruiseClient $client): int
    {
        $companies = Company::query()
            ->get()
            ->keyBy('icruise_company_id');
        foreach ($client->allVehicles() as $data) {
            $company = $companies->get($data['SubCompanyID']);
            Vehicle::updateOrCreate(
                [
                    'icruise_vehicle_id' => $data['VehID'],
                ],
                [
                    'company_id' => $company?->id,

                    'plate_number' => $data['PlateNO'] ?: null,

                    'brand' => $data['Brand'] ?: null,
                    'model' => $data['Model'] ?: null,

                    // Color currently comes as an integer code.
                    // Keep the raw value for now.
                    'color' => (string) $data['Color'],

                    // VIN = Vehicle Identification Number
                    // iCruise returns it as ChassisNumber.
                    'chassis_number' => $data['ChassisNumber'] ?: null,

                    'engine_number' => $data['EngineNumber'] ?: null,

                    'owner_name' => $data['OwnerName'] ?: null,
                    'owner_phone' => $data['Phone'] ?: null,

                    'purchase_date' => $this->normalizeDate($data['PurchaseDate']),
                    'installation_date' => $this->normalizeDate($data['InstallDate']),

                    'payload' => $data,
                    'last_synced_at' => now(),
                ]
            );
        }

        return self::SUCCESS;
    }

    protected function normalizeDate(?string $date): ?Carbon
    {
        if (blank($date) || str_starts_with($date, '1900-01-01')) {
            return null;
        }

        return Carbon::parse($date);
    }
}
