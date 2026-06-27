<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;
use OpenSwoole\Coroutine;

#[Signature('app:test-redis-subscribe')]
#[Description('Command description')]
class TestRedisSubscribe extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        Coroutine::create(function () {
            Redis::subscribe(['tracking:realtime'], function ($payload) {
                dump($payload);
            });

        });
        //
    }
}
