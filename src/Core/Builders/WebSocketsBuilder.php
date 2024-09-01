<?php

namespace Carpenstar\ByBitAPI\Core\Builders;

use Carpenstar\ByBitAPI\Core\Auth\Credentials;
use Carpenstar\ByBitAPI\Core\Interfaces\IFabricInterface;
use Carpenstar\ByBitAPI\Core\Interfaces\IResponseHandlerInterface;
use Carpenstar\ByBitAPI\Core\Interfaces\IWebSocketArgumentInterface;
use Carpenstar\ByBitAPI\Core\Interfaces\IWebSocketsChannelInterface;
use Carpenstar\ByBitAPI\Core\Objects\WebSockets\Channels\ChannelHandler;

class WebSocketsBuilder implements IFabricInterface
{
    public static function make(string $className, IWebSocketArgumentInterface $arguments = null, Credentials $credentials = null, ChannelHandler $callback = null): IWebSocketsChannelInterface
    {
        if (!in_array(IWebSocketsChannelInterface::class, class_implements($className))) {
            throw new \Exception("This websocket-channel {$className} must implement the interface " . IResponseHandlerInterface::class . "!");
        }

        return new $className($arguments, $credentials, $callback);
    }
}
