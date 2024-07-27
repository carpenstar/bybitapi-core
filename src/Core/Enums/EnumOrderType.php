<?php

namespace Carpenstar\ByBitAPI\Core\Enums;

interface EnumOrderType
{
    public const LIMIT = "Limit";
    public const MARKET = "Market";
    public const LIMIT_MAKER = "Limit_maker";

    public const ORDER_TYPE_LIST = [
        self::LIMIT,
        self::MARKET,
        self::LIMIT_MAKER
    ];
}
