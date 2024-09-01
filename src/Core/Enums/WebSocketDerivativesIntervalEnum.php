<?php
namespace Carpenstar\ByBitAPI\Core\Enums;

class WebSocketDerivativesIntervalEnum
{
    public const KLINE_1_MINUTE = 1;
    public const KLINE_3_MINUTE = 3;
    public const KLINE_5_MINUTE = 5;
    public const KLINE_15_MINUTE = 15;
    public const KLINE_30_MINUTE = 30;
    public const KLINE_60_MINUTE = 60;
    public const KLINE_120_MINUTE = 120;
    public const KLINE_240_MINUTE = 240;
    public const KLINE_360_MINUTE = 360;
    public const KLINE_720_MINUTE = 720;
    public const KLINE_DAY = 'D';
    public const KLINE_WEEK = 'W';
    public const KLINE_MONTH = 'M';
    public const ALL = [
        self::KLINE_1_MINUTE,
        self::KLINE_3_MINUTE,
        self::KLINE_5_MINUTE,
        self::KLINE_15_MINUTE,
        self::KLINE_30_MINUTE,
        self::KLINE_60_MINUTE,
        self::KLINE_120_MINUTE,
        self::KLINE_240_MINUTE,
        self::KLINE_360_MINUTE,
        self::KLINE_720_MINUTE,
        self::KLINE_DAY,
        self::KLINE_WEEK,
        self::KLINE_MONTH
    ];
}
