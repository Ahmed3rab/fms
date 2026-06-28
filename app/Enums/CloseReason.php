<?php

namespace App\Enums;

enum CloseReason: string
{
    case HeartbeatTimeout = 'heartbeat_timeout';
    case DuplicateAuthentication = 'duplicate_authentication';
    case ServerShutdown = 'server_shutdown';
    case Maintenance = 'maintenance';
    case Unauthorized = 'unauthorized';
    case ProtocolViolation = 'protocol_violation';
}
