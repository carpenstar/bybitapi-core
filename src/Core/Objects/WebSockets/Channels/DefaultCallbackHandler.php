<?php

namespace Carpenstar\ByBitAPI\Core\Objects\WebSockets\Channels;
use Workerman\Connection\TcpConnection;

class DefaultCallbackHandler extends ChannelHandler
{
    public function handle($data, TcpConnection $connection): void
    {
        var_dump($data);
    }
}
