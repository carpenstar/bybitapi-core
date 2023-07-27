<?php
namespace Carpenstar\ByBitAPI\Core\Overrides\Spot;

use Carpenstar\ByBitAPI\Spot\Trade\TradeHistory\TradeHistory;

class TestTradeHistory extends TradeHistory
{
    use OverrideExecuteTrait;
}