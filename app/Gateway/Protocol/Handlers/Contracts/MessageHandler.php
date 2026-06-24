<?php

namespace App\Gateway\Protocol\Handlers\Contracts;

use App\Gateway\Connections\Connection;
use App\Gateway\Gateway;
use App\Gateway\Protocol\Messages\Contracts\IncomingMessage;

interface MessageHandler
{
    public function handle(Gateway $gateway, Connection $connection, IncomingMessage $message): void;
}
