<?php

namespace App\Console\Commands\ICruise;

use App\Services\ICruise\ICruiseClient;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

#[Signature('icruise:server-info')]
#[Description('Command description')]
class ServerInfo extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(ICruiseClient $client): int
    {
        $info = $client->trackers()['Transfer'][0];
        Cache::rememberForever('server-info', function () use ($info) {
            return [
                'ip' => $info['ServerIP'],
                'domain' => $info['DomainName'],
                'websocket'    => [
                    'domain'    => $info['WssDomainName'],
                    'port' => $info['WssOutputPort'],
                    'secure_port'   => $info['WssOutputPort'],
                ],
            ];

        });
        return self::SUCCESS;
    }
}
