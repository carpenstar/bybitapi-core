<?php

namespace Carpenstar\ByBitAPI\Core\Interfaces;

use Carpenstar\ByBitAPI\Core\Auth\Credentials;

interface IWebSocketsChannelInterface
{
    public function __construct(IWebSocketArgumentInterface $arguments, Credentials $credentials, IChannelHandlerInterface $channelHandler);

    /**
     * @return string
     */
    public function getResponseClassname(): string;

    /**
     * @return void
     */
    public function execute(): void;
}
