<?php
namespace Carpenstar\ByBitAPI\Core\Overrides\Spot;

use Carpenstar\ByBitAPI\Spot\Trade\OrderHistory\OrderHistory;

class TestOrderHistory extends OrderHistory
{
    use OverrideExecuteTrait;
}