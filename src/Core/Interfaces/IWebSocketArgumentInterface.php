<?php

namespace Carpenstar\ByBitAPI\Core\Interfaces;

interface IWebSocketArgumentInterface
{
    public const DEFAULT_SOCKET_CLIENT_TIMEOUT = 1000;

    public function getTopic(): array;
}
