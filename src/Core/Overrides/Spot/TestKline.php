<?php
namespace Carpenstar\ByBitAPI\Core\Overrides\Spot;

use Carpenstar\ByBitAPI\Spot\MarketData\Kline\Kline;

class TestKline extends Kline
{
    use OverrideExecuteTrait;
}