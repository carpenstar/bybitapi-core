<?php
namespace Carpenstar\ByBitAPI\Core\Overrides\Spot;

use Carpenstar\ByBitAPI\Spot\Trade\GetOrder\GetOrder;

class TestGetOrder extends GetOrder
{
    use OverrideExecuteTrait;
}