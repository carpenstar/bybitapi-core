<?php
namespace Carpenstar\ByBitAPI\Core\Overrides\Spot;

use Carpenstar\ByBitAPI\Spot\MarketData\PublicTradingRecords\PublicTradingRecords;

class TestPublicTradingRecords extends PublicTradingRecords
{
    use OverrideExecuteTrait;
}