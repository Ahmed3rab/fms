<?php

namespace App\Gateway\Protocol\Handlers\Contracts;

use App\Gateway\Connections\Connection;
use App\Gateway\Protocol\Messages\Contracts\IncomingMessage;

interface MessageHandler
{
    public function __invoke(Connection $connection, IncomingMessage $message): void;
}
