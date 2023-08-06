<?php
namespace Carpenstar\ByBitAPI\Core\Helpers;

use Carpenstar\ByBitAPI\Core\Exceptions\SDKException;

class NumericHelper
{
    public static function checkValueMoreThan(float $value, float $minValue): void
    {
        if ($value < $minValue) {
            throw new SDKException("Value {$value} less than {$minValue}");
        }
    }

    public static function checkValueLessThan(float $value, float $maxValue): void
    {
        if ($value > $maxValue) {
            throw new SDKException("Value {$value} more than {$maxValue}");
        }
    }
}