<?php

namespace Carpenstar\ByBitAPI\Core\Enums;

class WebSocketTopicNameEnum
{
    /**
     * SPOT Channels
     */
    public const SPOT_KLINE = "kline";
    public const SPOT_BOOKTICKER = "bookticker";
    public const SPOT_ORDERBOOK = "orderbook";
    public const SPOT_PUBLIC_TRADE = "trade";
    public const SPOT_TICKERS = "tickers";

    /**
     * Derivatives Channel
     */
    public const DERIVATIVES_PUBLIC_TRADE = "publicTrade";
    public const DERIVATIVES_ORDERBOOK = "orderbook";
    public const DERIVATIVES_TICKERS = "tickers";
    public const DERIVATIVES_KLINE = "kline";
    public const DERIVATIVES_LIQUIDATION = "liquidation";
}
