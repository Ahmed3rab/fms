<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('gateway:start')]
#[Description('Start the tracking gateway server')]
class StartGatewayServer extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->info('Starting Tracking gateway Server...');

        $this->server->start();

        return self::SUCCESS;
    }
}
