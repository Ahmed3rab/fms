<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\Device;
use App\Services\ICruise\ICruiseClient;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

#[Signature('icruise:sync-companies')]
#[Description('Command description')]
class SyncICruiseCompanies extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(ICruiseClient $client): int
    {
        $companies = $client->companies();

        foreach ($companies as $company) {
            Company::updateOrCreate(
                [
                    'icruise_company_id' => $company['CompanyID'],
                ],
                [
                    'name' => $company['CompanyName'],
                    'slug'  => Str::slug($company['CompanyName']),
                    'payload' => $company,
                    'last_synced_at' => now(),
                ]
            );
        }
        return self::SUCCESS;
    }
}
