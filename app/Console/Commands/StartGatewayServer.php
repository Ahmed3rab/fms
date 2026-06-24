<?php

namespace App\Console\Commands;

use App\Gateway\Gateway;
use App\Gateway\Transport\Contracts\GatewayTransport;
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
    public function handle(GatewayTransport $transport, Gateway $gateway): int
    {
        $transport->start($gateway);

        return self::SUCCESS;
    }
}
