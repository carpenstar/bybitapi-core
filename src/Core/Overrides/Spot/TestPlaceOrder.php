<?php
namespace Carpenstar\ByBitAPI\Core\Overrides\Spot;

use Carpenstar\ByBitAPI\Spot\Trade\PlaceOrder\PlaceOrder;

class TestPlaceOrder extends PlaceOrder
{
    use OverrideExecuteTrait;
}