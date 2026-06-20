<?php

namespace App\Console\Commands\ICruise\Websocket;

use App\Services\ICruise\Realtime\ICruiseRealtimeClient;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('icruise:ws-listen')]
#[Description('Command description')]
class Listen extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(ICruiseRealtimeClient $client): int
    {
        $client->connect();

        return self::SUCCESS;
    }
}
