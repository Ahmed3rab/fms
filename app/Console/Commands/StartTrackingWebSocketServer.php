<?php

namespace App\Console\Commands;

use App\Services\WebSocket\TrackingWebSocketServer;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('websocket:start')]
#[Description('Start the tracking websocket server')]
class StartTrackingWebSocketServer extends Command
{
    public function __construct(protected TrackingWebSocketServer $server)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->info('Starting Tracking WebSocket Server...');

        $this->server->start();

        return self::SUCCESS;
    }
}
