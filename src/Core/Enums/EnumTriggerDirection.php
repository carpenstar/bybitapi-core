<?php
namespace Carpenstar\ByBitAPI\Core\Enums;

interface EnumTriggerDirection
{
    public const DIRECTION_UP = 1;

    public const DIRECTION_DOWN = 2;

    public const DIRECTION_LIST = [
        self::DIRECTION_UP,
        self::DIRECTION_DOWN
    ];
}