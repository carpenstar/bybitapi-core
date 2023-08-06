<?php
namespace Carpenstar\ByBitAPI\Core\Enums;

interface EnumSide
{
    public const BUY = "Buy";
    public const SELL = "Sell";

    public const ORDER_SIDE_LIST = [
        self::BUY,
        self::SELL
    ];
}