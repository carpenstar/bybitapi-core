<?php
namespace Carpenstar\ByBitAPI\Core\Overrides\Spot;

use Carpenstar\ByBitAPI\Spot\Trade\BatchCancelOrderById\BatchCancelOrderById;

class TestBatchCancelOrderById extends BatchCancelOrderById
{
    use OverrideExecuteTrait;
}