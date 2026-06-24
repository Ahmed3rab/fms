<?php

namespace App\Console\Commands;

use App\Gateway\Gateway;
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

    /**
     * @return void
     */
    public function handle(Gateway $gateway): int
    {
        $gateway->start();

        return self::SUCCESS;
    }
}
