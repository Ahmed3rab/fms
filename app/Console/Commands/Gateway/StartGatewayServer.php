<?php

namespace App\Console\Commands\Gateway;

use App\Gateway\GatewayRuntime;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('gateway:serve')]
#[Description('Start the tracking gateway server')]
class StartGatewayServer extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return void
     */
    public function handle(GatewayRuntime $runtime): int
    {
        $runtime->start();

        return self::SUCCESS;
    }

}
