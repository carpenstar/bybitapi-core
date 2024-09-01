<?php

namespace Carpenstar\ByBitAPI\Core\Objects\WebSockets;

use Carpenstar\ByBitAPI\Core\Interfaces\IWebSocketArgumentInterface;

abstract class WebSocketArgument implements IWebSocketArgumentInterface
{
    protected ?string $reqId;

    protected string $symbols;

    public function __construct(string $symbol, ?string $reqId = null)
    {
        $this->symbols = $symbol;
        $this->reqId = $reqId;
    }

    /**
     * @return string
     */
    public function getReqId(): string
    {
        return $this->reqId;
    }

    /**
     * @return string
     */
    public function getSymbols(): string
    {
        return $this->symbols;
    }
}
