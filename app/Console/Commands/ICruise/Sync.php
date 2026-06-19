<?php

namespace App\Console\Commands\ICruise;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('icruise:sync')]
#[Description('Command description')]
class Sync extends Command
{
    /**
     * Execute the console command.
     * @return void
     */
    public function handle(): void
    {
        $this->call(Companies::class);
        $this->call(Devices::class);
        $this->call(DeviceState::class);
        $this->call(ServerInfo::class);
    }
}
