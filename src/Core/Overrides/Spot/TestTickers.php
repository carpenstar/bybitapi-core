<?php
namespace Carpenstar\ByBitAPI\Core\Overrides\Spot;

use Carpenstar\ByBitAPI\Spot\MarketData\Tickers\Tickers;

class TestTickers extends Tickers
{
    use OverrideExecuteTrait;
}