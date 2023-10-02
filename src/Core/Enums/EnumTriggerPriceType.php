<?php
namespace Carpenstar\ByBitAPI\Core\Enums;

interface EnumTriggerPriceType
{
    public const PRICE_TYPE_LAST = "LastPrice";

    public const PRICE_TYPE_MARK = "MarkPrice";

    public const PRICE_TYPE_INDEX = "IndexPrice";

    public const PRICE_TYPE_LIST = [
        self::PRICE_TYPE_INDEX,
        self::PRICE_TYPE_LAST,
        self::PRICE_TYPE_MARK
    ];

}