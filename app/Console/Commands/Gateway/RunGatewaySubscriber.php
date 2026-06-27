<?php

namespace App\Console\Commands\Gateway;

use App\Gateway\PubSub\GatewaySubscriber;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('gateway:subscriber')]
#[Description('Runs the Gateway Redis subscriber')]
class RunGatewaySubscriber extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(GatewaySubscriber $subscriber): int
    {
        $this->info('Gateway subscriber started.');

        $subscriber->listen();

        return self::SUCCESS;
    }
}
