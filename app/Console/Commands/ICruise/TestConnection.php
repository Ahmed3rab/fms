<?php

namespace App\Console\Commands\ICruise;

use App\Models\Device;
use App\Services\ICruise\ICruiseClient;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('icruise:test')]
#[Description('Command description')]
class TestConnection extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(ICruiseClient $client): int
    {
        $response = $client->trackers();

        dump($response);

        return self::SUCCESS;
    }
}
