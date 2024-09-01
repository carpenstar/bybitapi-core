<?php

namespace Carpenstar\ByBitAPI\Core\Enums;

class WebSocketSpotIntervalEnum
{
    public const KLINE_1_MINUTE = '1m';
    public const KLINE_3_MINUTE = '3m';
    public const KLINE_5_MINUTE = '5m';
    public const KLINE_15_MINUTE = '15m';
    public const KLINE_30_MINUTE = '30m';
    public const KLINE_1_HOUR = '1h';
    public const KLINE_2_HOUR = '2h';
    public const KLINE_4_HOUR = '4h';
    public const KLINE_6_HOUR = '6h';
    public const KLINE_12_HOUR = '12h';
    public const KLINE_DAY = '1d';
    public const KLINE_WEEK = '1w';
    public const KLINE_MONTH = '1m';
    public const ALL = [
        self::KLINE_1_MINUTE,
        self::KLINE_3_MINUTE,
        self::KLINE_5_MINUTE,
        self::KLINE_15_MINUTE,
        self::KLINE_30_MINUTE,
        self::KLINE_1_HOUR,
        self::KLINE_2_HOUR,
        self::KLINE_4_HOUR,
        self::KLINE_6_HOUR,
        self::KLINE_12_HOUR,
        self::KLINE_DAY,
        self::KLINE_WEEK,
        self::KLINE_MONTH
    ];
}
