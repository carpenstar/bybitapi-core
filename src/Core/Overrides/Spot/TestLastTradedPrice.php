<?php
namespace Carpenstar\ByBitAPI\Core\Overrides\Spot;

use Carpenstar\ByBitAPI\Spot\MarketData\LastTradedPrice\LastTradedPrice;

class TestLastTradedPrice extends LastTradedPrice
{
    use OverrideExecuteTrait;
}