<?php
namespace Carpenstar\ByBitAPI\Core\Overrides\Spot;

use Carpenstar\ByBitAPI\Spot\MarketData\MergedOrderBook\MergedOrderBook;

class TestMergedOrderBook extends MergedOrderBook
{
    use OverrideExecuteTrait;
}