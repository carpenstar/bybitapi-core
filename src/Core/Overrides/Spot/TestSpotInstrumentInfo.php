<?php
namespace Carpenstar\ByBitAPI\Core\Overrides\Spot;

use Carpenstar\ByBitAPI\Spot\MarketData\InstrumentInfo\InstrumentInfo;

class TestSpotInstrumentInfo extends InstrumentInfo
{
    use OverrideExecuteTrait;
}