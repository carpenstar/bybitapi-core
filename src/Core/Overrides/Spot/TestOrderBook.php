<?php
namespace Carpenstar\ByBitAPI\Core\Overrides\Spot;

use Carpenstar\ByBitAPI\Spot\MarketData\OrderBook\OrderBook;

class TestOrderBook extends OrderBook
{
    use OverrideExecuteTrait;
}