<?php

namespace App\Services\ICruise;

use Illuminate\Support\Facades\Cache;

class ServerInfo
{
    public function all(): array
    {
        return Cache::get('server-info', []);
    }

    public function dbId(): ?string
    {
        return data_get($this->all(), 'db_id');
    }

    public function websocket(): array
    {
        return data_get($this->all(), 'websocket', []);
    }

    public function ip(): ?string
    {
        return data_get($this->all(), 'ip');
    }
}
