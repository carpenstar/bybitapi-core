<?php
namespace Carpenstar\ByBitAPI\Core\Overrides\Spot;

use Carpenstar\ByBitAPI\Spot\MarketData\BestBidAskPrice\BestBidAskPrice;

class TestBidAskPrice extends BestBidAskPrice
{
    use OverrideExecuteTrait;
}