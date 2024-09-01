<?php

namespace Carpenstar\ByBitAPI\Core\Objects\WebSockets\Channels;

use Carpenstar\ByBitAPI\Core\Interfaces\IChannelHandlerInterface;
use Workerman\Connection\TcpConnection;

abstract class ChannelHandler implements IChannelHandlerInterface
{
    abstract public function handle($data, TcpConnection $connection): void;
}
