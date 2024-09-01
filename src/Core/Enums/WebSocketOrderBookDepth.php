<?php
namespace Carpenstar\ByBitAPI\Core\Enums;

class WebSocketOrderBookDepth
{
    public const DEPTH_1 = '1';
    public const DEPTH_50 = '50';
    public const DEPTH_200 = '200';
    public const DEPTH_500 = '500';

    public const ALL = [
        self::DEPTH_1,
        self::DEPTH_50,
        self::DEPTH_200,
        self::DEPTH_500
    ];
}
