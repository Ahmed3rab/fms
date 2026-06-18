<?php

namespace App\Console\Commands;

use App\Services\ICruise\ICruiseClient;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('icruise:test')]
#[Description('Command description')]
class ICruiseTest extends Command
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
