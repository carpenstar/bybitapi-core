<?php
namespace Carpenstar\ByBitAPI\Core\Overrides\Spot;

use Carpenstar\ByBitAPI\Spot\Trade\CancelOrder\CancelOrder;

class TestCancelOrder extends CancelOrder
{
    use OverrideExecuteTrait;
}